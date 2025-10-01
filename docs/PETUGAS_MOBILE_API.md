# SafePoint Mobile API untuk Petugas

## Overview

API ini didesain khusus untuk aplikasi mobile petugas lapangan (field officers) SafePoint. API menyediakan fungsionalitas lengkap untuk manajemen tugas, tracking lokasi, dan komunikasi dengan keluarga korban.

## Authentication

Semua endpoint petugas memerlukan autentikasi menggunakan Laravel Sanctum dengan Bearer token:

```
Authorization: Bearer {token}
```

## Base URL

```
http://127.0.0.1:8000/api
```

## Endpoints

### Authentication

-   `POST /login` - Login petugas
-   `GET /me` - Get current user info

### Profile Management

-   `GET /petugas/profile` - Get petugas profile
-   `PUT /petugas/profile` - Update petugas profile

### Duty Management

-   `GET /petugas/duty/status` - Get current duty status
-   `POST /petugas/duty/start` - Start duty shift
-   `POST /petugas/duty/end` - End duty shift

### Location Tracking

-   `POST /petugas/location/update` - Update current location
-   `GET /petugas/location/current` - Get current location

### Case Management

-   `GET /petugas/cases/assigned` - Get assigned cases
-   `GET /petugas/cases/{id}` - Get case detail
-   `POST /petugas/cases/{id}/status` - Update case status
-   `POST /petugas/cases/{id}/note` - Add case note

### Communication

-   `GET /petugas/cases/{id}/family-contacts` - Get family contacts
-   `POST /petugas/cases/{id}/contact-family` - Contact family via WhatsApp

### Dashboard

-   `GET /petugas/dashboard` - Get dashboard data

## Testing Results ✅

### Berhasil Ditest:

1. **Authentication** - Login dan token generation berfungsi
2. **Profile Endpoint** - GET `/api/petugas/profile` berhasil mengembalikan data lengkap petugas
3. **Dashboard Endpoint** - GET `/api/petugas/dashboard` menampilkan statistik dan data user
4. **Location Update** - POST `/api/petugas/location/update` berhasil update koordinat

### Features yang Diimplementasi:

-   ✅ Role-based access control (PETUGAS role required)
-   ✅ Database location tracking dengan timestamp
-   ✅ What3Words integration untuk conversion koordinat
-   ✅ WhatsApp integration untuk komunikasi keluarga
-   ✅ Distance calculation between petugas dan lokasi emergency
-   ✅ Case access control berdasarkan unit assignment
-   ✅ Comprehensive error handling dan logging

## Test User

```
Email: petugas.test@safepoint.id
Password: test123
Role: PETUGAS
Unit: Ambulans 01
```

## Postman Collection

File collection tersedia di: `/postman/SafePoint_Petugas_API.postman_collection.json`

## Development Notes

### Database Enhancements:

-   Added location tracking fields ke users table:
    -   `last_latitude`, `last_longitude`
    -   `last_location_update`
    -   `duty_status` (ON_DUTY/OFF_DUTY)
    -   `duty_started_at`, `last_activity_at`

### Security Features:

-   Unit-based access control untuk cases
-   Token-based authentication
-   Role middleware untuk PETUGAS access only
-   Input validation pada semua endpoints

### Integration Features:

-   What3Words API untuk easy location communication
-   WhatsApp integration dengan template messages
-   Distance calculation untuk proximity features
-   Event logging untuk audit trail

## Next Steps

1. Integration dengan mobile app Flutter/React Native
2. Real-time notifications menggunakan WebSocket/Pusher
3. Photo upload untuk case documentation
4. Offline capability untuk areas dengan poor connectivity
5. Advanced analytics dan reporting
