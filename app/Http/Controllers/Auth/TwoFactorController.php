<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TwoFactorController extends Controller
{
    /**
     * Menampilkan halaman input kode verifikasi 2FA
     */
    public function index(Request $request)
    {
        if (!$request->session()->has('two_factor_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.two-factor');
    }

    /**
     * Memverifikasi kode OTP yang dimasukkan pengguna (Faktor Kedua)
     */
    public function store(Request $request)
    {
        $request->validate([
            'two_factor_code' => ['required', 'string', 'size:6'],
        ]);

        if (!$request->session()->has('two_factor_user_id')) {
            return redirect()->route('login');
        }

        $user = User::find($request->session()->get('two_factor_user_id'));

        // Proteksi Siber: Periksa validitas kode OTP dan waktu kedaluwarsa
        if ($user->two_factor_code !== $request->two_factor_code || now()->gt($user->two_factor_expires_at)) {
            throw ValidationException::withMessages([
                'two_factor_code' => 'Kode verifikasi salah atau telah kedaluwarsa.',
            ]);
        }

        // Jika kode valid, hapus token OTP dari database agar tidak bisa digunakan ulang (Replay Attack)
        $user->two_factor_code = null;
        $user->two_factor_expires_at = null;
        $user->save();

        // Aktifkan sesi login resmi pengguna ke dalam sistem
        Auth::login($user, $request->session()->get('two_factor_remember', false));

        // Bersihkan sesi penampung sementara
        $request->session()->forget(['two_factor_user_id', 'two_factor_remember']);
        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }
}