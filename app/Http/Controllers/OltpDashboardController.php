<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OltpDashboardController extends Controller
{
    /**
     * 1. HALAMAN UTAMA DASHBOARD OPERASIONAL (OLTP)
     */
    public function index()
    {
        $totalStok = DB::table('laptops')->sum('stok') ?? 0;
        $totalTransaksi = DB::table('penjualan')->count();
        $totalKaryawan = DB::table('karyawan')->count();
        $syncTime = now()->format('H:i:s');

        // Menggunakan kolom 'tanggal' (sesuai struktur tabel di gambar)
        $volumeHariIni = DB::table('penjualan')
                            ->whereDate('tanggal', now()->toDateString())
                            ->count();

        // Metode Pembayaran Terpopuler
        $metodeTerpopuler = DB::table('penjualan')
                                ->select('metode_pembayaran', DB::raw('count(*) as total'))
                                ->groupBy('metode_pembayaran')
                                ->orderBy('total', 'desc')
                                ->first();

        return view('pages.oltp.dashboard', compact(
            'totalStok', 
            'totalTransaksi', 
            'totalKaryawan', 
            'syncTime', 
            'volumeHariIni', 
            'metodeTerpopuler'
        ));
    }

    /**
     * 2. HALAMAN DAFTAR STOK LAPTOP
     */
    public function stok()
    {
        $daftarStok = DB::table('laptops')
                        ->orderBy('id', 'desc') 
                        ->paginate(10);

        return view('pages.oltp.stok', compact('daftarStok'));
    }

    /**
     * 3. PROSES SIMPAN DATA STOK LAPTOP BARU
     */
    public function storeStok(Request $request)
    {
        $request->validate([
            'nama_laptop' => 'required|string|max:255',
            'brand'       => 'required|string|max:100',
            'stok'        => 'required|integer|min:0',
            'harga'       => 'required|numeric|min:0',
        ]);

        DB::table('laptops')->insert([
            'nama_laptop' => $request->nama_laptop,
            'brand'       => $request->brand,
            'stok'        => $request->stok,
            'harga'       => $request->harga,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        return redirect()->back()->with('success', 'Stok laptop baru berhasil disimpan!');
    }

    /**
     * 4. HALAMAN INPUT & RIWAYAT TRANSAKSI KASIR
     */
    public function transaksi()
    {
        $daftarTransaksi = DB::table('penjualan')
                            ->orderBy('id_penjualan', 'desc')
                            ->paginate(10);

        $daftarLaptop = DB::table('laptops')
                          ->orderBy('nama_laptop', 'asc')
                          ->get();

        return view('pages.oltp.transaksi', compact('daftarTransaksi', 'daftarLaptop'));
    }

    /**
     * 5. PROSES SIMPAN TRANSAKSI PENJUALAN BARU
     */
    public function storeTransaksi(Request $request)
    {
        // Sesuaikan validasi dengan kolom yang tersedia di tabel 'penjualan'
        $request->validate([
            'metode_pembayaran' => 'required',
            'total'             => 'required|numeric',
        ]);

        // Menyimpan data ke tabel 'penjualan' sesuai kolom yang ada di gambar
        DB::table('penjualan')->insert([
            'invoice'           => 'INV-' . date('YmdHis'),
            'id_cabang'         => 1, // Sesuaikan dengan logika sistem Anda
            'id_user'           => 1, // Sesuaikan dengan id user yang login
            'metode_pembayaran' => $request->metode_pembayaran,
            'total'             => $request->total,
            'tanggal'           => now(),
        ]);

        return redirect()->back()->with('success', 'Transaksi berhasil disimpan!');
    }

    /**
     * 6. HALAMAN DIREKTORI KARYAWAN
     */
    public function karyawan()
    {
        $daftarKaryawan = DB::table('karyawan')
                            ->orderBy('id_karyawan', 'asc')
                            ->paginate(10);

        return view('pages.oltp.karyawan', compact('daftarKaryawan'));
    }

    /**
     * 7. PROSES SIMPAN KARYAWAN BARU
     */
    public function storeKaryawan(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:255',
            'email'     => 'required|email|max:255',
            'jabatan'   => 'required|string',
        ]);

        DB::table('karyawan')->insert([
            'nama'       => $request->nama,
            'email'      => $request->email,
            'jabatan'    => $request->jabatan,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Karyawan berhasil didaftarkan!');
    }
}