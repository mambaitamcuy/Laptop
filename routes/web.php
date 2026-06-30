<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OltpDashboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\GoogleController;

/*
|--------------------------------------------------------------------------
| Web Routes - Sistem Informasi Arkadia Laptop (OLTP, DWH & Keamanan MFA)
|--------------------------------------------------------------------------
*/

// =========================================================================
// 1. 🔑 JALUR GUEST (Hanya Bisa Diakses Jika BELUM Login)
// =========================================================================
Route::middleware(['guest'])->group(function () {
    
    // 🔐 A. LOGIN MANUAL
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    // Form OTP Manual (Session-Based dari LoginController)
    Route::get('/verify-mfa', [LoginController::class, 'showMfaForm'])->name('mfa.verify');
    Route::post('/verify-mfa', [LoginController::class, 'verifyMfa'])->name('mfa.verify.post');

    // 🌐 B. LOGIN VIA GOOGLE (Pemicu Awal)
    Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
    Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');
});


// =========================================================================
// 2. 🛡️ JALUR KEEFEKTIFAN AUTH (Wajib Login / Melalui Google)
// =========================================================================
Route::middleware(['auth'])->group(function () {

    // 🔒 C. HALAMAN & PROSES VERIFIKASI OTP GOOGLE (Database-Based)
    Route::get('/verify-otp', function () {
        return view('auth.verify-otp');
    })->name('otp.verify');
    
    Route::post('/verify-otp', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'otp' => 'required|numeric',
        ]);

        $user = auth()->user();

        if ($user && $user->two_factor_code == $request->otp && now()->lt($user->two_factor_expires_at)) {
            $user->update([
                'two_factor_code' => null,
                'two_factor_expires_at' => null,
            ]);

            session(['otp_verified' => true]);
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['otp' => 'Kode OTP yang Anda masukkan salah atau telah kedaluwarsa!']);
    })->name('otp.verify.submit');


    // =====================================================================
    // 3. 🖥️ KELOMPOK DASHBOARD INTERNAL ArkadiaLP
    // =====================================================================
    
    // 🅰️ Grup Rute Operasional (OLTP)
    Route::prefix('oltp')->name('oltp.')->group(function () {
        Route::get('/dashboard', [OltpDashboardController::class, 'index'])->name('dashboard');
        Route::get('/stok', [OltpDashboardController::class, 'stok'])->name('stok');
        Route::post('/stok/store', [OltpDashboardController::class, 'storeStok'])->name('stok.store');
        Route::get('/transaksi', [OltpDashboardController::class, 'transaksi'])->name('transaksi');
        Route::post('/transaksi/store', [OltpDashboardController::class, 'storeTransaksi'])->name('transaksi.store');
        
        // Fitur Karyawan Arkadia
        Route::get('/karyawan', [OltpDashboardController::class, 'karyawan'])->name('karyawan');
        Route::post('/karyawan/store', [OltpDashboardController::class, 'storeKaryawan'])->name('karyawan.store');
    });

    // 🅱️ Grup Rute Analitik (DWH)
    Route::prefix('dwh')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dwh.dashboard');
        Route::get('/profit', [DashboardController::class, 'profit'])->name('dwh.profit');
        Route::get('/cabang', [DashboardController::class, 'cabang'])->name('dwh.cabang');
        Route::get('/etl-report', [DashboardController::class, 'etlReport'])->name('dwh.etl-report');
        Route::post('/run-etl', [DashboardController::class, 'runEtl'])->name('dwh.run-etl');
    });

    // 🚪 Tombol Keluar Sistem Resmi
    Route::match(['get', 'post'], '/logout', [LoginController::class, 'logout'])->name('logout');
});


// =========================================================================
// 4. ALUR REDIRECT UTAMA & PENGAMAN
// =========================================================================
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return redirect()->route('oltp.dashboard');
})->name('dashboard')->middleware('auth');

if (file_exists(__DIR__.'/auth.php')) {
    require __DIR__.'/auth.php';
}