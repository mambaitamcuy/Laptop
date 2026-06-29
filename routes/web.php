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
    // Tampilan Form OTP Google (Menghubungkan baris 44 di GoogleController)
    Route::get('/verify-otp', function () {
        return view('auth.verify-otp');
    })->name('otp.verify');
    
    // Proses Validasi Form OTP Google ke Database (Mengatasi Error RouteNotFound)
    Route::post('/verify-otp', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'otp' => 'required|numeric',
        ]);

        $user = auth()->user(); // Ambil data user yang login setengah matang via Google

        // Validasi apakah kode OTP cocok dan belum kedaluwarsa (10 menit)
        if ($user && $user->two_factor_code == $request->otp && now()->lt($user->two_factor_expires_at)) {
            
            // Bersihkan sisa OTP di database demi keamanan
            $user->update([
                'two_factor_code' => null,
                'two_factor_expires_at' => null,
            ]);

            // Nyalakan session penanda sukses OTP
            session(['otp_verified' => true]);

            // Terbangkan ke dashboard utama ArkadiaLP
            return redirect()->route('dashboard');
        }

        // Jika salah, kembalikan dengan pesan error merah
        return back()->withErrors(['otp' => 'Kode OTP yang Anda masukkan salah atau telah kedaluwarsa!']);
    })->name('otp.verify.submit');


    // =====================================================================
    // 3. 🖥️ KELOMPOK DASHBOARD INTERNAL ArkadiaLP
    // =====================================================================
    // 🅰️ Grup Rute Operasional (OLTP)
    Route::prefix('oltp')->group(function () {
        Route::get('/dashboard', [OltpDashboardController::class, 'index'])->name('oltp.dashboard');
        Route::get('/stok', [OltpDashboardController::class, 'stok'])->name('oltp.stok');
        Route::get('/transaksi', [OltpDashboardController::class, 'transaksi'])->name('oltp.transaksi');
        Route::get('/karyawan', [OltpDashboardController::class, 'karyawan'])->name('oltp.karyawan');
    });

    // 🅱️ Grup Rute Analitik (DWH)
    Route::prefix('dwh')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dwh.dashboard');
        Route::get('/profit', [DashboardController::class, 'profit'])->name('dwh.profit');
        Route::get('/cabang', [DashboardController::class, 'cabang'])->name('dwh.cabang');
        Route::get('/etl-report', [DashboardController::class, 'etlReport'])->name('dwh.etl-report');
    });

    // 🚪 Tombol Keluar Sistem Resmi (Menggunakan LoginController POST)
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
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