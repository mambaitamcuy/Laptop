<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Menampilkan Dashboard Analitik DWH
     */
    public function index(Request $request)
    {
        // 1. Ambil input filter cabang
        $selectedCabang = $request->input('cabang', 'all');

        try {
            // 2. Base Query (Menggunakan alias 'fp')
            $baseQuery = DB::table('arkadialp_dwh.dwh_fact_penjualan as fp');
            
            if ($selectedCabang !== 'all') {
                $baseQuery->where('fp.id_dim_cabang', $selectedCabang);
            }

            // 3. Mengambil Metrik Utama (Wajib di-CLONE)
            $totalPendapatan  = (clone $baseQuery)->sum('fp.subtotal') ?? 0;
            $totalKeuntungan  = (clone $baseQuery)->sum('fp.profit') ?? 0;
            $totalUnitTerjual = (clone $baseQuery)->sum('fp.qty') ?? 0; 
            $totalTransaksi   = (clone $baseQuery)->count();
            
            $lastUpdated = now('Asia/Makassar')->format('d M Y | H:i:s') . ' WITA';

            // 4. Data untuk Line Chart (Tren Bulanan)
            // PERBAIKAN: Menuliskan full expression di groupBy & orderBy agar lolos strict mode MySQL
            $dwhBulanan = DB::table('arkadialp_dwh.dwh_fact_penjualan as fp')
                ->join('arkadialp_dwh.dwh_dim_waktu as dw', 'fp.id_waktu', '=', 'dw.id_waktu')
                ->select(
                    DB::raw("SUBSTRING(dw.tanggal, 1, 7) as bulan"), 
                    DB::raw('SUM(fp.profit) as total_keuntungan')
                )
                ->when($selectedCabang !== 'all', function ($q) use ($selectedCabang) {
                    return $q->where('fp.id_dim_cabang', $selectedCabang);
                })
                ->groupBy(DB::raw("SUBSTRING(dw.tanggal, 1, 7)"))
                ->orderBy(DB::raw("SUBSTRING(dw.tanggal, 1, 7)"), 'desc')
                ->limit(6)
                ->get()
                ->reverse();

            // 5. Data untuk Bar Chart (Omzet per Cabang)
            $dwhCabang = DB::table('arkadialp_dwh.dwh_fact_penjualan as fp')
                ->select('fp.id_dim_cabang', DB::raw('SUM(fp.subtotal) as total_omzet'))
                ->groupBy('fp.id_dim_cabang')
                ->get();

        } catch (\Exception $e) {
            // Jika masih ada error lain, bom debug ini akan menangkapnya lagi
            dd("Awas Error Database Baru: " . $e->getMessage(), "File: " . $e->getFile(), "Baris: " . $e->getLine());
        }

        return view('pages.dwh.dashboard', compact(
            'totalPendapatan', 'totalKeuntungan', 'totalUnitTerjual', 
            'totalTransaksi', 'lastUpdated', 'selectedCabang', 
            'dwhBulanan', 'dwhCabang'
        ));
    }

    /**
     * Mengeksekusi Pipa ETL via Tombol di Halaman Web
     */
    public function runEtl(Request $request)
    {
        try {
            DB::unprepared('CALL arkadialp_dwh.JalankanPipaETL()');
            return redirect()->route('dwh.dashboard')->with('success', 'ETL Berhasil!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal ETL: ' . $e->getMessage());
        }
    }
}