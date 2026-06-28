<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Middleware\EnsureOtpIsVerified; // 📌 1. IMPORT CLASS MIDDLEWARE DI SINI
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman utama otomatis lempar ke Login
Route::get('/', function () {
    return redirect()->route('login');
});

// Grup Guest (Hanya diakses sebelum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', function () { return view('auth.login'); })->name('login');
    Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
    Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
});

// Jalur Resmi Logout
Route::get('/logout', [GoogleController::class, 'logout'])->name('logout');

// ===================================================================
// 🔐 GRUP VALIDASI MFA (Wajib Lulus Login tapi Belum Isi OTP)
// ===================================================================
Route::middleware('auth')->group(function () {
    Route::get('/login/verify', [OtpController::class, 'showVerifyForm'])->name('otp.verify');
    Route::post('/login/verify', [OtpController::class, 'verify'])->name('otp.verify.submit');
});

// ===================================================================
// 🏰 GRUP UTAMA INTERNAL (Langsung Panggil Class-nya di Sini, Anti-Error!)
// ===================================================================
Route::middleware(['auth', EnsureOtpIsVerified::class])->group(function () {
    
    Route::get('/dashboard', function () {
        return "<h1>🔥 Selamat Datang di Dashboard Arkadia Analytics, Cik! 🔥</h1>
                <p>Status Keamanan: <strong>MFA SECURE (Lulus Verifikasi OTP)</strong></p>
                <p>Hak Akses Akun: " . strtoupper(Auth::user()->role) . "</p>
                <a href='/logout'>Keluar Sistem</a>";
    })->name('dashboard');

    // Tempatkan rute internal OLTP & Data Warehouse Om lainnya di dalam grup ini...
});