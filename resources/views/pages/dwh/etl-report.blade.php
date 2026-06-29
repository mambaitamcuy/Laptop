@extends('layouts.app')

@section('content')
<div class="p-4 p-md-5">
    
    <div class="d-flex align-items-center mb-4">
        <div class="rounded-circle d-flex align-items-center justify-content-center" 
            style="width: 50px; height: 50px; background-color: #1c2541; border: 1px solid #334155;">
            <i class="fas fa-stream text-danger" style="font-size: 18px;"></i>
        </div>
        <div class="ml-3">
            <h2 class="text-white font-weight-bold m-0" style="font-size: 22px; letter-spacing: -0.5px;">Laporan Pipa Data ETL</h2>
            <p class="text-muted m-0" style="font-size: 13.5px;">Status validasi, performa ekstraksi, dan loading data dari OLTP ke Data Warehouse</p>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-12 col-md-4 mb-4">
            <div class="card border-0 p-4 shadow-lg" style="background-color: #1c2541; border: 1px solid #334155 !important; border-radius: 8px;">
                <small class="text-muted font-weight-bold d-block mb-1" style="font-size: 10px; letter-spacing: 0.8px;">STATUS PIPELINE</small>
                <h4 class="text-success font-weight-bold m-0 d-flex align-items-center" style="font-size: 18px; color: #10b981 !important;">
                    <span class="rounded-circle bg-success mr-2 d-inline-block" style="width: 10px; height: 10px; animate: pulse 2s infinite;"></span> IDLE / SUCCESS
                </h4>
            </div>
        </div>
        <div class="col-12 col-md-4 mb-4">
            <div class="card border-0 p-4 shadow-lg" style="background-color: #1c2541; border: 1px solid #334155 !important; border-radius: 8px;">
                <small class="text-muted font-weight-bold d-block mb-1" style="font-size: 10px; letter-spacing: 0.8px;">TOTAL RECORD STRUKTUR FAKTA</small>
                <h4 class="text-white font-weight-bold m-0" style="font-size: 18px;">
                    {{ number_format($totalRows, 0, ',', '.') }} Rows Terintegrasi
                </h4>
            </div>
        </div>
        <div class="col-12 col-md-4 mb-4">
            <div class="card border-0 p-4 shadow-lg" style="background-color: #1c2541; border: 1px solid #334155 !important; border-radius: 8px;">
                <small class="text-muted font-weight-bold d-block mb-1" style="font-size: 10px; letter-spacing: 0.8px;">KECEPATAN RATA-RATA</small>
                <h4 class="text-info font-weight-bold m-0" style="font-size: 18px; color: #00b4d8 !important;">
                    0.42 Detik / Batch
                </h4>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-lg" style="background-color: #1c2541; border: 1px solid #334155 !important; border-radius: 10px; overflow: hidden;">
        <div class="card-header border-0 p-4" style="background-color: rgba(0,0,0,0.15); border-bottom: 1px solid #334155 !important;">
            <div class="text-danger font-weight-bold text-uppercase" style="font-size: 12.5px; letter-spacing: 0.8px;">
                <i class="fas fa-terminal mr-2"></i> Log Eksekusi Server Kronologis
            </div>
        </div>

        <div class="table-responsive">
            <table class="table text-white mb-0" style="font-size: 13.5px;">
                <thead>
                    <tr style="background: rgba(0,0,0,0.25); font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; border-bottom: 1px solid #334155;">
                        <th class="border-0 px-4 py-3">Waktu Job</th>
                        <th class="border-0 px-4 py-3">Nama Task ETL</th>
                        <th class="border-0 px-4 py-3">Sumber Data (OLTP)</th>
                        <th class="border-0 px-4 py-3">Target Warehouse</th>
                        <th class="border-0 px-4 py-3">Baris Terdampak</th>
                        <th class="border-0 px-4 py-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid rgba(51, 65, 85, 0.4);">
                        <td class="px-4 py-3 text-muted align-middle">Hari Ini, 20:00 WITA</td>
                        <td class="px-4 py-3 font-weight-bold text-white align-middle">ETL_Fact_Penjualan_Sync</td>
                        <td class="px-4 py-3 text-muted align-middle"><code class="text-warning">oltp_db.transaksi</code></td>
                        <td class="px-4 py-3 text-muted align-middle"><code class="text-info">dwh_db.dwh_fact_penjualan</code></td>
                        <td class="px-4 py-3 text-white font-weight-bold align-middle">+{{ $totalRows }} Rows</td>
                        <td class="px-4 py-3 align-middle"><span class="badge bg-success text-white px-2.5 py-1">SUCCESS</span></td>
                    </tr>
                    <tr style="border-bottom: 1px solid rgba(51, 65, 85, 0.4);">
                        <td class="px-4 py-3 text-muted align-middle">Hari Ini, 04:00 WITA</td>
                        <td class="px-4 py-3 font-weight-bold text-white align-middle">ETL_Dim_Karyawan_Update</td>
                        <td class="px-4 py-3 text-muted align-middle"><code class="text-warning">oltp_db.karyawan</code></td>
                        <td class="px-4 py-3 text-muted align-middle"><code class="text-info">dwh_db.dwh_dim_karyawan</code></td>
                        <td class="px-4 py-3 text-white align-middle">Muktahir (0)</td>
                        <td class="px-4 py-3 align-middle"><span class="badge bg-success text-white px-2.5 py-1">SUCCESS</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection