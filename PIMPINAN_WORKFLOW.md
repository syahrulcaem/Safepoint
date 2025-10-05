# Workflow Pimpinan Unit - Multi-Unit Dispatch System

## 📋 Konsep Sistem

### Struktur Organisasi

```
SUPERADMIN (1)
    ↓
OPERATOR (1-n)
    ↓ dispatch ke multiple units
[UNIT 1] → PIMPINAN 1 → PETUGAS 1.1, 1.2, 1.3
[UNIT 2] → PIMPINAN 2 → PETUGAS 2.1, 2.2
[UNIT 3] → PIMPINAN 3 → PETUGAS 3.1, 3.2, 3.3
```

### Prinsip Utama

1. **Setiap unit memiliki 1 pimpinan**
2. **Pimpinan hanya melihat dispatch untuk unit mereka sendiri**
3. **Operator bisa dispatch ke multiple units sekaligus**
4. **Setiap unit dispatch = 1 record terpisah di `case_dispatches`**

## 🔄 Alur Workflow

### 1. OPERATOR Dispatch Case

```
Operator login → Lihat case NEW → Pilih multiple units (checkbox)
  ↓
Operator submit form → Sistem create multiple CaseDispatch records
  ↓
Record 1: case_id=X, unit_id=1, dispatcher_id=OP, assigned_petugas_id=NULL
Record 2: case_id=X, unit_id=2, dispatcher_id=OP, assigned_petugas_id=NULL
Record 3: case_id=X, unit_id=3, dispatcher_id=OP, assigned_petugas_id=NULL
```

### 2. PIMPINAN Assign Petugas

```
Pimpinan Unit 1 login → Dashboard shows:
  ✅ Pending: Dispatch untuk unit_id=1 yang assigned_petugas_id=NULL
  ✅ Assigned: Dispatch untuk unit_id=1 yang sudah punya petugas
  ❌ TIDAK melihat: Dispatch untuk unit_id=2 atau unit_id=3

Pimpinan klik "Tugaskan Petugas" → Modal muncul
  ↓
Pilih petugas dari unit mereka → Submit
  ↓
Update CaseDispatch: assigned_petugas_id = petugas_id, assigned_at = NOW()
```

### 3. PETUGAS Handle Case

```
Petugas login → Lihat cases yang assigned ke mereka
  ↓
Update status: DISPATCHED → ON_THE_WAY → ON_SCENE → CLOSED
```

## 📊 Database Schema

### Table: `units`

```sql
- id
- name (varchar)
- type (enum: POLISI, DAMKAR, AMBULANCE)
- pimpinan_id (foreign key → users.id) ← KUNCI: Link ke pimpinan
- is_active (boolean)
```

### Table: `users`

```sql
- id
- name
- email
- role (enum: SUPERADMIN, OPERATOR, PIMPINAN, PETUGAS, CITIZEN)
- unit_id (foreign key → units.id)
- duty_status (enum: ON_DUTY, OFF_DUTY)
```

### Table: `case_dispatches`

```sql
- id
- case_id (foreign key → cases.id)
- unit_id (foreign key → units.id) ← KUNCI: Filter per unit
- assigned_petugas_id (nullable, foreign key → users.id)
- dispatcher_id (foreign key → users.id)
- notes
- dispatched_at
- assigned_at (nullable)
```

## 🔐 Access Control

### Pimpinan Dashboard Query

```php
// ✅ CORRECT: Hanya dispatch untuk unit pimpinan
$unit = $user->unitAsPimpinan; // Get unit where pimpinan_id = user.id

$pendingDispatches = CaseDispatch::where('unit_id', $unit->id)
    ->whereNull('assigned_petugas_id')
    ->get();

// ❌ WRONG: Melihat semua dispatch
$pendingDispatches = CaseDispatch::whereNull('assigned_petugas_id')->get();
```

## 📝 Sample Data

### Unit Polisi Sektor 1

-   **Pimpinan**: Kompol Budi Santoso (pimpinan.polisi1@safepoint.id)
-   **Petugas**:
    -   Bripka Ahmad (ahmad.polisi1@safepoint.id)
    -   Bripka Deni (deni.polisi1@safepoint.id)

### Pemadam Kebakaran Pusat

-   **Pimpinan**: Kepala Damkar Hadi Kusuma (pimpinan.damkar1@safepoint.id)
-   **Petugas**:
    -   Fireman Agus (agus.damkar1@safepoint.id)
    -   Fireman Bambang (bambang.damkar1@safepoint.id)
    -   Fireman Candra (candra.damkar1@safepoint.id)

### Ambulance Unit 1

-   **Pimpinan**: Dr. Siti Nurhaliza (pimpinan.ambulance1@safepoint.id)
-   **Petugas**:
    -   Paramedis Fikri (fikri.ambulance1@safepoint.id)
    -   Paramedis Gita (gita.ambulance1@safepoint.id)

**Semua password**: `password123`

## 🧪 Testing Scenarios

### Scenario 1: Single Unit Dispatch

```
1. Login as operator@safepoint.id
2. Create/view case dengan status NEW
3. Pilih checkbox: "Unit Polisi Sektor 1" saja
4. Submit → 1 CaseDispatch record created

5. Login as pimpinan.polisi1@safepoint.id
6. Dashboard shows: 1 pending dispatch
7. Assign ke Bripka Ahmad
8. Dashboard shows: 0 pending, 1 assigned

9. Login as pimpinan.damkar1@safepoint.id
10. Dashboard shows: 0 pending, 0 assigned (tidak ada dispatch untuk unit ini)
```

### Scenario 2: Multi-Unit Dispatch

```
1. Login as operator@safepoint.id
2. View case dengan status NEW
3. Pilih checkbox:
   - ✅ Unit Polisi Sektor 1
   - ✅ Pemadam Kebakaran Pusat
   - ✅ Ambulance Unit 1
4. Submit → 3 CaseDispatch records created

5. Login as pimpinan.polisi1@safepoint.id
6. Dashboard shows: 1 pending dispatch (hanya untuk unit polisi 1)

7. Login as pimpinan.damkar1@safepoint.id
8. Dashboard shows: 1 pending dispatch (hanya untuk unit damkar pusat)

9. Login as pimpinan.ambulance1@safepoint.id
10. Dashboard shows: 1 pending dispatch (hanya untuk unit ambulance 1)
```

## 🔧 Setup Instructions

### Fresh Database Setup

```bash
# Drop all tables and re-migrate
php artisan migrate:fresh

# Run seeders with new structure
php artisan db:seed

# Verify data
php artisan tinker
>>> Unit::with('pimpinan')->get()
>>> User::where('role', 'PIMPINAN')->get()
```

### Quick Test Query

```php
// Check unit-pimpinan relationship
Unit::all()->map(fn($u) => [
    'unit' => $u->name,
    'pimpinan' => $u->pimpinan->name ?? 'NONE'
]);

// Check pimpinan-unit relationship
User::where('role', 'PIMPINAN')->get()->map(fn($u) => [
    'pimpinan' => $u->name,
    'unit' => $u->unitAsPimpinan->name ?? 'NONE'
]);
```

## ✅ Verification Checklist

-   [ ] Setiap unit punya pimpinan_id yang valid
-   [ ] Pimpinan hanya lihat dispatch untuk unit mereka
-   [ ] Operator bisa pilih multiple units
-   [ ] Multi-unit dispatch create multiple records
-   [ ] Petugas hanya dari unit yang sama muncul di dropdown pimpinan
-   [ ] Dashboard pimpinan tidak menampilkan dispatch dari unit lain

## 🚨 Common Issues

### Issue: Pimpinan melihat semua dispatch

**Cause**: Query tidak filter berdasarkan `unit_id`
**Fix**: Tambahkan `->where('unit_id', $unit->id)`

### Issue: Dropdown petugas kosong

**Cause**: Tidak ada petugas ON_DUTY di unit tersebut
**Fix**: Update duty_status petugas atau create petugas baru untuk unit

### Issue: Pimpinan tidak bisa login

**Cause**: User tidak memiliki unitAsPimpinan
**Fix**: Set pimpinan_id di tabel units yang sesuai dengan user.id pimpinan
