# SafePoint API Testing dengan Postman

## File yang Disediakan

1. **SafePoint_API_Fixed.postman_collection.json** - Collection utama untuk testing API (dengan route yang benar)
2. **SafePoint_Development.postman_environment.json** - Environment variables untuk development

## Route API yang Benar

Berdasarkan file `routes/api.php`, berikut adalah route yang tersedia:

### Authentication Routes

-   `POST /api/register` - Register user baru
-   `POST /api/login` - Login user
-   `POST /api/logout` - Logout user (auth required)
-   `GET /api/me` - Get user profile (auth required)

### Profile Routes

-   `GET /api/profile` - Get citizen profile (auth required)
-   `PUT /api/profile` - Update citizen profile (auth required)
-   `POST /api/profile/avatar` - Upload avatar (auth required)
-   `POST /api/profile/ktp` - Upload KTP (auth required)

### Emergency Case Routes

-   `POST /api/emergency` - Create emergency case (auth required)
-   `GET /api/emergency/{case}` - Get emergency case by ID (auth required)
-   `GET /api/my-cases` - Get my emergency cases (auth required)

## Cara Import ke Postman

### 1. Import Collection

1. Buka Postman
2. Klik tombol **Import** di kiri atas
3. Drag & drop file `SafePoint_API_Fixed.postman_collection.json` atau klik **Choose Files**
4. Klik **Import**

### 2. Import Environment

1. Di Postman, klik ikon **gear** (Settings) di kanan atas
2. Pilih **Manage Environments**
3. Klik **Import**
4. Pilih file `SafePoint_Development.postman_environment.json`
5. Klik **Import**
6. Pastikan environment **SafePoint Development** aktif (pilih dari dropdown di kanan atas)

## Cara Testing API

### 1. Setup Server

Pastikan Laravel server berjalan:

```bash
cd "d:\KMIPN\Safepoint"
php artisan serve
```

### 2. Testing Flow

#### A. Authentication Flow

1. **Register** - Daftar user baru

    - Method: POST
    - URL: `{{base_url}}/api/register`
    - Body: JSON dengan name, email, password

2. **Login** - Login untuk mendapatkan token

    - Method: POST
    - URL: `{{base_url}}/api/login`
    - Body: JSON dengan email, password
    - **Token otomatis tersimpan di environment setelah login sukses**

3. **Get My Profile** - Test token authentication
    - Method: GET
    - URL: `{{base_url}}/api/me`
    - Header: Authorization dengan Bearer token

#### B. Emergency Case Management Flow

1. **Create Emergency Case** - Buat case darurat

    - Method: POST
    - URL: `{{base_url}}/api/emergency`
    - Body: JSON dengan latitude, longitude, description, category
    - **Otomatis convert koordinat ke What3Words Indonesia**

2. **Get My Cases** - Lihat cases milik user

    - Method: GET
    - URL: `{{base_url}}/api/my-cases`

3. **Get Emergency Case by ID** - Detail case
    - Method: GET
    - URL: `{{base_url}}/api/emergency/{{case_id}}`
    - Set `case_id` di environment variables

#### C. Testing What3Words Indonesia

Folder **Testing What3Words** berisi 3 test case dengan koordinat berbeda:

1. **Jakarta** (Monas): `-6.2088, 106.8456`
2. **Bandung** (Gedung Sate): `-6.9147, 107.6098`
3. **Surabaya** (Tugu Pahlawan): `-7.2504, 112.7688`

Setiap test akan menunjukkan bagaimana koordinat dikonversi ke 3 kata bahasa Indonesia.

#### D. Citizen Profile Management

1. **Get Profile** - Ambil profil warga
    - URL: `{{base_url}}/api/profile`
2. **Update Profile** - Update profil dengan data keluarga dan WhatsApp
    - URL: `{{base_url}}/api/profile`

## Status Code yang Diharapkan

-   **200** - Success
-   **201** - Created (untuk register, create emergency)
-   **401** - Unauthorized (token tidak valid/expired)
-   **422** - Validation Error
-   **500** - Server Error

## Environment Variables

Variables yang tersedia di environment:

-   `base_url` - URL server Laravel (default: http://localhost:8000)
-   `token` - Authentication token (otomatis diset setelah login)
-   `user_id` - ID user yang login
-   `case_id` - ID case untuk testing detail/update

## Tips Testing

1. **Urutan Testing**: Selalu mulai dengan Register → Login → Test API lainnya
2. **Token Management**: Token otomatis tersimpan setelah login sukses
3. **What3Words Testing**: Gunakan folder "Testing What3Words" untuk melihat hasil konversi bahasa Indonesia
4. **Case ID**: Setelah create emergency case, copy ID dari response dan set di environment variable `case_id`

## Contoh Response What3Words

Ketika membuat emergency case dengan koordinat Jakarta (-6.2088, 106.8456), response akan menunjukkan:

```json
{
    "id": "01JBXXXXXXXXXXXXXXXXXXXXXXXX",
    "latitude": -6.2088,
    "longitude": 106.8456,
    "locator_text": "timbangan.pilihanmu.bening",
    "google_maps_url": "https://maps.google.com/?q=-6.2088,106.8456",
    "description": "Test lokasi Jakarta - Monas",
    "category": "TEST",
    "status": "PENDING"
}
```

Perhatikan field `locator_text` yang berisi 3 kata bahasa Indonesia hasil konversi What3Words.

## Troubleshooting

1. **401 Unauthorized**: Token expired atau tidak valid, lakukan login ulang
2. **404 Not Found**: Pastikan menggunakan route yang benar (lihat daftar route di atas)
3. **500 Server Error**: Pastikan Laravel server berjalan dan database terhubung
4. **What3Words Error**: Pastikan API key What3Words sudah diset di file `.env`
5. **CORS Error**: Jika testing dari browser, pastikan CORS sudah dikonfigurasi dengan benar

## Fitur Utama yang Bisa Ditest

-   ✅ Authentication dengan Laravel Sanctum
-   ✅ Emergency case management (Create, Read operations)
-   ✅ What3Words integration dengan bahasa Indonesia
-   ✅ Citizen profile management dengan family WhatsApp data
-   ✅ Emergency SOS dengan automatic location conversion
-   ✅ Mobile app ready API endpoints
