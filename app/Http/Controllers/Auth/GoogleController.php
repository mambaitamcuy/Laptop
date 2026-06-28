<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail; // 📌 IMPORT FACADE MAIL
use App\Mail\SendOtpMail;             // 📌 IMPORT FILE MAIL_OTP KITA
use Illuminate\Http\Request;
use Exception;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // 1. Generate 6 Digit Angka OTP Acak
                $otpCode = rand(100000, 999999);

                // 2. Update data pelengkap Google sekalian titip kode OTP ke MySQL
                User::where('email', $googleUser->getEmail())->update([
                    'google_id'             => $googleUser->getId(),
                    'avatar'                => $googleUser->getAvatar(),
                    'login_method'          => 'GOOGLE', 
                    'two_factor_code'       => $otpCode,
                    'two_factor_expires_at' => now()->addMinutes(10),
                ]);
                
                $authenticatedUser = User::where('email', $googleUser->getEmail())->first();
                
                // 3. 🚀 KIRIM EMAIL OTP ASLI KE USER
                Mail::to($authenticatedUser->email)->send(new SendOtpMail($otpCode));
                
                // 4. Loginkan user ke sistem Auth Laravel
                Auth::login($authenticatedUser);
                session()->forget('otp_verified');
                
                return redirect()->route('otp.verify');
            } else {
                return redirect('/login')->withErrors([
                    'email' => 'Email Google Anda belum didaftarkan oleh Manajemen Pusat.'
                ]);
            }

        } catch (Exception $e) {
            return redirect('/login')->withErrors([
                'email' => 'Gagal melakukan otentikasi menggunakan Google: ' . $e->getMessage()
            ]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}