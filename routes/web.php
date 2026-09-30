<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\RealisasiController;

Auth::routes();

// Tanpa auth untuk fokus penuh ke fitur OCR AI
Route::get('/', function () {
    return redirect()->route('upload.create');
});

Route::group([], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Upload PDF & Parsing
    Route::get('/upload', [UploadController::class, 'create'])->name('upload.create');
    Route::post('/upload', [UploadController::class, 'store'])->name('upload.store');
    Route::get('/upload/preview/{id}', [UploadController::class, 'preview'])->name('upload.preview');
    Route::post('/upload/confirm/{id}', [UploadController::class, 'confirm'])->name('upload.confirm');
    
    // Data Realisasi & Export
    Route::get('/realisasi', [RealisasiController::class, 'index'])->name('retribusi.index');
    Route::get('/realisasi-data', [RealisasiController::class, 'index'])->name('realisasi.index');
    Route::put('/realisasi/batch-update', [RealisasiController::class, 'batchUpdate'])->name('realisasi.batchUpdate');
    Route::delete('/realisasi/{id}', [RealisasiController::class, 'destroy'])->name('realisasi.destroy');
    Route::get('/realisasi/export', [RealisasiController::class, 'export'])->name('realisasi.export');
    
    // Rute retribusi.upload untuk menu di sidebar Anda (Fix Error 500 sebelumnya)
    Route::get('/retribusi/upload', [UploadController::class, 'index'])->name('retribusi.upload');
});