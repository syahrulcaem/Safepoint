# 📱 SafePoint API Documentation

**Base URL:** `http://your-domain.com/api`  
**Authentication:** Bearer Token (Laravel Sanctum)

---

## 📋 **Table of Contents**

1. [Authentication APIs](#authentication-apis)
2. [Profile Management APIs](#profile-management-apis)
3. [Emergency Case APIs](#emergency-case-apis)
4. [Petugas/Officer APIs](#petugasofficer-apis)
5. [Data Models](#data-models)
6. [Response Format](#response-format)
7. [Error Codes](#error-codes)

---

## 🔐 **Authentication APIs**

### 1. **Register User**

**Endpoint:** `POST /register`  
**Auth:** None  
**Description:** Register new citizen user

#### Request Body:

```json
{
    "name": "string (required, max:100)",
    "email": "string (nullable, email, unique)",
    "phone": "string (nullable, max:32, unique)",
    "password": "string (required, min:6)",
    "nik": "string (nullable, max:32, unique)",
    "whatsapp_keluarga": "string (nullable, max:20)",
    "hubungan": "enum (nullable): KEPALA_KELUARGA|ISTRI|SUAMI|ANAK|AYAH|IBU|KAKEK|NENEK|CUCU|SAUDARA|LAINNYA"
}
```

**Note:** Either `email` or `phone` must be provided.

#### Success Response (201):

```json
{
    "success": true,
    "message": "Registrasi berhasil",
    "data": {
        "user": {
            "id": "uuid",
            "name": "John Doe",
            "email": "john@example.com",
            "phone": "+6281234567890",
            "role": "WARGA",
            "email_verified_at": "2025-10-02T10:00:00.000000Z",
            "created_at": "2025-10-02T10:00:00.000000Z",
            "updated_at": "2025-10-02T10:00:00.000000Z",
            "citizen_profile": {
                "id": 1,
                "user_id": "uuid",
                "nik": "1234567890123456",
                "whatsapp_keluarga": "+6281234567891",
                "hubungan": "KEPALA_KELUARGA",
                "birth_date": null,
                "blood_type": null,
                "chronic_conditions": null,
                "ktp_image_url": null,
                "avatar_url": null,
                "created_at": "2025-10-02T10:00:00.000000Z",
                "updated_at": "2025-10-02T10:00:00.000000Z"
            }
        },
        "token": "sanctum_token_here"
    }
}
```

#### Error Response (422):

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": ["The email field is required when phone is not present."],
        "password": ["The password must be at least 6 characters."]
    }
}
```

---

### 2. **Login User**

**Endpoint:** `POST /login`  
**Auth:** None  
**Description:** Login existing user

#### Request Body:

```json
{
    "email": "string (nullable, email)",
    "phone": "string (nullable)",
    "password": "string (required)"
}
```

**Note:** Either `email` or `phone` must be provided.

#### Success Response (200):

```json
{
    "success": true,
    "message": "Login berhasil",
    "data": {
        "user": {
            "id": "uuid",
            "name": "John Doe",
            "email": "john@example.com",
            "phone": "+6281234567890",
            "role": "WARGA",
            "citizen_profile": {
                "id": 1,
                "nik": "1234567890123456",
                "whatsapp_keluarga": "+6281234567891",
                "hubungan": "KEPALA_KELUARGA"
            }
        },
        "token": "sanctum_token_here"
    }
}
```

#### Error Response (422):

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": ["Email/telepon atau password tidak valid."]
    }
}
```

---

### 3. **Logout User**

**Endpoint:** `POST /logout`  
**Auth:** Bearer Token  
**Description:** Logout current user

#### Request Body:

```json
{}
```

#### Success Response (200):

```json
{
    "success": true,
    "message": "Logout berhasil"
}
```

---

### 4. **Get Current User**

**Endpoint:** `GET /me`  
**Auth:** Bearer Token  
**Description:** Get current authenticated user data

#### Request Body:

```json
{}
```

#### Success Response (200):

```json
{
    "success": true,
    "data": {
        "user": {
            "id": "uuid",
            "name": "John Doe",
            "email": "john@example.com",
            "phone": "+6281234567890",
            "role": "WARGA",
            "citizen_profile": {
                "id": 1,
                "nik": "1234567890123456",
                "whatsapp_keluarga": "+6281234567891",
                "hubungan": "KEPALA_KELUARGA",
                "birth_date": "1990-01-01",
                "blood_type": "A",
                "chronic_conditions": "Diabetes",
                "ktp_image_url": "/storage/ktp-images/abc123.jpg",
                "avatar_url": "/storage/avatars/xyz789.jpg"
            }
        }
    }
}
```

---

## 👤 **Profile Management APIs**

### 5. **Get Profile**

**Endpoint:** `GET /profile`  
**Auth:** Bearer Token  
**Description:** Get user profile data

#### Success Response (200):

```json
{
    "success": true,
    "data": {
        "user": {
            "id": "uuid",
            "name": "John Doe",
            "email": "john@example.com",
            "phone": "+6281234567890",
            "role": "WARGA",
            "citizen_profile": {
                "id": 1,
                "nik": "1234567890123456",
                "whatsapp_keluarga": "+6281234567891",
                "hubungan": "KEPALA_KELUARGA",
                "birth_date": "1990-01-01",
                "blood_type": "A",
                "chronic_conditions": "Diabetes",
                "ktp_image_url": "/storage/ktp-images/abc123.jpg",
                "avatar_url": "/storage/avatars/xyz789.jpg"
            }
        }
    }
}
```

---

### 6. **Update Profile**

**Endpoint:** `PUT /profile`  
**Auth:** Bearer Token  
**Description:** Update user profile data

#### Request Body:

```json
{
    "name": "string (nullable, max:100)",
    "email": "string (nullable, email, unique)",
    "phone": "string (nullable, max:32, unique)",
    "nik": "string (nullable, max:32, unique)",
    "whatsapp_keluarga": "string (nullable, max:20)",
    "hubungan": "enum (nullable): KEPALA_KELUARGA|ISTRI|SUAMI|ANAK|AYAH|IBU|KAKEK|NENEK|CUCU|SAUDARA|LAINNYA",
    "birth_date": "date (nullable, before:today)",
    "blood_type": "enum (nullable): A|B|AB|O|UNKNOWN",
    "chronic_conditions": "string (nullable)"
}
```

#### Success Response (200):

```json
{
    "success": true,
    "message": "Profil berhasil diperbarui",
    "data": {
        "user": {
            "id": "uuid",
            "name": "John Doe Updated",
            "email": "johnupdated@example.com",
            "phone": "+6281234567890",
            "role": "WARGA",
            "citizen_profile": {
                "nik": "1234567890123456",
                "whatsapp_keluarga": "+6281234567891",
                "hubungan": "KEPALA_KELUARGA",
                "birth_date": "1990-01-01",
                "blood_type": "A",
                "chronic_conditions": "Diabetes"
            }
        }
    }
}
```

---

### 7. **Upload Avatar**

**Endpoint:** `POST /profile/avatar`  
**Auth:** Bearer Token  
**Description:** Upload user profile avatar

#### Request Body (multipart/form-data):

```
avatar: file (required, image, mimes:jpeg,png,jpg, max:5MB)
```

#### Success Response (200):

```json
{
    "success": true,
    "message": "Avatar berhasil diunggah",
    "data": {
        "avatar_url": "/storage/avatars/xyz789.jpg"
    }
}
```

---

### 8. **Upload KTP**

**Endpoint:** `POST /profile/ktp`  
**Auth:** Bearer Token  
**Description:** Upload KTP image

#### Request Body (multipart/form-data):

```
ktp_image: file (required, image, mimes:jpeg,png,jpg, max:5MB)
```

#### Success Response (200):

```json
{
    "success": true,
    "message": "Foto KTP berhasil diunggah",
    "data": {
        "ktp_image_url": "/storage/ktp-images/abc123.jpg"
    }
}
```

---

## 🚨 **Emergency Case APIs**

### 9. **Create Emergency Case**

**Endpoint:** `POST /emergency`  
**Auth:** Bearer Token  
**Description:** Create new emergency case

#### Request Body:

```json
{
    "latitude": "number (required, between:-90,90)",
    "longitude": "number (required, between:-180,180)",
    "accuracy": "integer (nullable, min:0)",
    "category": "string (nullable, max:20)",
    "description": "string (nullable, max:500)",
    "phone": "string (nullable, max:32)"
}
```

**Available Categories:**

-   `UMUM` (default)
-   `BENCANA_ALAM`
-   `KECELAKAAN`
-   `KEBOCORAN_GAS`
-   `POHON_TUMBANG`
-   `BANJIR`

#### Success Response (201):

```json
{
    "success": true,
    "message": "Panggilan darurat berhasil dibuat",
    "data": {
        "case": {
            "id": "01HF5MGCJ123EXAMPLE",
            "reporter_user_id": "uuid",
            "viewer_token_hash": "hashed_token",
            "phone": "+6281234567890",
            "lat": "-6.2088",
            "lon": "106.8456",
            "accuracy": 5,
            "locator_text": "///word.word.word",
            "locator_provider": "w3w",
            "category": "KECELAKAAN",
            "description": "Kecelakaan sepeda motor di jalan raya",
            "status": "NEW",
            "assigned_unit_id": null,
            "assigned_petugas_id": null,
            "contacts_snapshot": [
                {
                    "name": "John Doe",
                    "phone": "+6281234567890",
                    "relation": "Pelapor",
                    "is_primary": true
                }
            ],
            "verified_at": null,
            "dispatched_at": null,
            "on_scene_at": null,
            "closed_at": null,
            "created_at": "2025-10-02T10:00:00.000000Z",
            "updated_at": "2025-10-02T10:00:00.000000Z",
            "reporter_user": {
                "id": "uuid",
                "name": "John Doe",
                "phone": "+6281234567890"
            },
            "assigned_unit": null
        },
        "what3words": "///word.word.word",
        "google_maps_url": "https://maps.google.com/maps?q=-6.2088,106.8456"
    }
}
```

---

### 10. **Get Case Detail**

**Endpoint:** `GET /emergency/{case_id}`  
**Auth:** Bearer Token  
**Description:** Get emergency case detail (only accessible by case reporter)

#### Success Response (200):

```json
{
    "success": true,
    "data": {
        "case": {
            "id": "01HF5MGCJ123EXAMPLE",
            "status": "DISPATCHED",
            "category": "KECELAKAAN",
            "description": "Kecelakaan sepeda motor di jalan raya",
            "lat": "-6.2088",
            "lon": "106.8456",
            "locator_text": "///word.word.word",
            "verified_at": "2025-10-02T10:05:00.000000Z",
            "dispatched_at": "2025-10-02T10:10:00.000000Z",
            "reporter_user": {
                "id": "uuid",
                "name": "John Doe"
            },
            "assigned_unit": {
                "id": "uuid",
                "name": "Unit Rescue Jakarta Pusat",
                "type": "AMBULANCE"
            },
            "case_events": [
                {
                    "id": 1,
                    "action": "DISPATCHED",
                    "metadata": {
                        "unit_name": "Unit Rescue Jakarta Pusat",
                        "petugas_name": "Officer Smith"
                    },
                    "created_at": "2025-10-02T10:10:00.000000Z",
                    "actor": {
                        "id": "uuid",
                        "name": "Admin User"
                    }
                }
            ],
            "dispatches": [
                {
                    "id": 1,
                    "unit": {
                        "id": "uuid",
                        "name": "Unit Rescue Jakarta Pusat"
                    },
                    "assigned_by": {
                        "id": "uuid",
                        "name": "Admin User"
                    },
                    "created_at": "2025-10-02T10:10:00.000000Z"
                }
            ]
        }
    }
}
```

#### Error Response (403):

```json
{
    "success": false,
    "message": "Akses ditolak"
}
```

---

### 11. **Get My Cases**

**Endpoint:** `GET /my-cases`  
**Auth:** Bearer Token  
**Description:** Get all cases reported by current user

#### Success Response (200):

```json
{
    "success": true,
    "data": {
        "cases": {
            "current_page": 1,
            "data": [
                {
                    "id": "01HF5MGCJ123EXAMPLE",
                    "status": "RESOLVED",
                    "category": "KECELAKAAN",
                    "lat": "-6.2088",
                    "lon": "106.8456",
                    "locator_text": "///word.word.word",
                    "created_at": "2025-10-02T10:00:00.000000Z",
                    "assigned_unit": {
                        "id": "uuid",
                        "name": "Unit Rescue Jakarta Pusat",
                        "type": "AMBULANCE"
                    }
                }
            ],
            "first_page_url": "http://domain.com/api/my-cases?page=1",
            "from": 1,
            "last_page": 1,
            "last_page_url": "http://domain.com/api/my-cases?page=1",
            "next_page_url": null,
            "path": "http://domain.com/api/my-cases",
            "per_page": 20,
            "prev_page_url": null,
            "to": 1,
            "total": 1
        }
    }
}
```

---

## 👮‍♂️ **Petugas/Officer APIs**

### 12. **Get Petugas Profile**

**Endpoint:** `GET /petugas/profile`  
**Auth:** Bearer Token (Role: PETUGAS)  
**Description:** Get petugas profile data

#### Success Response (200):

```json
{
    "success": true,
    "data": {
        "id": "uuid",
        "name": "Officer Smith",
        "email": "officer@example.com",
        "phone": "+6281234567890",
        "role": "PETUGAS",
        "unit": {
            "id": "uuid",
            "name": "Unit Rescue Jakarta Pusat",
            "type": "AMBULANCE",
            "location": "Jakarta Pusat"
        },
        "duty_status": "ON_DUTY",
        "duty_started_at": "2025-10-02T08:00:00.000000Z",
        "last_location": {
            "latitude": "-6.2088",
            "longitude": "106.8456",
            "updated_at": "2025-10-02T10:00:00.000000Z"
        },
        "last_activity_at": "2025-10-02T10:00:00.000000Z"
    }
}
```

---

### 13. **Update Petugas Profile**

**Endpoint:** `PUT /petugas/profile`  
**Auth:** Bearer Token (Role: PETUGAS)  
**Description:** Update petugas profile

#### Request Body:

```json
{
    "name": "string (nullable, max:255)",
    "phone": "string (nullable, max:20)"
}
```

#### Success Response (200):

```json
{
    "success": true,
    "message": "Profil berhasil diperbarui",
    "data": {
        "id": "uuid",
        "name": "Officer Smith Updated",
        "email": "officer@example.com",
        "phone": "+6281234567891",
        "role": "PETUGAS"
    }
}
```

---

### 14. **Start Duty**

**Endpoint:** `POST /petugas/duty/start`  
**Auth:** Bearer Token (Role: PETUGAS)  
**Description:** Start duty shift

#### Request Body:

```json
{}
```

#### Success Response (200):

```json
{
    "success": true,
    "message": "Berhasil memulai tugas",
    "data": {
        "duty_status": "ON_DUTY",
        "duty_started_at": "2025-10-02T08:00:00.000000Z"
    }
}
```

#### Error Response (422):

```json
{
    "success": false,
    "message": "Anda sudah dalam status bertugas"
}
```

---

### 15. **End Duty**

**Endpoint:** `POST /petugas/duty/end`  
**Auth:** Bearer Token (Role: PETUGAS)  
**Description:** End duty shift

#### Success Response (200):

```json
{
    "success": true,
    "message": "Berhasil mengakhiri tugas",
    "data": {
        "duty_status": "OFF_DUTY",
        "duty_ended_at": "2025-10-02T16:00:00.000000Z"
    }
}
```

---

### 16. **Get Duty Status**

**Endpoint:** `GET /petugas/duty/status`  
**Auth:** Bearer Token (Role: PETUGAS)  
**Description:** Get current duty status

#### Success Response (200):

```json
{
    "success": true,
    "data": {
        "duty_status": "ON_DUTY",
        "duty_started_at": "2025-10-02T08:00:00.000000Z",
        "duty_duration": "02:00:00",
        "is_on_duty": true
    }
}
```

---

### 17. **Update Location**

**Endpoint:** `POST /petugas/location/update`  
**Auth:** Bearer Token (Role: PETUGAS)  
**Description:** Update petugas current location

#### Request Body:

```json
{
    "latitude": "number (required, between:-90,90)",
    "longitude": "number (required, between:-180,180)",
    "accuracy": "number (nullable, min:0)"
}
```

#### Success Response (200):

```json
{
    "success": true,
    "message": "Lokasi berhasil diperbarui",
    "data": {
        "latitude": "-6.2088",
        "longitude": "106.8456",
        "accuracy": 5,
        "updated_at": "2025-10-02T10:00:00.000000Z"
    }
}
```

---

### 18. **Get Current Location**

**Endpoint:** `GET /petugas/location/current`  
**Auth:** Bearer Token (Role: PETUGAS)  
**Description:** Get petugas current location

#### Success Response (200):

```json
{
    "success": true,
    "data": {
        "latitude": "-6.2088",
        "longitude": "106.8456",
        "updated_at": "2025-10-02T10:00:00.000000Z",
        "accuracy": 5,
        "what3words": "///word.word.word"
    }
}
```

---

### 19. **Get Assigned Cases**

**Endpoint:** `GET /petugas/cases/assigned`  
**Auth:** Bearer Token (Role: PETUGAS)  
**Description:** Get cases assigned to petugas

#### Query Parameters:

-   `status`: Filter by case status (optional)
-   `page`: Page number for pagination

#### Success Response (200):

```json
{
    "success": true,
    "data": {
        "cases": {
            "current_page": 1,
            "data": [
                {
                    "id": "01HF5MGCJ123EXAMPLE",
                    "status": "DISPATCHED",
                    "category": "KECELAKAAN",
                    "description": "Kecelakaan sepeda motor",
                    "lat": "-6.2088",
                    "lon": "106.8456",
                    "locator_text": "///word.word.word",
                    "distance": 2.5,
                    "eta_minutes": 8,
                    "priority": "HIGH",
                    "reporter_user": {
                        "name": "John Doe",
                        "phone": "+6281234567890"
                    },
                    "dispatched_at": "2025-10-02T10:10:00.000000Z",
                    "created_at": "2025-10-02T10:00:00.000000Z"
                }
            ],
            "total": 1,
            "per_page": 20
        }
    }
}
```

---

### 20. **Get Case Detail (Petugas)**

**Endpoint:** `GET /petugas/cases/{case_id}`  
**Auth:** Bearer Token (Role: PETUGAS)  
**Description:** Get detailed case information for petugas

#### Success Response (200):

```json
{
    "success": true,
    "data": {
        "case": {
            "id": "01HF5MGCJ123EXAMPLE",
            "status": "DISPATCHED",
            "category": "KECELAKAAN",
            "description": "Kecelakaan sepeda motor di jalan raya",
            "lat": "-6.2088",
            "lon": "106.8456",
            "locator_text": "///word.word.word",
            "what3words_url": "https://what3words.com/word.word.word",
            "google_maps_url": "https://maps.google.com/maps?q=-6.2088,106.8456",
            "distance": 2.5,
            "eta_minutes": 8,
            "reporter_user": {
                "id": "uuid",
                "name": "John Doe",
                "phone": "+6281234567890"
            },
            "contacts_snapshot": [
                {
                    "name": "John Doe",
                    "phone": "+6281234567890",
                    "relation": "Pelapor",
                    "is_primary": true
                },
                {
                    "name": "Jane Doe",
                    "phone": "+6281234567891",
                    "relation": "Istri",
                    "is_primary": false
                }
            ],
            "case_events": [
                {
                    "action": "CREATED",
                    "created_at": "2025-10-02T10:00:00.000000Z",
                    "metadata": {
                        "reporter_name": "John Doe"
                    }
                }
            ],
            "dispatched_at": "2025-10-02T10:10:00.000000Z"
        }
    }
}
```

---

### 21. **Update Case Status**

**Endpoint:** `POST /petugas/cases/{case_id}/status`  
**Auth:** Bearer Token (Role: PETUGAS)  
**Description:** Update case status by petugas

#### Request Body:

```json
{
    "status": "enum (required): ON_THE_WAY|ON_SCENE|RESOLVED",
    "notes": "string (nullable, max:500)",
    "location": {
        "latitude": "number (nullable)",
        "longitude": "number (nullable)"
    }
}
```

#### Success Response (200):

```json
{
    "success": true,
    "message": "Status kasus berhasil diperbarui",
    "data": {
        "case": {
            "id": "01HF5MGCJ123EXAMPLE",
            "status": "ON_SCENE",
            "on_scene_at": "2025-10-02T10:20:00.000000Z"
        },
        "event": {
            "id": 2,
            "action": "ON_SCENE",
            "metadata": {
                "notes": "Petugas sudah tiba di lokasi",
                "petugas_location": {
                    "latitude": "-6.2088",
                    "longitude": "106.8456"
                }
            },
            "created_at": "2025-10-02T10:20:00.000000Z"
        }
    }
}
```

---

### 22. **Add Case Note**

**Endpoint:** `POST /petugas/cases/{case_id}/note`  
**Auth:** Bearer Token (Role: PETUGAS)  
**Description:** Add note to case

#### Request Body:

```json
{
    "note": "string (required, max:1000)",
    "type": "enum (nullable): INFO|WARNING|URGENT",
    "is_visible_to_reporter": "boolean (nullable, default: false)"
}
```

#### Success Response (200):

```json
{
    "success": true,
    "message": "Catatan berhasil ditambahkan",
    "data": {
        "note": {
            "id": 1,
            "case_id": "01HF5MGCJ123EXAMPLE",
            "note": "Korban sudah dievakuasi ke ambulans",
            "type": "INFO",
            "is_visible_to_reporter": false,
            "created_by": "uuid",
            "created_at": "2025-10-02T10:25:00.000000Z"
        }
    }
}
```

---

### 23. **Get Family Contacts**

**Endpoint:** `GET /petugas/cases/{case_id}/family-contacts`  
**Auth:** Bearer Token (Role: PETUGAS)  
**Description:** Get family contact information for case

#### Success Response (200):

```json
{
    "success": true,
    "data": {
        "contacts": [
            {
                "name": "John Doe",
                "phone": "+6281234567890",
                "relation": "Pelapor",
                "is_primary": true
            },
            {
                "name": "Jane Doe",
                "phone": "+6281234567891",
                "relation": "Istri",
                "is_primary": false
            }
        ],
        "emergency_contact": {
            "name": "Jane Doe",
            "phone": "+6281234567891",
            "relation": "Istri"
        }
    }
}
```

---

### 24. **Contact Family**

**Endpoint:** `POST /petugas/cases/{case_id}/contact-family`  
**Auth:** Bearer Token (Role: PETUGAS)  
**Description:** Log family contact attempt

#### Request Body:

```json
{
    "contact_name": "string (required)",
    "contact_phone": "string (required)",
    "contact_method": "enum (required): CALL|SMS|WHATSAPP",
    "status": "enum (required): SUCCESS|FAILED|NO_ANSWER",
    "notes": "string (nullable, max:500)"
}
```

#### Success Response (200):

```json
{
    "success": true,
    "message": "Kontak keluarga berhasil dicatat",
    "data": {
        "contact_log": {
            "id": 1,
            "case_id": "01HF5MGCJ123EXAMPLE",
            "contact_name": "Jane Doe",
            "contact_phone": "+6281234567891",
            "contact_method": "CALL",
            "status": "SUCCESS",
            "notes": "Keluarga sudah dihubungi dan dalam perjalanan",
            "contacted_by": "uuid",
            "contacted_at": "2025-10-02T10:30:00.000000Z"
        }
    }
}
```

---

### 25. **Get Dashboard Data**

**Endpoint:** `GET /petugas/dashboard`  
**Auth:** Bearer Token (Role: PETUGAS)  
**Description:** Get dashboard data for petugas

#### Success Response (200):

```json
{
    "success": true,
    "data": {
        "summary": {
            "active_cases": 2,
            "completed_today": 5,
            "total_distance": 45.2,
            "duty_duration": "06:30:00"
        },
        "active_cases": [
            {
                "id": "01HF5MGCJ123EXAMPLE",
                "status": "ON_THE_WAY",
                "category": "KECELAKAAN",
                "distance": 2.5,
                "eta_minutes": 8,
                "priority": "HIGH"
            }
        ],
        "recent_activity": [
            {
                "case_id": "01HF5MGCJ123EXAMPLE",
                "action": "STATUS_UPDATED",
                "description": "Status diubah menjadi ON_SCENE",
                "timestamp": "2025-10-02T10:20:00.000000Z"
            }
        ],
        "performance": {
            "average_response_time": 12.5,
            "cases_handled_today": 5,
            "rating": 4.8
        }
    }
}
```

---

## 📊 **Data Models**

### **User Model:**

```json
{
    "id": "uuid",
    "name": "string",
    "email": "string|null",
    "phone": "string|null",
    "role": "WARGA|PETUGAS|ADMIN",
    "unit_id": "uuid|null",
    "last_latitude": "decimal|null",
    "last_longitude": "decimal|null",
    "last_location_update": "datetime|null",
    "duty_status": "ON_DUTY|OFF_DUTY|null",
    "duty_started_at": "datetime|null",
    "last_activity_at": "datetime|null",
    "email_verified_at": "datetime|null",
    "created_at": "datetime",
    "updated_at": "datetime"
}
```

### **CitizenProfile Model:**

```json
{
    "id": "integer",
    "user_id": "uuid",
    "nik": "string|null",
    "whatsapp_keluarga": "string|null",
    "hubungan": "enum|null",
    "birth_date": "date|null",
    "blood_type": "A|B|AB|O|UNKNOWN|null",
    "chronic_conditions": "string|null",
    "ktp_image_url": "string|null",
    "avatar_url": "string|null",
    "created_at": "datetime",
    "updated_at": "datetime"
}
```

### **Case Model:**

```json
{
    "id": "ulid",
    "reporter_user_id": "uuid",
    "device_id": "string|null",
    "viewer_token_hash": "string",
    "phone": "string|null",
    "lat": "decimal",
    "lon": "decimal",
    "accuracy": "integer|null",
    "locator_text": "string",
    "locator_provider": "w3w|coordinates",
    "category": "UMUM|BENCANA_ALAM|KECELAKAAN|KEBOCORAN_GAS|POHON_TUMBANG|BANJIR",
    "description": "string|null",
    "status": "NEW|PENDING|VERIFIED|DISPATCHED|ON_THE_WAY|ON_SCENE|RESOLVED|CANCELLED",
    "assigned_unit_id": "uuid|null",
    "assigned_petugas_id": "uuid|null",
    "contacts_snapshot": "array",
    "verified_at": "datetime|null",
    "dispatched_at": "datetime|null",
    "on_scene_at": "datetime|null",
    "closed_at": "datetime|null",
    "created_at": "datetime",
    "updated_at": "datetime"
}
```

### **Unit Model:**

```json
{
    "id": "uuid",
    "name": "string",
    "type": "AMBULANCE|FIRE_TRUCK|POLICE|RESCUE|SAR",
    "location": "string",
    "capacity": "integer",
    "is_active": "boolean",
    "created_at": "datetime",
    "updated_at": "datetime"
}
```

---

## 📝 **Response Format**

### **Success Response:**

```json
{
    "success": true,
    "message": "string (optional)",
    "data": {
        // response data
    }
}
```

### **Error Response:**

```json
{
    "success": false,
    "message": "string",
    "errors": {
        "field": ["error message"]
    }
}
```

### **Validation Error (422):**

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "field_name": ["The field_name field is required."]
    }
}
```

---

## ⚠️ **Error Codes**

-   **200**: Success
-   **201**: Created
-   **400**: Bad Request
-   **401**: Unauthorized (Invalid or missing token)
-   **403**: Forbidden (Insufficient permissions)
-   **404**: Not Found
-   **422**: Validation Error
-   **500**: Internal Server Error

---

## 🔑 **Authentication**

All protected endpoints require Bearer token in header:

```
Authorization: Bearer {token}
```

Token is obtained from `/login` or `/register` endpoints and should be stored securely in the mobile app.

---

## 📱 **Mobile App Integration Notes**

1. **Location Services**: Apps should request location permissions for emergency reporting and petugas tracking
2. **Real-time Updates**: Consider implementing WebSocket or FCM for real-time status updates
3. **Offline Support**: Cache essential data for offline access
4. **Security**: Store tokens securely using Keychain (iOS) or Keystore (Android)
5. **Error Handling**: Implement proper error handling for network failures
6. **File Uploads**: Use multipart/form-data for image uploads (avatar, KTP)

---

**Last Updated:** October 2, 2025  
**API Version:** 1.0
