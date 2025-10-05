# Role-Based Access Control - Update Log

## Perubahan yang Dilakukan

### 1. Login Controller Update

**File:** `app/Http/Controllers/Auth/LoginController.php`

Menambahkan redirect berdasarkan role setelah login:

-   **PIMPINAN** → `/pimpinan/dashboard`
-   **OPERATOR/SUPERADMIN** → `/` (dashboard default)

```php
// Redirect based on user role
if ($user->role === 'PIMPINAN') {
    return redirect()->intended(route('pimpinan.dashboard'));
}

return redirect()->intended('/');
```

### 2. Middleware Baru

#### a. EnsurePimpinanRole

**File:** `app/Http/Middleware/EnsurePimpinanRole.php`

Middleware untuk memastikan hanya user dengan role `PIMPINAN` yang bisa akses route pimpinan.

#### b. EnsureOperatorRole

**File:** `app/Http/Middleware/EnsureOperatorRole.php`

Middleware untuk memastikan hanya user dengan role `SUPERADMIN` atau `OPERATOR` yang bisa akses route operator (dashboard, cases, units, users).

### 3. Registrasi Middleware

**File:** `bootstrap/app.php`

Mendaftarkan middleware baru:

```php
$middleware->alias([
    'web.role' => \App\Http\Middleware\EnsureWebRole::class,
    'role' => \App\Http\Middleware\RoleMiddleware::class,
    'pimpinan' => \App\Http\Middleware\EnsurePimpinanRole::class,
    'operator' => \App\Http\Middleware\EnsureOperatorRole::class,
]);
```

### 4. Route Separation

**File:** `routes/web.php`

Memisahkan route berdasarkan role:

#### Pimpinan Routes (prefix: `/pimpinan`)

-   `GET /pimpinan/dashboard` → Dashboard pimpinan
-   `GET /pimpinan/cases/{case}` → Detail case pimpinan
-   `POST /pimpinan/dispatches/{dispatch}/assign` → Assign petugas

**Access:** Hanya role `PIMPINAN`

#### Operator Routes (prefix: `/`)

-   `GET /` → Dashboard operator
-   `GET /cases` → Daftar cases
-   `GET /cases/{case}` → Detail case operator
-   `POST /cases/{case}/dispatch-multi` → Dispatch ke multiple units
-   `POST /cases/{case}/close` → Close case
-   `POST /cases/{case}/cancel` → Cancel case
-   Unit Management (CRUD)
-   User Management (CRUD)
-   Notifications

**Access:** Hanya role `SUPERADMIN` dan `OPERATOR`

## Behavior Setelah Perubahan

### Login Behavior

1. **Pimpinan** login → otomatis redirect ke `/pimpinan/dashboard`
2. **Operator/SUPERADMIN** login → otomatis redirect ke `/` (dashboard operator)

### Access Control

1. **Pimpinan** tidak bisa akses:

    - `/` (dashboard operator)
    - `/cases` (list cases operator)
    - `/cases/{id}` (detail case operator)
    - `/units` (unit management)
    - `/users` (user management)
    - Akan mendapat error **403 Forbidden**

2. **Operator/SUPERADMIN** tidak bisa akses:
    - `/pimpinan/dashboard`
    - `/pimpinan/cases/{id}`
    - Akan mendapat error **403 Forbidden**

### Navigation

-   Sidebar dan menu navigation perlu disesuaikan untuk menampilkan menu sesuai role user

## Testing

### Test Login as Pimpinan

```
Email: pimpinan.polisi1@safepoint.id
Password: password123
Expected: Redirect to /pimpinan/dashboard
```

### Test Login as Operator

```
Email: operator@safepoint.id
Password: password123
Expected: Redirect to / (dashboard operator)
```

### Test Access Control

1. Login as pimpinan
2. Try to access `/cases` → Should get 403 error
3. Try to access `/` → Should get 403 error

4. Login as operator
5. Try to access `/pimpinan/dashboard` → Should get 403 error
6. Try to access `/pimpinan/cases/1` → Should get 403 error

## Next Steps

1. **Update Sidebar/Menu** - Sesuaikan menu navigasi berdasarkan role user
2. **Test Thoroughly** - Test semua route untuk memastikan access control berjalan dengan baik
3. **Error Pages** - Buat custom error page untuk 403 Forbidden yang lebih user-friendly
4. **Logout Redirect** - Pastikan logout redirect ke `/login` untuk semua role

## Files Modified

1. `app/Http/Controllers/Auth/LoginController.php` - Role-based redirect
2. `app/Http/Middleware/EnsurePimpinanRole.php` - NEW
3. `app/Http/Middleware/EnsureOperatorRole.php` - NEW
4. `bootstrap/app.php` - Middleware registration
5. `routes/web.php` - Route separation

## Checklist

-   ✅ Login redirect berdasarkan role
-   ✅ Middleware untuk pimpinan
-   ✅ Middleware untuk operator
-   ✅ Route separation
-   ✅ Middleware registration
-   ⏳ Update sidebar/navigation menu
-   ⏳ Custom 403 error page
-   ⏳ Testing login dan access control
