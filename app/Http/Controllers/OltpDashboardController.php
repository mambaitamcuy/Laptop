<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OltpDashboardController extends Controller
{
    // Menggunakan koneksi default 'mysql' (Arkadia OLTP)
    protected $koneksi = 'mysql'; 
    
    // Nama tabel transaksi utama di database operasional Anda
    protected $tabelTransaksi = 'penjualan'; 

    public function index(Request $request)
    {
        try {
            // 1. Ambil input filter cabang dari dropdown (default: 'all')
            $selectedCabang = $request->input('cabang', 'all');

            // 2. Siapkan query dasar penjualan & stok agar bisa difilter secara dinamis
            $queryPenjualan = DB::connection($this->koneksi)->table($this->tabelTransaksi);
            $queryStok = DB::connection($this->koneksi)->table('stok_cabang');
            
            // Jika user memilih cabang tertentu, saring query berdasarkan id_cabang
            if ($selectedCabang !== 'all') {
                $queryPenjualan->where('id_cabang', $selectedCabang);
                $queryStok->where('id_cabang', $selectedCabang);
            }

            // --- PROSES HITUNG METRIKS UTAMA (CARD STATISTIK) ---
            
            // A. Hitung Omset Global / Per Cabang
            $totalOmset = (clone $queryPenjualan)->sum('total'); 
            
            // B. Hitung Total Transaksi Real dari Database
            $angkaTransaksiReal = (clone $queryPenjualan)->count(); 

            // 🌟 SINKRONISASI VARIABEL: Isi semua kemungkinan nama variabel termasuk yang ada di Blade kamu
            $totalTransaksiHariIni = $angkaTransaksiReal; // Matches: {{ $totalTransaksiHariIni }}
            $totalTransaksi        = $angkaTransaksiReal;
            $transaksiTerproses    = $angkaTransaksiReal;

            // C. Hitung Total Stok Fisik dari tabel stok_cabang
            $totalStok = $queryStok->sum('jumlah_stok');

            // D. Hitung total item terjual dari tabel detail_penjualan
            $queryDetail = DB::connection($this->koneksi)->table('detail_penjualan');
            if ($selectedCabang !== 'all') {
                $queryDetail->join('penjualan', 'detail_penjualan.id_penjualan', '=', 'penjualan.id_penjualan')
                            ->where('penjualan.id_cabang', $selectedCabang);
                $totalItemTerjual = $queryDetail->sum('detail_penjualan.qty');
            } else {
                $totalItemTerjual = $queryDetail->sum('qty');
            }

            // --- AMBIL DATA UNTUK TABEL DAN GRAFIK ---
            
            // 3. Data 5 Riwayat Transaksi Terakhir
            $transaksiTerbaru = (clone $queryPenjualan)
                ->select('id_penjualan', 'invoice', 'metode_pembayaran', 'total', 'tanggal')
                ->orderBy('tanggal', 'desc')
                ->limit(5)
                ->get();

            // 4. Data Distribusi Metode Pembayaran (Untuk Pie Chart)
            $metodePembayaranData = (clone $queryPenjualan)
                ->select('metode_pembayaran', DB::raw('count(*) as jumlah'))
                ->groupBy('metode_pembayaran')
                ->get();

            // 5. Data Performa Penjualan Per Cabang
            $penjualanCabangQuery = DB::connection($this->koneksi)
                ->table($this->tabelTransaksi)
                ->join('cabang', 'penjualan.id_cabang', '=', 'cabang.id_cabang')
                ->select('cabang.nama_cabang', DB::raw('sum(penjualan.total) as total_omset'), DB::raw('count(*) as jumlah_transaksi'))
                ->groupBy('cabang.nama_cabang', 'cabang.id_cabang');
                
            if ($selectedCabang !== 'all') {
                $penjualanCabangQuery->where('penjualan.id_cabang', $selectedCabang);
            }
            $penjualanCabang = $penjualanCabangQuery->orderBy('total_omset', 'desc')->get();

            // 6. Tren Bulanan Omset (Untuk Line Chart)
            $trenBulanan = (clone $queryPenjualan)
                ->select(
                    DB::raw("DATE_FORMAT(tanggal, '%Y-%m') as bulan"),
                    DB::raw('sum(total) as omset_bulanan')
                )
                ->groupBy(DB::raw("DATE_FORMAT(tanggal, '%Y-%m')"))
                ->orderBy('bulan', 'asc')
                ->limit(12)
                ->get();

            // 7. Ambil daftar semua cabang untuk Dropdown Filter
            $daftarCabang = DB::connection($this->koneksi)->table('cabang')->get();

            // Waktu pembaruan sistem (WITA)
            $lastUpdated = Carbon::now('Asia/Makassar')->format('d M Y | H:i:s') . ' WITA';

            // Kirim semua variabel ke view Blade
            return view('pages.oltp.dashboard', compact(
                'totalOmset',
                'totalStok',
                'totalItemTerjual',
                'transaksiTerbaru',
                'metodePembayaranData',
                'penjualanCabang',
                'trenBulanan',
                'lastUpdated',
                'selectedCabang',
                'daftarCabang',
                'totalTransaksi',
                'transaksiTerproses',
                'totalTransaksiHariIni' // 🟢 Variabel penentu agar tidak 0 rows lagi
            ));

        } catch (\Exception $e) {
            // Detektor error query jika ada kendala database
            dd([
                'Pesan Error' => $e->getMessage(),
                'File' => $e->getFile(),
                'Baris' => $e->getLine()
            ]);
        }
    }
}