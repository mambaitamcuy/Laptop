<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Halaman Dashboard Executive (DWH)
     * Menghitung Volume Terjual, Total Transaksi, Omzet, Profit, dan Tren Grafik Bulanan
     */
    public function index(Request $request)
    {
        // Ambil filter wilayah dari dropdown request (default 'all')
        $selectedWilayah = $request->get('wilayah', 'all');

        // 1. QUERY UTAMA UNTUK KARTU INFORMASI (KPI CARD)
        $query = DB::table('arkadialp_dwh.dwh_fact_penjualan');

        if ($selectedWilayah !== 'all') {
            $query->join('arkadialp_dwh.dwh_dim_cabang', 'arkadialp_dwh.dwh_fact_penjualan.id_dim_cabang', '=', 'arkadialp_dwh.dwh_dim_cabang.id_dim_cabang')
                ->where(function($q) use ($selectedWilayah) {
                    $q->where('arkadialp_dwh.dwh_dim_cabang.kota', $selectedWilayah)
                        ->orWhere('arkadialp_dwh.dwh_dim_cabang.nama_cabang', 'LIKE', '%' . $selectedWilayah . '%');
                });
        }

        // Kloning query KPI dengan nama tabel eksplisit agar terhindar dari krisis kolisi/ambigu
        $totalVolume    = (clone $query)->sum('arkadialp_dwh.dwh_fact_penjualan.qty') ?: 0;
        $totalTransaksi = (clone $query)->count('arkadialp_dwh.dwh_fact_penjualan.id_fact') ?: 0;
        $totalOmzet     = (clone $query)->sum('arkadialp_dwh.dwh_fact_penjualan.subtotal') ?: 0;
        $totalProfit    = (clone $query)->sum('arkadialp_dwh.dwh_fact_penjualan.profit') ?: 0;

        // 🔥 PERBAIKAN 1: Bungkus variabel ke dalam object $metrics agar terbaca oleh file Blade
        $metrics = (object) [
            'total_volume' => $totalVolume,
            'total_rows'   => $totalTransaksi,
            'total_gross'  => $totalOmzet,
            'total_profit' => $totalProfit
        ];

        // 2. QUERY UNTUK GRAFIK TREN BULANAN
        $trendQuery = DB::table('arkadialp_dwh.dwh_fact_penjualan')
            ->join('arkadialp_dwh.dwh_dim_waktu', 'arkadialp_dwh.dwh_fact_penjualan.id_waktu', '=', 'arkadialp_dwh.dwh_dim_waktu.id_waktu');

        if ($selectedWilayah !== 'all') {
            $trendQuery->join('arkadialp_dwh.dwh_dim_cabang', 'arkadialp_dwh.dwh_fact_penjualan.id_dim_cabang', '=', 'arkadialp_dwh.dwh_dim_cabang.id_dim_cabang')
                ->where(function($q) use ($selectedWilayah) {
                    $q->where('arkadialp_dwh.dwh_dim_cabang.kota', $selectedWilayah)
                        ->orWhere('arkadialp_dwh.dwh_dim_cabang.nama_cabang', 'LIKE', '%' . $selectedWilayah . '%');
                });
        }

        $trendData = $trendQuery->select(
                'arkadialp_dwh.dwh_dim_waktu.tahun',
                'arkadialp_dwh.dwh_dim_waktu.bulan',
                'arkadialp_dwh.dwh_dim_waktu.nama_bulan',
                DB::raw('SUM(arkadialp_dwh.dwh_fact_penjualan.subtotal) as total_omzet'),
                DB::raw('SUM(arkadialp_dwh.dwh_fact_penjualan.profit) as total_profit')
            )
            ->groupBy('arkadialp_dwh.dwh_dim_waktu.tahun', 'arkadialp_dwh.dwh_dim_waktu.bulan', 'arkadialp_dwh.dwh_dim_waktu.nama_bulan')
            ->orderBy('arkadialp_dwh.dwh_dim_waktu.tahun', 'asc')
            ->orderBy('arkadialp_dwh.dwh_dim_waktu.bulan', 'asc')
            ->get();

        $chartLabels = $trendData->map(fn($item) => $item->nama_bulan . ' ' . $item->tahun)->toArray();
        $chartOmzet  = $trendData->pluck('total_omzet')->toArray();
        $chartProfit = $trendData->pluck('total_profit')->toArray();
        
        // Diarahkan ke profit agar grafik garisnya singkron dengan judul "Tren Keuntungan Bersih Murni"
        $chartValues = $chartProfit; 

        $daftarCabang = DB::table('arkadialp_dwh.dwh_dim_cabang')->select('kota')->distinct()->get();
        
        // 🔥 PERBAIKAN 2: Menggunakan format 'd M Y' agar nama bulan tidak rusak diterjemahkan PHP
        $syncTime = Carbon::now('Asia/Makassar')->format('d M Y | H:i:s') . ' WITA';

        return view('pages.dwh.dashboard', compact(
            'selectedWilayah', 'metrics', 'daftarCabang', 'syncTime', 
            'chartLabels', 'chartOmzet', 'chartProfit', 'chartValues'
        ));
    }

    /**
     * Halaman Analisis Cabang / Wilayah (DWH)
     */
    public function cabang()
    {
        $analisisCabang = DB::table('arkadialp_dwh.dwh_dim_cabang')
            ->leftJoin('arkadialp_dwh.dwh_fact_penjualan', 'arkadialp_dwh.dwh_dim_cabang.id_dim_cabang', '=', 'arkadialp_dwh.dwh_fact_penjualan.id_dim_cabang')
            ->select(
                'arkadialp_dwh.dwh_dim_cabang.id_dim_cabang',
                'arkadialp_dwh.dwh_dim_cabang.nama_cabang',
                'arkadialp_dwh.dwh_dim_cabang.kota',
                DB::raw('IFNULL(SUM(arkadialp_dwh.dwh_fact_penjualan.qty), 0) as total_qty'),
                DB::raw('IFNULL(SUM(arkadialp_dwh.dwh_fact_penjualan.subtotal), 0) as total_omzet'),
                DB::raw('IFNULL(SUM(arkadialp_dwh.dwh_fact_penjualan.profit), 0) as total_profit')
            )
            ->groupBy('arkadialp_dwh.dwh_dim_cabang.id_dim_cabang', 'arkadialp_dwh.dwh_dim_cabang.nama_cabang', 'arkadialp_dwh.dwh_dim_cabang.kota')
            ->paginate(10);

        if (view()->exists('pages.dwh.cabang')) {
            return view('pages.dwh.cabang', compact('analisisCabang'));
        }

        return view('pages.dwh.analisis_wilayah', compact('analisisCabang'));
    }

    /**
     * Alternatif Cadangan Route Analisis Wilayah
     */
    public function analisisWilayah()
    {
        return $this->cabang();
    }

    /**
     * Halaman Tabel Fakta Profit (DWH)
     */
    public function faktaProfit()
    {
        $faktaPenjualan = DB::table('arkadialp_dwh.dwh_fact_penjualan')
            ->join('arkadialp_dwh.dwh_dim_produk', 'arkadialp_dwh.dwh_fact_penjualan.id_dim_produk', '=', 'arkadialp_dwh.dwh_dim_produk.id_dim_produk')
            ->join('arkadialp_dwh.dwh_dim_cabang', 'arkadialp_dwh.dwh_fact_penjualan.id_dim_cabang', '=', 'arkadialp_dwh.dwh_dim_cabang.id_dim_cabang')
            ->select(
                'arkadialp_dwh.dwh_fact_penjualan.*', 
                'arkadialp_dwh.dwh_dim_produk.nama_produk', 
                'arkadialp_dwh.dwh_dim_produk.merek', 
                'arkadialp_dwh.dwh_dim_cabang.nama_cabang'
            )
            ->orderBy('id_fact', 'desc')
            ->paginate(15);

        return view('pages.dwh.fakta_profit', compact('faktaPenjualan'));
    }

    /**
     * Halaman Tabel Ringkasan Profit Bulanan
     */
    public function profit()
    {
        $daftarProfit = DB::table('arkadialp_dwh.dwh_fact_penjualan')
            ->join('arkadialp_dwh.dwh_dim_waktu', 'arkadialp_dwh.dwh_fact_penjualan.id_waktu', '=', 'arkadialp_dwh.dwh_dim_waktu.id_waktu')
            ->select(
                'arkadialp_dwh.dwh_dim_waktu.tahun',
                'arkadialp_dwh.dwh_dim_waktu.bulan',
                'arkadialp_dwh.dwh_dim_waktu.nama_bulan',
                DB::raw('SUM(arkadialp_dwh.dwh_fact_penjualan.qty) as total_unit'),
                DB::raw('SUM(arkadialp_dwh.dwh_fact_penjualan.subtotal) as total_pendapatan'),
                DB::raw('SUM(arkadialp_dwh.dwh_fact_penjualan.profit) as total_keuntungan')
            )
            ->groupBy('arkadialp_dwh.dwh_dim_waktu.tahun', 'arkadialp_dwh.dwh_dim_waktu.bulan', 'arkadialp_dwh.dwh_dim_waktu.nama_bulan')
            ->orderBy('arkadialp_dwh.dwh_dim_waktu.tahun', 'desc')
            ->orderBy('arkadialp_dwh.dwh_dim_waktu.bulan', 'desc')
            ->paginate(12);

        return view('pages.dwh.profit', compact('daftarProfit'));
    }

    /**
     * Halaman Laporan Jalur ETL & Eksekusi Stored Procedure
     */
    public function etlReport(Request $request)
    {
        if ($request->has('execute')) {
            try {
                DB::unprepared('CALL arkadialp_dwh.JalankanPipaETL()');
                return redirect()->route('dwh.etl-report')->with('success', 'Pipa ETL Berhasil Dieksekusi! Data OLTP sukses ditransformasikan ke DWH.');
            } catch (\Exception $e) {
                return redirect()->route('dwh.etl-report')->with('error', 'Gagal mengeksekusi ETL: ' . $e->getMessage());
            }
        }

        $sampleFactData = DB::table('arkadialp_dwh.dwh_fact_penjualan')
            ->orderBy('id_fact', 'desc')
            ->limit(5)
            ->get();

        $syncTime = Carbon::now('Asia/Makassar')->format('d M Y | H:i:s') . ' WITA';

        return view('pages.dwh.etl_report', compact('sampleFactData', 'syncTime'));
    }

    /**
     * AJAX REALTIME: Mengeksekusi Stored Procedure JalankanPipaETL via Tombol Dashboard
     */
    public function runEtl()
    {
        try {
            DB::statement('CALL arkadialp_dwh.JalankanPipaETL()');

            return response()->json([
                'success' => true,
                'message' => 'Semua data terbaru harian toko sukses di-load ke Dashboard DWH & Operasional!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengeksekusi ETL: ' . $e->getMessage()
            ], 500);
        }
    }
}