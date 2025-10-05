# SafePoint Multi-Unit Dispatch System - Technical Specification

**Date:** October 5, 2025  
**Version:** 2.0  
**Status:** APPROVED FOR IMPLEMENTATION

---

## 📋 Overview

Sistem dispatch cases ke multiple units dengan alur kerja:

1. **Operator** menerima cases dari warga
2. **Operator** dispatch ke **1 atau lebih unit** sekaligus
3. **Pimpinan Unit** menerima cases dan assign **petugas**
4. **Petugas** menangani kasus di lapangan

---

## 🎭 Role Structure

| Role           | Deskripsi                                     | Access Level              |
| -------------- | --------------------------------------------- | ------------------------- |
| **SUPERADMIN** | Admin tertinggi sistem                        | Full access               |
| **OPERATOR**   | Operator pusat yang menerima & dispatch cases | Case management           |
| **PIMPINAN**   | Pimpinan unit (operator unit)                 | Unit & petugas management |
| **PETUGAS**    | Petugas lapangan                              | Field response            |
| **CITIZEN**    | Warga pelapor                                 | Report emergency          |

---

## 💾 Database Schema

### 1. Table: `units`

```sql
CREATE TABLE units (
    id TEXT PRIMARY KEY,
    name TEXT NOT NULL,
    type TEXT NOT NULL, -- 'FIRE_DEPT', 'POLICE', 'AMBULANCE', 'SAR', etc
    location TEXT,
    phone TEXT,
    pimpinan_id TEXT, -- Foreign key ke users.id (WHERE role='PIMPINAN')
    is_active BOOLEAN DEFAULT 1,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (pimpinan_id) REFERENCES users(id) ON DELETE SET NULL
);
```

**Contoh Data:**

```sql
INSERT INTO units VALUES
('01K6...', 'Damkar Jakarta Pusat', 'FIRE_DEPT', 'Jl. Merdeka 1', '021-111', '01K5...', 1),
('01K7...', 'Polisi Jakarta Pusat', 'POLICE', 'Jl. Kepolisian 2', '021-222', '01K5...', 1),
('01K8...', 'Ambulans RS Pusat', 'AMBULANCE', 'Jl. Kesehatan 3', '021-333', '01K5...', 1);
```

---

### 2. Table: `users`

```sql
CREATE TABLE users (
    id TEXT PRIMARY KEY,
    name TEXT NOT NULL,
    email TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL,
    role TEXT NOT NULL, -- 'SUPERADMIN', 'OPERATOR', 'PIMPINAN', 'PETUGAS', 'CITIZEN'
    unit_id TEXT, -- NULL untuk SUPERADMIN/OPERATOR/CITIZEN, ada isi untuk PIMPINAN/PETUGAS
    phone TEXT,
    is_active BOOLEAN DEFAULT 1,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (unit_id) REFERENCES units(id) ON DELETE SET NULL
);

CREATE INDEX idx_users_role ON users(role);
CREATE INDEX idx_users_unit_id ON users(unit_id);
```

**Contoh Data:**

```sql
-- Operator
INSERT INTO users VALUES ('01K1...', 'Operator A', 'operator@safepoint.id', '...', 'OPERATOR', NULL, '081...', 1);

-- Pimpinan Damkar
INSERT INTO users VALUES ('01K2...', 'Pimpinan Damkar', 'pimpinan.damkar@safepoint.id', '...', 'PIMPINAN', '01K6...', '082...', 1);

-- Petugas Damkar
INSERT INTO users VALUES ('01K3...', 'Petugas Damkar A', 'petugas1@damkar.id', '...', 'PETUGAS', '01K6...', '083...', 1);
INSERT INTO users VALUES ('01K4...', 'Petugas Damkar B', 'petugas2@damkar.id', '...', 'PETUGAS', '01K6...', '084...', 1);
```

---

### 3. Table: `cases` (EXISTING - Modified)

```sql
-- Existing fields tetap ada
-- Field yang dihapus/diubah:
-- - verified_at → DIHAPUS (tidak ada verifikasi lagi)
-- - assigned_unit_id → TETAP NULL (karena multi-unit)
-- - assigned_petugas_id → TETAP NULL (diisi oleh pimpinan via case_dispatches)

-- Status flow:
-- NEW → DISPATCHED → ON_THE_WAY → ON_SCENE → CLOSED/CANCELLED
```

**Field yang terpengaruh:**

```sql
ALTER TABLE cases DROP COLUMN verified_at; -- HAPUS field verified_at
```

---

### 4. Table: `case_dispatches` (NEW)

```sql
CREATE TABLE case_dispatches (
    id TEXT PRIMARY KEY,
    case_id TEXT NOT NULL,
    unit_id TEXT NOT NULL,
    assigned_petugas_id TEXT NULL, -- Diisi oleh pimpinan
    dispatcher_id TEXT NOT NULL, -- ID operator yang dispatch
    notes TEXT,
    dispatched_at TIMESTAMP NOT NULL, -- Waktu operator dispatch
    assigned_at TIMESTAMP NULL, -- Waktu pimpinan assign petugas
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (case_id) REFERENCES cases(id) ON DELETE CASCADE,
    FOREIGN KEY (unit_id) REFERENCES units(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_petugas_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (dispatcher_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE INDEX idx_case_dispatches_case_id ON case_dispatches(case_id);
CREATE INDEX idx_case_dispatches_unit_id ON case_dispatches(unit_id);
CREATE INDEX idx_case_dispatches_petugas_id ON case_dispatches(assigned_petugas_id);
```

**Contoh Skenario:**

**Kasus Bencana Alam** → Operator dispatch ke 3 unit:

```sql
-- Case ID: 01KCASE123
-- Operator dispatch ke: Damkar, Polisi, Ambulans

INSERT INTO case_dispatches VALUES
('01KDISP1', '01KCASE123', '01K6DAMKAR', NULL, '01K1OPERATOR', 'Kebakaran besar', '2025-10-05 10:00:00', NULL),
('01KDISP2', '01KCASE123', '01K7POLICE', NULL, '01K1OPERATOR', 'Pengamanan area', '2025-10-05 10:00:00', NULL),
('01KDISP3', '01KCASE123', '01K8AMBULAN', NULL, '01K1OPERATOR', 'Evakuasi korban', '2025-10-05 10:00:00', NULL);

-- Pimpinan Damkar assign petugas
UPDATE case_dispatches
SET assigned_petugas_id = '01K3PETUGAS', assigned_at = '2025-10-05 10:05:00'
WHERE id = '01KDISP1';

-- Pimpinan Polisi assign petugas
UPDATE case_dispatches
SET assigned_petugas_id = '01K5PETUGASPOL', assigned_at = '2025-10-05 10:07:00'
WHERE id = '01KDISP2';
```

---

## 🔄 Workflow Flow

### Phase 1: Case Creation

```
Warga → Mobile App → POST /api/emergency
  ↓
System create case:
  - status: 'NEW'
  - assigned_unit_id: NULL
  - assigned_petugas_id: NULL
```

### Phase 2: Operator Dispatch (MULTI-UNIT)

```
Operator → Web Dashboard → Select Case → Dispatch Form
  ↓
Select Units (Multi-select):
  ☑ Damkar Jakarta Pusat
  ☑ Polisi Jakarta Pusat
  ☑ Ambulans RS Pusat
  ↓
Click "Kirim ke Unit"
  ↓
System:
  1. Update cases.status = 'DISPATCHED'
  2. Update cases.dispatched_at = NOW()
  3. Create 3 records di case_dispatches:
     - Record 1: case_id + unit_id(Damkar) + dispatcher_id
     - Record 2: case_id + unit_id(Polisi) + dispatcher_id
     - Record 3: case_id + unit_id(Ambulans) + dispatcher_id
  4. Send notifications ke 3 pimpinan unit
```

### Phase 3: Pimpinan Assign Petugas

```
Pimpinan Damkar → Login Dashboard → Lihat Cases → Select Case
  ↓
Lihat detail dispatch untuk unitnya
  ↓
Select Petugas dari dropdown
  ↓
Click "Tugaskan Petugas"
  ↓
System:
  1. Update case_dispatches.assigned_petugas_id
  2. Update case_dispatches.assigned_at = NOW()
  3. Send notification ke petugas
```

### Phase 4: Petugas Handle Case

```
Petugas → Mobile App → Terima Notifikasi → Accept Task
  ↓
Update location GPS real-time
  ↓
Status changes: ON_THE_WAY → ON_SCENE → CLOSED
```

---

## 🖥️ UI Changes

### 1. Operator Dispatch Form (cases/show.blade.php)

**SEBELUM:**

```blade
<select name="unit_id">  <!-- Single select -->
  <option>Pilih Unit</option>
</select>

<select name="petugas_id">  <!-- Dropdown petugas -->
  <option>Pilih Petugas</option>
</select>

<button>Kirim ke Unit</button>
```

**SESUDAH:**

```blade
<!-- HAPUS button verify -->

<!-- Multi-select units -->
<div>
  <label>Pilih Unit (bisa lebih dari 1)</label>
  <div class="space-y-2">
    @foreach($units as $unit)
      <label class="flex items-center">
        <input type="checkbox" name="unit_ids[]" value="{{ $unit->id }}" class="mr-2">
        <span>{{ $unit->name }} ({{ $unit->type }})</span>
        <span class="ml-2 text-sm text-gray-500">Pimpinan: {{ $unit->pimpinan->name ?? '-' }}</span>
      </label>
    @endforeach
  </div>
</div>

<!-- HAPUS dropdown petugas -->

<textarea name="notes" placeholder="Catatan untuk pimpinan unit..."></textarea>

<button>Kirim ke Unit yang Dipilih</button>
```

---

### 2. Dashboard Pimpinan (NEW PAGE)

**Route:** `/pimpinan/dashboard`

**Features:**

-   List cases yang di-dispatch ke unitnya
-   Filter: Belum ditugaskan | Sudah ditugaskan | Semua
-   Card per case dengan tombol "Tugaskan Petugas"

```blade
@foreach($dispatches as $dispatch)
  <div class="case-card">
    <h3>Kasus: {{ $dispatch->case->short_id }}</h3>
    <p>Kategori: {{ $dispatch->case->category }}</p>
    <p>Lokasi: {{ $dispatch->case->locator_text }}</p>
    <p>Diterima: {{ $dispatch->dispatched_at->diffForHumans() }}</p>

    @if($dispatch->assigned_petugas_id)
      <div class="bg-green-100">
        ✓ Sudah ditugaskan ke: {{ $dispatch->assignedPetugas->name }}
      </div>
    @else
      <form method="POST" action="{{ route('pimpinan.assign-petugas', $dispatch) }}">
        <select name="petugas_id" required>
          <option>Pilih Petugas</option>
          @foreach($petugasInUnit as $petugas)
            <option value="{{ $petugas->id }}">{{ $petugas->name }}</option>
          @endforeach
        </select>
        <button>Tugaskan</button>
      </form>
    @endif
  </div>
@endforeach
```

---

## 📡 API Endpoints

### For Operator

```php
// Route: POST /cases/{case}/dispatch-multi
Route::post('/cases/{case}/dispatch-multi', [CaseController::class, 'dispatchMulti'])
    ->name('cases.dispatch-multi')
    ->middleware(['auth', 'role:OPERATOR,SUPERADMIN']);
```

**Request:**

```json
{
    "unit_ids": ["01K6...", "01K7...", "01K8..."],
    "notes": "Kebakaran besar, butuh 3 unit"
}
```

**Response:**

```json
{
    "success": true,
    "message": "Kasus berhasil di-dispatch ke 3 unit",
    "data": {
        "case_id": "01KCASE123",
        "dispatched_units": [
            {
                "unit_id": "01K6...",
                "unit_name": "Damkar Jakarta Pusat",
                "pimpinan": "Bapak Ahmad"
            },
            {
                "unit_id": "01K7...",
                "unit_name": "Polisi Jakarta Pusat",
                "pimpinan": "Bapak Budi"
            }
        ]
    }
}
```

---

### For Pimpinan

```php
// Route: GET /pimpinan/dashboard
Route::get('/pimpinan/dashboard', [PimpinanController::class, 'dashboard'])
    ->name('pimpinan.dashboard')
    ->middleware(['auth', 'role:PIMPINAN']);

// Route: POST /pimpinan/dispatches/{dispatch}/assign
Route::post('/pimpinan/dispatches/{dispatch}/assign', [PimpinanController::class, 'assignPetugas'])
    ->name('pimpinan.assign-petugas')
    ->middleware(['auth', 'role:PIMPINAN']);
```

---

## 🔧 Migration Files Needed

```bash
# 1. Add pimpinan_id to units
php artisan make:migration add_pimpinan_id_to_units_table

# 2. Create case_dispatches table
php artisan make:migration create_case_dispatches_table

# 3. Remove verified_at from cases
php artisan make:migration remove_verified_at_from_cases_table
```

---

## 🎯 Implementation Checklist

### Database

-   [ ] Migration: Add `pimpinan_id` to `units` table
-   [ ] Migration: Create `case_dispatches` table
-   [ ] Migration: Remove `verified_at` from `cases` table
-   [ ] Seeder: Create sample units with pimpinan
-   [ ] Seeder: Create sample petugas per unit

### Models

-   [ ] Create `CaseDispatch` model
-   [ ] Add relationships to `Unit` model (`pimpinan`, `dispatches`)
-   [ ] Add relationships to `User` model (`unit`, `dispatchesAsDispatcher`, `dispatchesAsPetugas`)
-   [ ] Add relationships to `Cases` model (`dispatches`)

### Controllers

-   [ ] Update `CaseController@dispatch` → rename to `dispatchMulti`
-   [ ] Remove `CaseController@verify` method
-   [ ] Create `PimpinanController` with `dashboard` and `assignPetugas`

### Views

-   [ ] Update `cases/show.blade.php` - Remove verify button, change dispatch form
-   [ ] Create `pimpinan/dashboard.blade.php` - Dashboard pimpinan
-   [ ] Create `pimpinan/assign.blade.php` - Form assign petugas
-   [ ] Update `cases/index.blade.php` - Remove verified status badge

### Routes

-   [ ] Remove route `cases.verify`
-   [ ] Update route `cases.dispatch` → `cases.dispatch-multi`
-   [ ] Add routes for pimpinan dashboard & assign

### Middleware

-   [ ] Create `CheckRole` middleware for role-based access
-   [ ] Apply middleware to pimpinan routes

### Notifications

-   [ ] Notification when operator dispatch → send to pimpinan
-   [ ] Notification when pimpinan assign → send to petugas

---

## 📊 Status Flow

```
NEW (Laporan masuk)
  ↓ [Operator dispatch ke units]
DISPATCHED (Dikirim ke pimpinan unit)
  ↓ [Pimpinan assign petugas]
ASSIGNED (Petugas ditugaskan) - optional status
  ↓ [Petugas accept & berangkat]
ON_THE_WAY (Petugas menuju lokasi)
  ↓ [Petugas tiba di lokasi]
ON_SCENE (Petugas di lokasi)
  ↓ [Petugas selesai menangani]
CLOSED (Kasus selesai)

  atau

CANCELLED (Kasus dibatalkan)
```

---

## 🚀 Next Steps

1. Review & approval spesifikasi ini
2. Buat migration files
3. Buat models & relationships
4. Update controllers
5. Update views
6. Testing multi-unit dispatch
7. Deploy to production

---

**Generated:** October 5, 2025  
**Approved By:** Backend Developer  
**Status:** ✅ READY FOR IMPLEMENTATION
