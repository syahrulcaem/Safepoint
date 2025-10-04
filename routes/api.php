<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\CaseController;
use App\Http\Controllers\Api\PetugasController;
use App\Http\Controllers\Api\PublicEmergencyController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/petugas/login', [AuthController::class, 'petugasLogin']);

// Public Emergency - No authentication required (rate limited)
Route::post('/public/emergency', [PublicEmergencyController::class, 'store'])
    ->middleware('throttle:5,1'); // 5 requests per minute per IP

// Protected routes for citizens
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Profile
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::post('/profile/avatar', [ProfileController::class, 'uploadAvatar']);
    Route::post('/profile/ktp', [ProfileController::class, 'uploadKtp']);

    // Emergency Cases
    Route::post('/emergency', [CaseController::class, 'create']);
    Route::get('/emergency/{case}', [CaseController::class, 'show']);
    Route::get('/my-cases', [CaseController::class, 'myCases']);
});

// Protected routes for Petugas (Field Officers)
Route::middleware(['auth:sanctum', 'role:PETUGAS'])->prefix('petugas')->group(function () {
    // Profile Management
    Route::get('/profile', [PetugasController::class, 'profile']);
    Route::put('/profile', [PetugasController::class, 'updateProfile']);

    // Device & Notification Management
    Route::post('/device-token', [PetugasController::class, 'registerDeviceToken']);
    Route::get('/check-updates', [PetugasController::class, 'checkUpdates']);

    // Duty Status
    Route::post('/duty/start', [PetugasController::class, 'startDuty']);
    Route::post('/duty/end', [PetugasController::class, 'endDuty']);
    Route::get('/duty/status', [PetugasController::class, 'getDutyStatus']);

    // Location Tracking
    Route::post('/location/update', [PetugasController::class, 'updateLocation']);
    Route::get('/location/current', [PetugasController::class, 'getCurrentLocation']);

    // Case Management
    Route::get('/cases/assigned', [PetugasController::class, 'getAssignedCases']);
    Route::get('/cases/{case}', [PetugasController::class, 'getCaseDetail']);
    Route::post('/cases/{case}/status', [PetugasController::class, 'updateCaseStatus']);
    Route::post('/cases/{case}/note', [PetugasController::class, 'addCaseNote']);

    // Polling for updates (no database changes needed)
    Route::get('/check-updates', [PetugasController::class, 'checkUpdates']);

    // What3Words API
    Route::get('/what3words/{lat}/{lon}', [PetugasController::class, 'getWhat3Words']);
    Route::post('/what3words/coordinates', [PetugasController::class, 'getCoordinatesFromWhat3Words']);

    // Communication
    Route::get('/cases/{case}/family-contacts', [PetugasController::class, 'getFamilyContacts']);
    Route::post('/cases/{case}/contact-family', [PetugasController::class, 'contactFamily']);

    // Dashboard Data
    Route::get('/dashboard', [PetugasController::class, 'getDashboardData']);
});
