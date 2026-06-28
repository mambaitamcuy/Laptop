<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Menampilkan form registrasi (Hanya bisa dibuka oleh pusat).
     */
    public function create(): View
    {
        // Mengambil daftar cabang dari OLTP untuk opsi pilihan di select option form
        $daftarCabang = DB::connection('mysql')->table('cabang')->get();
        return view('auth.register-karyawan', compact('daftarCabang'));
    }

    /**
     * Menangani proses pendaftaran akun karyawan/admin cabang baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:pusat,cabang,karyawan'],
            'id_cabang' => ['nullable', 'integer'], // Boleh null jika dia admin pusat global
        ]);

        // Create user baru tanpa otomatis login (karena yang mendaftarkan adalah admin pusat)
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'id_cabang' => $request->role === 'pusat' ? null : $request->id_cabang,
        ]);

        return redirect()->route('dashboard')->with('success', 'Akun staf/karyawan baru berhasil didaftarkan!');
    }
}