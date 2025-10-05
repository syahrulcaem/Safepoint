# Troubleshooting: Fitur Pilih Unit dan Petugas

## ❌ Masalah yang Terjadi

Setelah memilih unit di halaman detail kasus (`/cases/{id}`), dropdown petugas tidak muncul atau kosong.

---

## 🔍 Diagnosa Masalah

### 1. Cek Apakah User Sudah Login

Endpoint `/api/units/{unit}/petugas` ada di **routes/web.php** yang membutuhkan autentikasi web session.

**Test:**

-   Buka browser
-   Akses `http://localhost:8000/login`
-   Login dengan user admin/responder
-   Baru bisa test fitur dispatch

### 2. Cek Apakah Ada Data Unit di Database

**Cara cek via SQLite:**

```bash
# Buka database
sqlite3 database/database.sqlite

# Query units
SELECT id, name, type, location FROM units;

# Keluar
.exit
```

**Atau via Laravel Tinker:**

```bash
php artisan tinker

# Cek units
App\Models\Unit::all();

# Keluar
exit
```

**Hasil yang diharapkan:**

```
> App\Models\Unit::all();
=> Illuminate\Database\Eloquent\Collection {#4567
     all: [
       App\Models\Unit {#4568
         id: "01K6...",
         name: "Damkar Jakarta Pusat",
         type: "FIRE_DEPT",
         ...
       },
     ],
   }
```

### 3. Cek Apakah Ada Data Petugas (User dengan role PETUGAS)

**Via SQLite:**

```bash
sqlite3 database/database.sqlite

# Query petugas
SELECT id, name, email, role, unit_id FROM users WHERE role = 'PETUGAS';

.exit
```

**Via Tinker:**

```bash
php artisan tinker

# Cek petugas
App\Models\User::where('role', 'PETUGAS')->get();

exit
```

**Hasil yang diharapkan:**

```
> App\Models\User::where('role', 'PETUGAS')->get();
=> Illuminate\Database\Eloquent\Collection {#4569
     all: [
       App\Models\User {#4570
         id: "01K6...",
         name: "Petugas A",
         email: "petugas1@example.com",
         role: "PETUGAS",
         unit_id: "01K6...",  // HARUS ADA!
       },
     ],
   }
```

**⚠️ PENTING:** Petugas HARUS memiliki `unit_id` yang terisi!

---

## 🔧 Cara Memperbaiki

### Jika Tidak Ada Data Unit

**Buat Unit via Tinker:**

```bash
php artisan tinker

App\Models\Unit::create([
    'name' => 'Damkar Jakarta Pusat',
    'type' => 'FIRE_DEPT',
    'location' => 'Jl. Merdeka No. 1',
    'phone' => '021-1234567',
    'is_active' => true
]);

App\Models\Unit::create([
    'name' => 'Polisi Jakarta Pusat',
    'type' => 'POLICE',
    'location' => 'Jl. Kepolisian No. 2',
    'phone' => '021-7654321',
    'is_active' => true
]);

exit
```

**Atau via UI Web:**

-   Login sebagai Super Admin
-   Akses menu **Units**
-   Klik **Tambah Unit Baru**
-   Isi form dan simpan

### Jika Tidak Ada Data Petugas

**Buat User Petugas via Tinker:**

```bash
php artisan tinker

# Dapatkan ID unit dulu
$unit = App\Models\Unit::first();
echo $unit->id;

# Buat petugas
App\Models\User::create([
    'name' => 'Petugas Damkar A',
    'email' => 'petugas1@damkar.com',
    'password' => bcrypt('password123'),
    'role' => 'PETUGAS',
    'unit_id' => $unit->id,  // WAJIB DIISI!
    'phone' => '081234567890',
    'is_active' => true,
]);

App\Models\User::create([
    'name' => 'Petugas Damkar B',
    'email' => 'petugas2@damkar.com',
    'password' => bcrypt('password123'),
    'role' => 'PETUGAS',
    'unit_id' => $unit->id,
    'phone' => '081234567891',
    'is_active' => true,
]);

exit
```

**Atau via Seeder (Recommended):**

Buat file `database/seeders/UnitAndPetugasSeeder.php`:

```php
<?php

namespace Database\Seeders;

use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UnitAndPetugasSeeder extends Seeder
{
    public function run()
    {
        // Create Units
        $damkar = Unit::create([
            'id' => Str::ulid(),
            'name' => 'Damkar Jakarta Pusat',
            'type' => 'FIRE_DEPT',
            'location' => 'Jl. Merdeka No. 1',
            'phone' => '021-1234567',
            'is_active' => true
        ]);

        $police = Unit::create([
            'id' => Str::ulid(),
            'name' => 'Polisi Jakarta Pusat',
            'type' => 'POLICE',
            'location' => 'Jl. Kepolisian No. 2',
            'phone' => '021-7654321',
            'is_active' => true
        ]);

        $ambulance = Unit::create([
            'id' => Str::ulid(),
            'name' => 'Ambulans RS Pusat',
            'type' => 'AMBULANCE',
            'location' => 'Jl. Kesehatan No. 3',
            'phone' => '021-9876543',
            'is_active' => true
        ]);

        // Create Petugas for Damkar
        User::create([
            'id' => Str::ulid(),
            'name' => 'Petugas Damkar A',
            'email' => 'damkar.a@example.com',
            'password' => bcrypt('password'),
            'role' => 'PETUGAS',
            'unit_id' => $damkar->id,
            'phone' => '081234567890',
            'is_active' => true,
        ]);

        User::create([
            'id' => Str::ulid(),
            'name' => 'Petugas Damkar B',
            'email' => 'damkar.b@example.com',
            'password' => bcrypt('password'),
            'role' => 'PETUGAS',
            'unit_id' => $damkar->id,
            'phone' => '081234567891',
            'is_active' => true,
        ]);

        // Create Petugas for Police
        User::create([
            'id' => Str::ulid(),
            'name' => 'Polisi A',
            'email' => 'police.a@example.com',
            'password' => bcrypt('password'),
            'role' => 'PETUGAS',
            'unit_id' => $police->id,
            'phone' => '081234567892',
            'is_active' => true,
        ]);

        // Create Petugas for Ambulance
        User::create([
            'id' => Str::ulid(),
            'name' => 'Paramedis A',
            'email' => 'ambulance.a@example.com',
            'password' => bcrypt('password'),
            'role' => 'PETUGAS',
            'unit_id' => $ambulance->id,
            'phone' => '081234567893',
            'is_active' => true,
        ]);

        $this->command->info('✅ Units and Petugas seeded successfully!');
    }
}
```

**Jalankan seeder:**

```bash
php artisan db:seed --class=UnitAndPetugasSeeder
```

---

## 🧪 Testing Setelah Perbaikan

### 1. Test via Browser Console

Buka halaman detail kasus dan buka **Developer Tools** (F12), lalu:

```javascript
// Test fetch petugas untuk unit ID tertentu
fetch("/api/units/01K6S9ABCD1234567890/petugas", {
    headers: {
        Accept: "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
            .content,
    },
})
    .then((r) => r.json())
    .then((data) => console.log(data));
```

**Hasil yang diharapkan:**

```json
{
    "success": true,
    "data": [
        {
            "id": "01K6...",
            "name": "Petugas Damkar A",
            "is_available": true
        },
        {
            "id": "01K6...",
            "name": "Petugas Damkar B",
            "is_available": true
        }
    ]
}
```

### 2. Test via UI

1. Login sebagai admin
2. Buka halaman **Daftar Kasus** (`/cases`)
3. Klik salah satu kasus dengan status **NEW** atau **VERIFIED**
4. Di sidebar kanan, klik dropdown **"Pilih Unit"**
5. Pilih salah satu unit (misal: Damkar Jakarta Pusat)
6. ✅ Dropdown **"Petugas"** harus muncul dengan daftar petugas

---

## 📊 Status Endpoint

| Endpoint                    | Method | Auth        | Status     |
| --------------------------- | ------ | ----------- | ---------- |
| `/api/units/{unit}/petugas` | GET    | Web Session | ✅ Working |

**Controller:** `CaseController@getPetugasByUnit`  
**Location:** `app/Http/Controllers/CaseController.php` line 264

---

## 🐛 Debug Mode

Jika masih error, aktifkan log debugging:

**Tambahkan di CaseController:**

```php
public function getPetugasByUnit(Request $request, Unit $unit)
{
    \Log::debug('getPetugasByUnit called', [
        'unit_id' => $unit->id,
        'unit_name' => $unit->name,
        'request_headers' => $request->headers->all(),
    ]);

    $petugas = User::where('role', 'PETUGAS')
        ->where('unit_id', $unit->id)
        ->get();

    \Log::debug('Petugas found', [
        'count' => $petugas->count(),
        'data' => $petugas->toArray()
    ]);

    return response()->json([
        'success' => true,
        'data' => $petugas
    ]);
}
```

**Cek log:**

```bash
tail -f storage/logs/laravel.log
```

---

## ✅ Checklist Perbaikan

-   [ ] User sudah login ke aplikasi web
-   [ ] Ada minimal 1 unit di tabel `units`
-   [ ] Ada minimal 1 petugas (user dengan role='PETUGAS') di tabel `users`
-   [ ] Petugas memiliki `unit_id` yang terisi
-   [ ] Route `/api/units/{unit}/petugas` bisa diakses (return 200, bukan 404/401)
-   [ ] Response JSON berisi array `data` dengan petugas

---

## 📝 Catatan Tambahan

### Struktur Tabel Users yang Benar

```sql
CREATE TABLE users (
    id TEXT PRIMARY KEY,
    name TEXT NOT NULL,
    email TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL,
    role TEXT NOT NULL,  -- 'ADMIN', 'RESPONDER', 'PETUGAS', 'CITIZEN'
    unit_id TEXT,        -- Foreign key ke units.id (PENTING!)
    phone TEXT,
    is_active BOOLEAN DEFAULT 1,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (unit_id) REFERENCES units(id)
);
```

### Relasi Model

**User.php:**

```php
public function unit()
{
    return $this->belongsTo(Unit::class, 'unit_id');
}
```

**Unit.php:**

```php
public function petugas()
{
    return $this->hasMany(User::class, 'unit_id')
        ->where('role', 'PETUGAS');
}
```

---

**Update:** October 5, 2025  
**Status:** Guide created for backend developer
