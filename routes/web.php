<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\RealisasiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LoginHistoryController;

// Rute Autentikasi Mandiri
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Halaman Depan Redirect ke Dashboard jika login, atau ke Login jika belum
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Grup Rute yang Memerlukan Login
Route::middleware(['auth'])->group(function () {
    // Dashboard & Monitoring Realisasi
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [DashboardController::class, 'index'])->name('home');

    // Upload PDF & Parsing OCR AI Gemini
    Route::get('/upload', [UploadController::class, 'create'])->name('upload.create');
    Route::post('/upload', [UploadController::class, 'store'])->name('upload.store');
    Route::get('/upload/preview/{id}', [UploadController::class, 'preview'])->name('upload.preview');
    Route::post('/upload/confirm/{id}', [UploadController::class, 'confirm'])->name('upload.confirm');
    Route::get('/upload/processing/{id}', [UploadController::class, 'processing'])->name('upload.processing');
    Route::get('/upload/ocr-status/{id}', [UploadController::class, 'ocrStatus'])->name('upload.ocr-status');
    Route::get('/retribusi/upload', [UploadController::class, 'index'])->name('retribusi.upload');

    // Data Realisasi Retribusi & Ekspor
    Route::get('/realisasi', [RealisasiController::class, 'index'])->name('retribusi.index');
    Route::get('/realisasi-data', [RealisasiController::class, 'index'])->name('realisasi.index');
    Route::put('/realisasi/batch-update', [RealisasiController::class, 'batchUpdate'])->name('realisasi.batchUpdate');
    Route::put('/realisasi/{id}', [RealisasiController::class, 'update'])->name('realisasi.update');
    Route::delete('/realisasi/{id}', [RealisasiController::class, 'destroy'])->name('realisasi.destroy');
    Route::get('/realisasi/export', [RealisasiController::class, 'export'])->name('realisasi.export');

    // Profil Pengguna & Ubah Password
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Fitur Khusus Administrator BAPENDA
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        // Kelola Pengguna (User Admin & OPD)
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

        // Audit Trail Riwayat Login
        Route::get('/login-histories', [LoginHistoryController::class, 'index'])->name('login_histories.index');
        Route::delete('/login-histories/clear', [LoginHistoryController::class, 'clear'])->name('login_histories.clear');
    });
});
