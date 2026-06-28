<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * 1. Menampilkan Halaman Login Manual
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * 2. Proses Otentikasi Awal & Kirim OTP via Session
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::validate($credentials)) {
            $user = User::where('email', $credentials['email'])->first();
            $otpCode = rand(100000, 999999);

            session([
                'mfa_user_id'  => $user->id,
                'mfa_otp_code' => $otpCode,
                'mfa_expires'  => now()->addMinutes(5),
            ]);

            try {
                Mail::raw("Kode OTP Keamanan Arkadia LP Anda adalah: $otpCode.", function ($message) use ($user) {
                    $message->to($user->email)->subject('🔒 Verifikasi Keamanan MFA - Arkadia LP');
                });
            } catch (\Exception $e) {
                // Tetap aman di lokal jika mail server mati
            }

            return redirect()->route('mfa.verify')->with('info', 'Masukkan kode OTP Anda.');
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    /**
     * 🌟 3. MENAMPILKAN FORM VERIFIKASI OTP (INI YANG DICARI ERROR OM)
     */
    public function showMfaForm()
    {
        if (!session()->has('mfa_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.verify-otp');
    }

    /**
     * 4. Validasi Kode OTP yang Diinput
     */
    public function verifyMfa(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric',
        ]);

        if (!session()->has('mfa_user_id') || now()->gt(session('mfa_expires'))) {
            return redirect()->route('login')->with('error', 'Sesi verifikasi berakhir.');
        }

        if ($request->otp == session('mfa_otp_code')) {
            $user = User::find(session('mfa_user_id'));

            if ($user) {
                Auth::login($user);
                session()->forget(['mfa_user_id', 'mfa_otp_code', 'mfa_expires']);
                return redirect()->route('dashboard');
            }
        }

        return back()->with('error', 'Kode OTP salah.');
    }

    /**
     * 5. Tombol Logout Resmi
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}