<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CaseActionController;
use App\Http\Controllers\CaseController;
use App\Http\Controllers\DashboardController;
// use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes - Admin Web Only
Route::middleware(['auth', 'web.role'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Cases
    Route::get('/cases', [CaseController::class, 'index'])->name('cases.index');
    Route::get('/cases/{case}', [CaseController::class, 'show'])->name('cases.show');
    Route::get('/cases/{case}/modal', [CaseController::class, 'modal'])->name('cases.modal');
    Route::get('/cases/{case}/whatsapp', [CaseController::class, 'whatsapp'])->name('cases.whatsapp');
    Route::post('/cases/{case}/send-whatsapp', [CaseController::class, 'sendWhatsapp'])->name('cases.send-whatsapp');

    // Case Actions
    Route::post('/cases/{case}/verify', [CaseController::class, 'verify'])->name('cases.verify');
    Route::post('/cases/{case}/dispatch', [CaseController::class, 'dispatch'])->name('cases.dispatch');
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
    
    // Notifications
    Route::get('/api/notifications', [CaseController::class, 'getNotifications'])->name('notifications.index');
    Route::post('/api/notifications/{id}/read', [CaseController::class, 'markNotificationAsRead'])->name('notifications.read');
    Route::post('/api/notifications/mark-all-read', [CaseController::class, 'markAllAsRead'])->name('notifications.markAllRead');
    Route::get('/api/notifications/unread-count', [CaseController::class, 'getUnreadCount'])->name('notifications.unreadCount');
});
