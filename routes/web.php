<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CaseActionController;
use App\Http\Controllers\CaseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\PimpinanController;
use App\Http\Controllers\SlaReportController;
// use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Pimpinan Routes - Only for PIMPINAN role
Route::middleware(['auth', 'web.role', 'pimpinan'])->prefix('pimpinan')->group(function () {
    Route::get('/dashboard', [PimpinanController::class, 'dashboard'])->name('pimpinan.dashboard');
    Route::get('/cases/{case}', [PimpinanController::class, 'showCase'])->name('pimpinan.case.show');
    Route::post('/dispatches/{dispatch}/assign', [PimpinanController::class, 'assignPetugas'])->name('pimpinan.assign');

    // Petugas Management
    Route::get('/petugas', [PimpinanController::class, 'managePetugas'])->name('pimpinan.petugas');
    Route::post('/petugas', [PimpinanController::class, 'storePetugas'])->name('pimpinan.petugas.store');

    // Location Tracking
    Route::get('/map', [LocationController::class, 'index'])->name('pimpinan.map');
    Route::get('/api/petugas-locations', [LocationController::class, 'getPetugasLocations'])->name('pimpinan.petugas-locations');
});

// Petugas Routes - Only for PETUGAS role
Route::middleware(['auth', 'web.role'])->prefix('petugas')->group(function () {
    Route::get('/dashboard', [PetugasController::class, 'dashboard'])->name('petugas.dashboard');
    Route::get('/cases/{case}', [PetugasController::class, 'showCase'])->name('petugas.case.show');
    Route::post('/cases/{case}/update-status', [PetugasController::class, 'updateStatus'])->name('petugas.update-status');

    // Location Tracking
    Route::post('/location/update', [LocationController::class, 'updateLocation'])->name('petugas.location.update');
});

// API Routes for location (accessible from web with auth)
Route::middleware(['auth'])->prefix('api')->group(function () {
    Route::post('/location/update', [LocationController::class, 'updateLocation'])->name('api.location.update');
    Route::get('/location/user/{userId}', [LocationController::class, 'getUserLocation'])->name('api.location.user');
    Route::get('/location/unit/{unitId?}', [LocationController::class, 'getUnitLocations'])->name('api.location.unit');
});

// SLA Report - Accessible by all authenticated users
Route::middleware(['auth'])->group(function () {
    Route::get('/reports/sla', [SlaReportController::class, 'index'])->name('reports.sla');
});

// Operator Routes - For SUPERADMIN and OPERATOR
Route::middleware(['auth', 'web.role', 'operator'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Cases
    Route::get('/cases', [CaseController::class, 'index'])->name('cases.index');
    Route::get('/cases/{case}', [CaseController::class, 'show'])->name('cases.show');
    Route::get('/cases/{case}/modal', [CaseController::class, 'modal'])->name('cases.modal');
    Route::get('/cases/{case}/whatsapp', [CaseController::class, 'whatsapp'])->name('cases.whatsapp');
    Route::post('/cases/{case}/send-whatsapp', [CaseController::class, 'sendWhatsapp'])->name('cases.send-whatsapp');

    // Case Actions
    Route::post('/cases/{case}/dispatch-multi', [CaseController::class, 'dispatchMulti'])->name('cases.dispatch-multi');
    Route::post('/cases/{case}/close', [CaseController::class, 'close'])->name('cases.close');
    Route::post('/cases/{case}/cancel', [CaseController::class, 'cancel'])->name('cases.cancel');

    // AJAX Endpoints
    Route::get('/api/units/{unit}/petugas', [CaseController::class, 'getPetugasByUnit'])->name('units.petugas');

    // Unit Management - Super Admin Only
    Route::resource('units', UnitController::class);
    Route::post('/units/{unit}/toggle-status', [UnitController::class, 'toggleStatus'])->name('units.toggle-status');

    // User Management - Super Admin Only
    Route::resource('users', UserController::class);
    Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::get('/users/{user}/whatsapp', [UserController::class, 'whatsapp'])->name('users.whatsapp');
    Route::post('/users/{user}/send-whatsapp', [UserController::class, 'sendWhatsapp'])->name('users.send-whatsapp');

    // Location Tracking
    Route::get('/map', [LocationController::class, 'index'])->name('map');
    Route::get('/api/petugas-locations', [LocationController::class, 'getPetugasLocations'])->name('petugas-locations');

    // Notifications
    Route::get('/api/notifications', [CaseController::class, 'getNotifications'])->name('notifications.index');
    Route::post('/api/notifications/{id}/read', [CaseController::class, 'markNotificationAsRead'])->name('notifications.read');
    Route::post('/api/notifications/mark-all-read', [CaseController::class, 'markAllAsRead'])->name('notifications.markAllRead');
    Route::get('/api/notifications/unread-count', [CaseController::class, 'getUnreadCount'])->name('notifications.unreadCount');
});
