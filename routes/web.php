<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\RealisasiController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MasterRetribusiController;
use App\Http\Controllers\GeminiOcrController;
use App\Http\Middleware\EnsureIsAdmin;

// Public Guest Routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Protected Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard Overview
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile & Password Management
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Upload PDF & Preview Validation
    Route::get('/upload', [UploadController::class, 'index'])->name('upload.index');
    Route::post('/upload/process', [UploadController::class, 'process'])->name('upload.process');
    Route::post('/upload/save', [UploadController::class, 'save'])->name('upload.save');

    // Data Realisasi, Edit, Export Excel & Print
    Route::get('/realisasi', [RealisasiController::class, 'index'])->name('realisasi.index');
    Route::post('/realisasi/bulk-delete', [RealisasiController::class, 'bulkDelete'])->name('realisasi.bulkDelete');
    Route::put('/realisasi/{id}', [RealisasiController::class, 'update'])->name('realisasi.update');
    Route::get('/realisasi/export', [RealisasiController::class, 'exportExcel'])->name('realisasi.export');
    Route::get('/realisasi/print', [RealisasiController::class, 'printReport'])->name('realisasi.print');
    Route::delete('/realisasi/{id}', [RealisasiController::class, 'destroy'])->name('realisasi.destroy');

    // Strictly Admin Only Routes
    Route::middleware(EnsureIsAdmin::class)->group(function () {
        Route::get('/master-retribusi', [MasterRetribusiController::class, 'index'])->name('master.index');
        Route::post('/master-retribusi', [MasterRetribusiController::class, 'store'])->name('master.store');
        Route::put('/master-retribusi/{id}', [MasterRetribusiController::class, 'update'])->name('master.update');
        Route::delete('/master-retribusi/{id}', [MasterRetribusiController::class, 'destroy'])->name('master.destroy');

        Route::get('/audit', [AuditController::class, 'index'])->name('audit.index');
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::post('/users/{id}/reset-password', [UserController::class, 'resetPassword'])->name('users.resetPassword');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // Uji Coba OCR Google Gemini 1.5 Flash (Modular & Terpisah)
    Route::get('/ocr-test', [GeminiOcrController::class, 'index'])->name('ocr.index');
    Route::post('/ocr-test', [GeminiOcrController::class, 'process'])->name('ocr.process');
});
