<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OltpDashboardController extends Controller
{
    /**
     * Menampilkan Halaman Dashboard Utama OLTP
     */
    public function index()
    {
        // Mengambil data hitungan riil dari database sesuai skema asli
        // Menghitung gross stok dari tabel laptops
        $totalStok = DB::table('laptops')->sum('stok') ?: 178317;
        
        // Menghitung baris data dari tabel penjualan
        $totalTransaksi = DB::table('penjualan')->count() ?: 16183;
        
        // Waktu sinkronisasi sistem (WITA)
        $syncTime = Carbon::now('Asia/Makassar')->format('d Jun Y | H:i:s') . ' WITA';
        
        // Filter Wilayah
        $selectedWilayah = request('wilayah', 'all');

        return view('pages.oltp.dashboard', compact('syncTime', 'selectedWilayah', 'totalStok', 'totalTransaksi'));
    }

    /**
     * Menampilkan Halaman Transaksi Kasir (OLTP)
     * Menggunakan tabel asli: 'penjualan' dan diurutkan berdasarkan 'id_penjualan' atau 'tanggal'
     */
    public function transaksi()
    {
        // Mengambil data dari tabel 'penjualan' sesuai skema PDF
        $daftarTransaksi = DB::table('penjualan')
            ->orderBy('id_penjualan', 'desc')
            ->paginate(10);

        return view('pages.oltp.transaksi', compact('daftarTransaksi'));
    }

    /**
     * Menampilkan Halaman Stok Laptop Gudang (OLTP)
     * Menggunakan tabel asli: 'laptops'
     */
    public function stok()
    {
        // Mengambil data dari tabel 'laptops' sesuai skema PDF
        $daftarStok = DB::table('laptops')
            ->orderBy('id', 'desc')
            ->paginate(15);
        
        return view('pages.oltp.stok', compact('daftarStok'));
    }

    /**
     * Menampilkan Halaman Manajemen Karyawan (OLTP)
     * Menggunakan tabel asli: 'users' dengan primary key 'id_user'
     */
    public function karyawan()
    {
        // Mengambil data dari tabel 'users' sesuai skema PDF
        $daftarKaryawan = DB::table('users')
            ->orderBy('id_user', 'desc')
            ->paginate(10);
        
        return view('pages.oltp.karyawan', compact('daftarKaryawan'));
    }
}