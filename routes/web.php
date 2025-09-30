<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CaseActionController;
use App\Http\Controllers\CaseController;
use App\Http\Controllers\DashboardController;
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

    // Case Actions
    Route::post('/cases/{case}/verify', [CaseActionController::class, 'verify'])->name('cases.verify');
    Route::post('/cases/{case}/dispatch', [CaseActionController::class, 'dispatch'])->name('cases.dispatch');
    Route::post('/cases/{case}/close', [CaseActionController::class, 'close'])->name('cases.close');
});
