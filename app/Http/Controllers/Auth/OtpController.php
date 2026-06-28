<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class OtpController extends Controller
{
    /**
     * Menampilkan Form Pengisian OTP
     */
    public function showVerifyForm()
    {
        $user = Auth::user();
        
        // Agar Om tidak lelah cek database terus saat coding local, 
        // kodenya saya titipkan di variabel $debug_otp untuk ditampilkan di layar.
        return view('auth.verify-otp', [
            'debug_otp' => $user->two_factor_code
        ]);
    }

    /**
     * Memproses Validasi Kode OTP dari User
     */
    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric',
        ]);

        $user = Auth::user();

        // Cek apakah OTP cocok dan waktu expired-nya belum lewat
        if ($user->two_factor_code == $request->otp && now()->isBefore($user->two_factor_expires_at)) {
            
            // Bersihkan sisa OTP di database agar tidak bisa dipakai ulang (Anti-Bruteforce)
            User::where('email', $user->email)->update([
                'two_factor_code'       => null,
                'two_factor_expires_at' => null,
            ]);

            // 🔑 KUNCI UTAMA: Set session penanda bahwa MFA Sukses!
            session(['otp_verified' => true]);

            // Lempar masuk ke Dashboard utama
            return redirect()->to('/dashboard');
        }

        // Jika gagal, kembalikan dengan pesan error
        return back()->withErrors([
            'otp' => 'Kode OTP yang Anda masukkan salah atau telah kedaluwarsa!'
        ]);
    }
}