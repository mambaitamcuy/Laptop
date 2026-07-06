<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan; 
use Illuminate\Pagination\LengthAwarePaginator;

class DashboardController extends Controller
{
    /**
     * 1. DASHBOARD ANALITIK (DWH) - UTAMA
     * URL: /dwh/dashboard
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'pusat') {
            abort(403, 'Akses Ditolak!');
        }

        // Ambil input filter wilayah (default 'all' agar sinkron dengan blade)
        $selectedWilayah = $request->input('wilayah', 'all');

        try {
            // A. HITUNG METRICS UNTUK 4 KOTAK RINGKASAN DI ATAS
            // PERBAIKAN: Menghapus prefix 'arkadialp_dwh.' agar fleksibel di Lokal & Railway
            $metricsQuery = DB::connection('mysql_dwh')->table('dwh_fact_penjualan')
                ->join('dwh_dim_cabang', 'dwh_fact_penjualan.id_dim_cabang', '=', 'dwh_dim_cabang.id_dim_cabang');

            // Jika memilih cabang spesifik, lakukan filter berdasarkan nama cabang
            if ($selectedWilayah !== 'all') {
                $metricsQuery->where('dwh_dim_cabang.nama_cabang', $selectedWilayah);
            }

            // Agregasi query secara berkala menggunakan SQL murni
            $metrics = $metricsQuery->selectRaw('
                IFNULL(SUM(subtotal), 0) as total_gross,
                IFNULL(SUM(profit), 0) as total_profit,
                IFNULL(SUM(qty), 0) as total_volume,
                COUNT(id_penjualan) as total_rows
            ')->first();

            // B. AMBIL DATA UNTUK GRAFIK TREND (CHART)
            // PERBAIKAN: Menghapus prefix 'arkadialp_dwh.'
            $queryChart = DB::connection('mysql_dwh')->table('dwh_analytics_views');
            if ($selectedWilayah !== 'all') {
                $queryChart->where('cabang', $selectedWilayah);
            }
            $analyticsData = $queryChart->get();

        } catch (\Exception $e) {
            // Safety Fallback jika skema database DWH kosong atau belum migrasi
            $metrics = (object) [
                'total_gross' => 0,
                'total_profit' => 0,
                'total_volume' => 0,
                'total_rows' => 0
            ];
            $analyticsData = collect([]);
        }

        // Ekstrak data array untuk di-render oleh Chart.js di Blade
        $chartLabels = $analyticsData->pluck('cabang')->toArray();
        $chartValues = $analyticsData->pluck('total_pendapatan')->toArray();
        
        $syncTime = now()->timezone('Asia/Makassar')->format('d M Y H:i:s') . ' WITA';

        return view('pages.dwh.dashboard', compact(
            'analyticsData', 
            'metrics', 
            'syncTime', 
            'selectedWilayah', 
            'chartLabels', 
            'chartValues'
        ));
    }

    /**
     * 2. HALAMAN LAPORAN PROFIT MARGIN (DWH)
     * URL: /dwh/profit
     */
    public function profit(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'pusat') {
            abort(403, 'Akses Ditolak!');
        }

        $selectedWilayah = $request->input('wilayah', 'all');

        try {
            // PERBAIKAN: Menghapus prefix 'arkadialp_dwh.'
            $query = DB::connection('mysql_dwh')->table('dwh_analytics_views');
            if ($selectedWilayah !== 'all') {
                $query->where('cabang', $selectedWilayah);
            }
            $daftarProfit = $query->paginate(10);
            $chartLabels = $daftarProfit->pluck('cabang')->toArray();
            $chartValues = $daftarProfit->pluck('profit_margin')->toArray();
        } catch (\Exception $e) {
            $daftarProfit = new LengthAwarePaginator([], 0, 10);
            $chartLabels = [];
            $chartValues = [];
        }

        $syncTime = now()->timezone('Asia/Makassar')->format('d M Y H:i:s') . ' WITA';

        return view('pages.dwh.profit', compact('daftarProfit', 'syncTime', 'selectedWilayah', 'chartLabels', 'chartValues'));
    }

    /**
     * 3. HALAMAN LAPORAN PER CABANG (DWH)
     * URL: /dwh/cabang
     */
    public function cabang(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'pusat') {
            abort(403, 'Akses Ditolak!');
        }

        $selectedWilayah = $request->input('wilayah', 'all');

        try {
            // PERBAIKAN: Menghapus prefix 'arkadialp_dwh.'
            $query = DB::connection('mysql_dwh')->table('dwh_analytics_views');
            if ($selectedWilayah !== 'all') {
                $query->where('cabang', $selectedWilayah);
            }
            $analisisCabang = $query->paginate(10);
            $chartLabels = $analisisCabang->pluck('cabang')->toArray();
            $chartValues = $analisisCabang->pluck('total_pendapatan')->toArray();
        } catch (\Exception $e) {
            $analisisCabang = new LengthAwarePaginator([], 0, 10);
            $chartLabels = [];
            $chartValues = [];
        }

        $syncTime = now()->timezone('Asia/Makassar')->format('d M Y H:i:s') . ' WITA';

        return view('pages.dwh.cabang', compact('analisisCabang', 'syncTime', 'selectedWilayah', 'chartLabels', 'chartValues'));
    }

    /**
     * 4. HALAMAN REPORT ETL LOGS
     * URL: /dwh/etl-report
     */
    public function etlReport()
    {
        $syncTime = now()->timezone('Asia/Makassar')->format('d M Y H:i:s') . ' WITA';
        return view('pages.dwh.etl-report', compact('syncTime'));
    }

    /**
     * 5. EKSKUSI PIPA ETL VIA WEB (Mengembalikan Respon JSON untuk JavaScript Fetch API)
     * URL: /dwh/run-etl
     */
    public function runEtl(Request $request)
    {
        try {
            // Memicu jalannya Artisan Command arkadia:etl yang telah dibuat
            Artisan::call('arkadia:etl');

            return response()->json([
                'success' => true,
                'message' => 'Pipa ETL Berhasil Dijalankan! Data Warehouse berhasil disinkronkan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menjalankan ETL: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 6. DASHBOARD OPERASIONAL (OLTP)
     * URL: /oltp/dashboard
     */
    public function indexOLTP()
    {
        $user = Auth::user();
        $branchMap = [1 => 'Parigi', 2 => 'Palu', 3 => 'Donggala'];

        if ($user->role === 'pusat') {
            $dataLaptop = DB::connection('mysql')->table('laptops')->get(); 
            $totalPenjualan = DB::connection('mysql')->table('penjualan')->sum('total_harga');

            return view('pages.oltp.dashboard', compact('dataLaptop', 'totalPenjualan'));
        }

        if ($user->role === 'cabang' || $user->role === 'karyawan') {
            $namaCabangUser = $branchMap[$user->id_cabang] ?? 'Unknown';

            $dataLaptop = DB::connection('mysql')
                            ->table('laptops')
                            ->where('cabang', $namaCabangUser)
                            ->get();

            $totalPenjualan = DB::connection('mysql')
                                ->table('penjualan')
                                ->join('users', 'penjualan.id_user', '=', 'users.id_user')
                                ->where('users.id_cabang', $user->id_cabang)
                                ->sum('penjualan.total_harga');

            return view('pages.oltp.dashboard', compact('dataLaptop', 'totalPenjualan'));
        }

        abort(403, 'Akses Ditolak.');
    }
}