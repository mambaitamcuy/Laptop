<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OltpDashboardController extends Controller
{
    /**
     * 1. HALAMAN UTAMA DASHBOARD OPERASIONAL (OLTP)
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('/login');
        }

        // Proteksi Hak Akses Role
        if ($user->role === 'karyawan') {
            return redirect('/oltp/transaksi')->with('info', 'Selamat bekerja! Anda dialihkan ke halaman Kasir.');
        }

        if ($user->role === 'cabang') {
            return redirect('/oltp/stok')->with('info', 'Selamat bekerja! Anda dialihkan ke halaman Stok.');
        }

        // --- FITUR FILTER WILAYAH CABANG ---
        $selectedWilayah = $request->get('wilayah', 'all');
        
        // Mapping value dropdown ke teks nama cabang di database laptops
        $mappingCabang = [
            '1' => 'Palu',
            '2' => 'Donggala',
            '3' => 'Parigi'
        ];

        // --- AMBIL METRIK UTAMA (DILENGKAPI FILTER) ---
        $queryStok = DB::table('laptops');
        if ($selectedWilayah !== 'all' && isset($mappingCabang[$selectedWilayah])) {
            $queryStok->where('cabang', $mappingCabang[$selectedWilayah]);
        }
        $totalStok = $queryStok->sum('stok') ?? 0;

        $totalTransaksi = DB::table('penjualan')->count();
        
        $queryKaryawan = DB::table('karyawan');
        if ($selectedWilayah !== 'all') {
            $queryKaryawan->where('id_cabang', $selectedWilayah);
        }
        $totalKaryawan = $queryKaryawan->count();
        
        $syncTime = now()->timezone('Asia/Makassar')->format('H:i:s') . ' WITA';

        // --- LOGIKA GRAFIK 1: TREN VOLUME TRANSAKSI HARI INI ($hourlyLabels & $hourlyValues) ---
        $trenPenjualan = DB::table('penjualan')
                            ->whereDate('tanggal', now()->toDateString())
                            ->select(DB::raw('HOUR(created_at) as jam'), DB::raw('COUNT(*) as total_rows'))
                            ->groupBy(DB::raw('HOUR(created_at)'))
                            ->orderBy('jam', 'asc')
                            ->get();

        $hourlyLabels = [];
        $hourlyValues = [];

        foreach ($trenPenjualan as $data) {
            $hourlyLabels[] = sprintf('%02d:00', $data->jam);
            $hourlyValues[] = $data->total_rows;
        }

        // Fallback jika hari ini belum ada transaksi agar grafik tidak kosong/error
        if (empty($hourlyLabels)) {
            $hourlyLabels = ['00:00', '06:00', '12:00', '18:00', now()->format('H:i')];
            $hourlyValues = [0, 0, 0, 0, DB::table('penjualan')->whereDate('tanggal', now()->toDateString())->count()];
        }

        // --- LOGIKA GRAFIK 2: METODE PEMBAYARAN POPULER ($paymentLabels & $paymentValues) ---
        $paymentData = DB::table('penjualan')
                            ->select('metode_pembayaran', DB::raw('COUNT(*) as total'))
                            ->groupBy('metode_pembayaran')
                            ->orderBy('total', 'desc')
                            ->get();

        $paymentLabels = $paymentData->pluck('metode_pembayaran')->toArray();
        $paymentValues = $paymentData->pluck('total')->toArray();

        // --- DATA LAPTOP STOK KRITIS (DILENGKAPI FILTER) ---
        $queryKritis = DB::table('laptops')->where('stok', '<=', 5);
        if ($selectedWilayah !== 'all' && isset($mappingCabang[$selectedWilayah])) {
            $queryKritis->where('cabang', $mappingCabang[$selectedWilayah]);
        }
        $laptopsKritis = $queryKritis->orderBy('stok', 'asc')->get();

        // --- DATA AKTIVITAS KASIR TERKINI (AMBIL 5 DATA TERBARU) ---
        $recentTransactions = DB::table('penjualan')
                                ->leftJoin('users', 'penjualan.id_user', '=', 'users.id_user')
                                ->select('penjualan.*', 'users.name as nama_kasir')
                                ->orderBy('penjualan.id_penjualan', 'desc')
                                ->limit(5)
                                ->get();

        return view('pages.oltp.dashboard', compact(
            'totalStok', 
            'totalTransaksi', 
            'totalKaryawan', 
            'syncTime', 
            'selectedWilayah',
            'hourlyLabels',
            'hourlyValues',
            'paymentLabels',
            'paymentValues',
            'laptopsKritis',
            'recentTransactions'
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
            'cabang'      => 'nullable|string|max:255',
        ]);

        DB::table('laptops')->insert([
            'nama'       => $request->nama_laptop,
            'brand'      => $request->brand,
            'cabang'     => $request->cabang ?? 'Pusat',
            'harga'      => $request->harga,
            'stok'       => $request->stok,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Aset laptop baru berhasil ditambahkan ke database!');
    }

    /**
     * 3b. PROSES UPDATE DATA STOK LAPTOP (EDIT) -> *FUNGSI BARU*
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_laptop' => 'required|string|max:255',
            'brand'       => 'required|string|max:100',
            'stok'        => 'required|integer|min:0',
            'harga'       => 'required|numeric|min:0',
            'cabang'      => 'nullable|string|max:255',
        ]);

        DB::table('laptops')->where('id', $id)->update([
            'nama'       => $request->nama_laptop,
            'brand'      => $request->brand,
            'cabang'     => $request->cabang ?? 'Pusat',
            'harga'      => $request->harga,
            'stok'       => $request->stok,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Data unit laptop berhasil diperbarui!');
    }

    /**
     * 3c. PROSES HAPUS DATA STOK LAPTOP -> *FUNGSI BARU*
     */
    public function destroy($id)
    {
        DB::table('laptops')->where('id', $id)->delete();

        return redirect()->back()->with('success', 'Unit laptop berhasil dihapus dari daftar gudang!');
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
                        ->orderBy('nama', 'asc')
                        ->get();

        return view('pages.oltp.transaksi', compact('daftarTransaksi', 'daftarLaptop'));
    }

    /**
     * 5. PROSES SIMPAN TRANSAKSI PENJUALAN BARU
     */
    public function store(Request $request)
    {
        $request->validate([
            'metode_pembayaran' => 'required|string',
            'total'             => 'required|numeric|min:0',
        ]);

        DB::table('penjualan')->insert([
            'nomor_invoice'     => 'INV-' . date('YmdHis') . '-' . rand(10, 99),
            'id_user'           => auth()->id() ?? 1, 
            'metode_pembayaran' => $request->metode_pembayaran,
            'total_harga'       => $request->total,
            'status_pembayaran' => 'SUCCESS',
            'tanggal'           => now()->toDateString(),
            'created_at'        => now(),
            'updated_at'        => now(),
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
            'id_cabang' => 'nullable|integer',
        ]);

        DB::table('karyawan')->insert([
            'nama'       => $request->nama,
            'email'      => $request->email,
            'jabatan'    => $request->jabatan,
            'id_cabang'  => $request->id_cabang,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Karyawan berhasil didaftarkan!');
    }
}