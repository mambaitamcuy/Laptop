<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OltpDashboardController extends Controller
{
    // Properti global database agar mudah diatur jika ada perubahan nama database
    protected $dbOltp = 'arkadialp_oltp';
    protected $kolomStokAsli = 'jumlah_stok'; // Sesuai hasil DESCRIBE phpMyAdmin

    /**
     * 1. HALAMAN DASHBOARD UTAMA (Ringkasan Data Riil)
     */
    public function index()
    {
        // Total karyawan terdaftar
        $totalKaryawan = DB::table($this->dbOltp . '.users')->count();

        // Total transaksi harian
        $totalTransaksiHariIni = DB::table($this->dbOltp . '.penjualan')->count();

        // Akumulasi pendapatan real-time
        $pendapatanHariIni = DB::table($this->dbOltp . '.detail_penjualan')->sum('subtotal') ?? 0;

        // Data 5 besar stok menipis (di bawah 15 unit) untuk widget alert
        $stokMenipis = DB::table($this->dbOltp . '.stok_cabang as s')
            ->join($this->dbOltp . '.produk as p', 's.id_produk', '=', 'p.id_produk')
            ->join($this->dbOltp . '.cabang as c', 's.id_cabang', '=', 'c.id_cabang')
            ->select('p.nama_produk', 'c.nama_cabang', 's.' . $this->kolomStokAsli . ' as stok') 
            ->where('s.' . $this->kolomStokAsli, '<', 15)
            ->orderBy('s.' . $this->kolomStokAsli, 'asc')
            ->take(5)
            ->get();

        // Riwayat 5 transaksi kasir terbaru
        $transaksiTerbaru = DB::table($this->dbOltp . '.penjualan as p')
            ->join($this->dbOltp . '.cabang as c', 'p.id_cabang', '=', 'c.id_cabang')
            ->select('p.id_penjualan', 'c.nama_cabang', 'p.id_user')
            ->orderBy('p.id_penjualan', 'desc')
            ->take(5)
            ->get();

        return view('pages.oltp.dashboard', compact(
            'totalTransaksiHariIni',
            'pendapatanHariIni',
            'totalKaryawan',
            'stokMenipis',
            'transaksiTerbaru'
        ));
    }

    /**
     * 2. HALAMAN MANAJEMEN KARYAWAN (Data Lengkap Staf + Cabang)
     */
    public function karyawan()
    {
        // Mengambil data user asli sekaligus menggabungkan tabel cabang
        $daftarKaryawan = DB::table($this->dbOltp . '.users as u')
            ->leftJoin($this->dbOltp . '.cabang as c', 'u.id_cabang', '=', 'c.id_cabang')
            ->select('u.*', 'c.nama_cabang')
            ->get();

        return view('pages.oltp.karyawan', compact('daftarKaryawan'));
    }

    /**
     * 3. HALAMAN INVENTARIS STOK LAPTOP (Data Lengkap Semua Stok Gudang & Cabang)
     */
    public function stok()
    {
        // Mengambil seluruh aset produk lengkap dengan relasi stok cabang
        $daftarStok = DB::table($this->dbOltp . '.stok_cabang as s')
            ->join($this->dbOltp . '.produk as p', 's.id_produk', '=', 'p.id_produk')
            ->join($this->dbOltp . '.cabang as c', 's.id_cabang', '=', 'c.id_cabang')
            ->select(
                's.id_stok', 
                'p.nama_produk', 
                'p.harga',   // Menarik kolom harga dari tabel produk
                'p.brand',   // Menarik kolom brand/merk laptop
                'c.nama_cabang', 
                's.' . $this->kolomStokAsli . ' as stok', 
                's.stok_minimum'
            )
            ->orderBy('c.nama_cabang', 'asc')
            ->get();

        return view('pages.oltp.stok', compact('daftarStok'));
    }

    /**
     * 4. HALAMAN TRANSAKSI KASIR (Data Lengkap Riwayat Penjualan)
     */
    public function transaksi()
    {
        // Mengambil semua log riwayat transaksi tanpa batasan limit data
        $daftarTransaksi = DB::table($this->dbOltp . '.penjualan as p')
            ->join($this->dbOltp . '.cabang as c', 'p.id_cabang', '=', 'c.id_cabang')
            ->select('p.id_penjualan', 'c.nama_cabang', 'p.id_user', 'p.tanggal_penjualan') 
            ->orderBy('p.id_penjualan', 'desc')
            ->get();

        return view('pages.oltp.transaksi', compact('daftarTransaksi'));
    }

    /**
     * 5. TOMBOL EKSEKUSI PIPA ETL (Prosedur Antara OLTP ke DWH)
     */
    public function runEtl(Request $request)
    {
        try {
            DB::unprepared('CALL arkadialp_dwh.JalankanPipaETL()');
            return response()->json([
                'success' => true,
                'message' => 'Pipa ETL Berhasil Dieksekusi!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menjalankan ETL: ' . $e->getMessage()
            ], 500);
        }
    }
}