@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-4">
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 pb-3 border-bottom border-slate-800" style="border-color: #334155 !important;">
        <div>
            <span class="text-muted uppercase font-weight-bold" style="font-size: 11px; letter-spacing: 1px;">Halaman: <span class="text-primary">Dashboard Operasional OLTP</span></span>
            <h2 class="text-white font-weight-bold m-0 mt-1" style="font-size: 24px; letter-spacing: -0.5px;">Manajemen Inventori & Kasir</h2>
        </div>
        <div class="mt-3 mt-md-0 d-flex align-items-center style-header-actions" style="gap: 12px;">
            <div class="px-3 py-2 text-white d-flex align-items-center" style="background-color: #1c2541; border-radius: 8px; border: 1px solid #334155; font-size: 12.5px;">
                <i class="fas fa-clock text-warning mr-2 animate-pulse"></i>
                <span class="text-muted mr-1">Sistem Diperbarui:</span> 
                <span class="font-weight-bold">{{ $syncTime }}</span>
            </div>
            
            <form action="" method="GET" class="m-0">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text border-0 text-muted" style="background-color: #1c2541; border-radius: 8px 0 0 8px; border: 1px solid #334155; border-right: none; font-size: 12.5px;">
                            <i class="fas fa-map-marker-alt text-primary mr-1"></i> Wilayah:
                        </span>
                    </div>
                    <select name="wilayah" onchange="this.form.submit()" class="form-control border-0 text-white font-weight-bold" style="background-color: #1c2541; border-radius: 0 8px 8px 0; border: 1px solid #334155; font-size: 13px; min-width: 150px; cursor: pointer;">
                        <option value="all" {{ ($selectedWilayah ?? '') == 'all' ? 'selected' : '' }}>Semua Cabang</option>
                        <option value="1" {{ ($selectedWilayah ?? '') == '1' ? 'selected' : '' }}>Cabang Palu</option>
                        <option value="2" {{ ($selectedWilayah ?? '') == '2' ? 'selected' : '' }}>Cabang Donggala</option>
                        <option value="3" {{ ($selectedWilayah ?? '') == '3' ? 'selected' : '' }}>Cabang Parigi</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100" style="background-color: #1c2541; border-radius: 12px; border: 1px solid #334155 !important;">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div>
                        <small class="text-muted font-weight-bold text-uppercase d-block mb-1" style="font-size: 11px; letter-spacing: 0.5px;">Total Stok Fisik (Gross)</small>
                        <h3 class="text-white font-weight-bold my-2" style="font-size: 28px;">{{ number_format($totalStok, 0, ',', '.') }} <span class="text-muted" style="font-size: 14px; font-weight: 500;">Unit</span></h3>
                    </div>
                    <div class="d-flex align-items-center mt-3 text-primary" style="font-size: 12px; font-weight: 500;">
                        <i class="fas fa-warehouse mr-1.5"></i> Dari database inventori gudang
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100" style="background-color: #1c2541; border-radius: 12px; border: 1px solid #334155 !important;">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div>
                        <small class="text-muted font-weight-bold text-uppercase d-block mb-1" style="font-size: 11px; letter-spacing: 0.5px;">Total Transaksi Terproses</small>
                        <h3 class="text-emerald-400 font-weight-bold my-2" style="font-size: 28px; color: #10b981 !important;">{{ number_format($totalTransaksi, 0, ',', '.') }} <span class="text-muted" style="font-size: 14px; font-weight: 500;">Rows</span></h3>
                    </div>
                    <div class="d-flex align-items-center mt-3 text-muted" style="font-size: 12px; font-weight: 500;">
                        <i class="fas fa-cash-register mr-1.5 text-muted"></i> Log aktivitas kasir aktif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100" style="background-color: #1c2541; border-radius: 12px; border: 1px solid #334155 !important;">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div>
                        <small class="text-muted font-weight-bold text-uppercase d-block mb-1" style="font-size: 11px; letter-spacing: 0.5px;">Koneksi Engine Database</small>
                        <h3 class="text-white font-weight-bold my-2" style="font-size: 25px; letter-spacing: -0.5px;">Connected</h3>
                    </div>
                    <div class="d-flex align-items-center mt-3 text-success" style="font-size: 12px; font-weight: 500; color: #10b981 !important;">
                        <i class="fas fa-check-circle mr-1.5"></i> Instance arkadialp_oltp aktif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100" style="background-color: #1c2541; border-radius: 12px; border: 1px solid #334155 !important;">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div>
                        <small class="text-muted font-weight-bold text-uppercase d-block mb-1" style="font-size: 11px; letter-spacing: 0.5px;">Kesiapan Pipa Gudang Data</small>
                        <h3 class="text-warning font-weight-bold my-2" style="font-size: 26px; color: #f59e0b !important;">Ready</h3>
                    </div>
                    <div class="d-flex align-items-center mt-3 text-muted" style="font-size: 12px; font-weight: 500;">
                        <i class="fas fa-stream mr-1.5"></i> Saluran ETL siap digunakan
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        
        <div class="col-lg-8 pr-lg-3 mb-4">
            <div class="d-flex flex-column" style="gap: 24px;">
                
                <div class="card border-0 shadow-sm" style="background-color: #1c2541; border-radius: 12px; border: 1px solid #334155 !important;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="text-white font-weight-bold m-0" style="font-size: 16px;">
                                <i class="fas fa-chart-area mr-2 text-primary"></i>Tren Volume Transaksi Masuk (Hari Ini)
                            </h5>
                            <span class="badge badge-pill px-3 py-1.5" style="background: rgba(59, 130, 246, 0.15); color: #3b82f6; font-size: 11px; font-weight: 600;">
                                <i class="fas fa-sync-alt fa-spin mr-1"></i> Live Real-Time
                            </span>
                        </div>
                        
                        <div class="position-relative" style="height: 230px; border-radius: 8px;">
                            <canvas id="realtimeOltpChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm" style="background-color: #1c2541; border-radius: 12px; border: 1px solid #334155 !important;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="text-white font-weight-bold m-0" style="font-size: 16px;">
                                <i class="fas fa-history mr-2 text-success"></i>Aktivitas Kasir Terkini
                            </h5>
                            <a href="{{ route('oltp.transaksi') }}" class="btn btn-sm px-3 text-primary font-weight-bold" style="background: rgba(59, 130, 246, 0.1); border-radius: 6px; font-size: 12px;">
                                Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-borderless m-0 text-muted" style="font-size: 13.5px;">
                                <thead style="background: rgba(11, 19, 41, 0.5); color: #f8fafc; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">
                                    <tr>
                                        <th class="p-3" style="border-radius: 6px 0 0 6px;">ID Transaksi</th>
                                        <th class="p-3">Nama Kasir</th>
                                        <th class="p-3">Metode</th>
                                        <th class="p-3">Total Pembayaran</th>
                                        <th class="p-3" style="border-radius: 0 6px 6px 0; text-align: right;">Waktu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr style="border-bottom: 1px solid rgba(51, 65, 85, 0.4);">
                                        <td class="p-3 font-weight-bold text-white">#TRX-98210</td>
                                        <td class="p-3 text-white">Admin Gudang Alpha</td>
                                        <td class="p-3"><span class="badge badge-secondary px-2 py-1" style="background: #334155; color: #94a3b8;">QRIS</span></td>
                                        <td class="p-3 font-weight-bold text-success" style="color: #10b981 !important;">Rp 14.500.000</td>
                                        <td class="p-3 text-right text-muted" style="font-size: 12px;">10 menit lalu</td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid rgba(51, 65, 85, 0.4);">
                                        <td class="p-3 font-weight-bold text-white">#TRX-98209</td>
                                        <td class="p-3 text-white">Kasir Utama Toko</td>
                                        <td class="p-3"><span class="badge badge-secondary px-2 py-1" style="background: #334155; color: #94a3b8;">Transfer</span></td>
                                        <td class="p-3 font-weight-bold text-success" style="color: #10b981 !important;">Rp 8.200.000</td>
                                        <td class="p-3 text-right text-muted" style="font-size: 12px;">24 menit lalu</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-lg-4 pl-lg-3 mb-4">
            <div class="d-flex flex-column" style="gap: 24px;">
                
                <div class="card border-0 shadow-sm" style="background-color: #1c2541; border-radius: 12px; border: 1px solid #334155 !important;">
                    <div class="card-body p-4">
                        <h5 class="text-warning font-weight-bold mb-1" style="font-size: 16px; color: #f59e0b !important;">
                            <i class="fas fa-exclamation-triangle mr-2"></i>Peringatan Stok Kritis
                        </h5>
                        <p class="text-muted mb-3" style="font-size: 11.5px;">Tipe laptop di bawah ini wajib segera dipesan ulang:</p>
                        
                        <div class="d-flex flex-column" style="gap: 10px;">
                            <div class="d-flex justify-content-between align-items-center p-3" style="background: rgba(11, 19, 41, 0.4); border-radius: 8px; border: 1px solid #334155;">
                                <div>
                                    <span class="d-block text-white font-weight-bold" style="font-size: 13.5px;">Arkadia Phantom X</span>
                                    <small class="text-muted">Intel Core i7 / 16GB</small>
                                </div>
                                <span class="badge px-2 py-1.5 font-weight-bold" style="background: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); font-size: 12px;">
                                    2 Unit
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center p-3" style="background: rgba(11, 19, 41, 0.4); border-radius: 8px; border: 1px solid #334155;">
                                <div>
                                    <span class="d-block text-white font-weight-bold" style="font-size: 13.5px;">Arkadia SlimBook 14</span>
                                    <small class="text-muted">AMD Ryzen 5 / 8GB</small>
                                </div>
                                <span class="badge px-2 py-1.5 font-weight-bold" style="background: rgba(245, 158, 11, 0.15); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.2); font-size: 12px;">
                                    4 Unit
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm" style="background-color: #1c2541; border-radius: 12px; border: 1px solid #334155 !important;">
                    <div class="card-body p-4">
                        <h5 class="text-white font-weight-bold mb-3" style="font-size: 16px;">
                            <i class="fas fa-wallet mr-2 text-info"></i>Metode Pembayaran Populer
                        </h5>
                        
                        <div style="height: 175px; border-radius: 8px;">
                            <canvas id="paymentMethodChart"></canvas>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    
    // 1. KONFIGURASI LINE CHART REALTIME (JAM-JAMAN KASIR)
    const ctxOltp = document.getElementById('realtimeOltpChart').getContext('2d');
    const lineGradient = ctxOltp.createLinearGradient(0, 0, 0, 230);
    lineGradient.addColorStop(0, 'rgba(59, 130, 246, 0.35)'); // Warna gradasi biru tema asli Anda
    lineGradient.addColorStop(1, 'rgba(59, 130, 246, 0.00)');

    new Chart(ctxOltp, {
        type: 'line',
        data: {
            labels: {!! json_encode($hourlyLabels ?? []) !!},
            datasets: [{
                label: 'Transaksi Masuk',
                data: {!! json_encode($hourlyValues ?? []) !!},
                borderColor: '#3b82f6', // Menyesuaikan warna utama teks halaman Anda
                borderWidth: 2.5,
                pointBackgroundColor: '#60a5fa',
                pointHoverRadius: 5,
                backgroundColor: lineGradient,
                fill: true,
                tension: 0.35
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#94a3b8', font: { size: 10 } }
                },
                y: {
                    grid: { color: 'rgba(51, 65, 85, 0.2)' },
                    ticks: { color: '#94a3b8', font: { size: 11 } },
                    min: 0
                }
            }
        }
    });

    // 2. KONFIGURASI DONUT CHART (METODE PEMBAYARAN POPULER)
    const ctxDonut = document.getElementById('paymentMethodChart').getContext('2d');
    new Chart(ctxDonut, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($paymentLabels ?? []) !!},
            datasets: [{
                data: {!! json_encode($paymentValues ?? []) !!},
                backgroundColor: ['#3b82f6', '#10b981', '#f59e0b'], // Sinkron dengan warna card (Biru, Emerald, Amber)
                borderWidth: 2,
                borderColor: '#1c2541', // Menyesuaikan warna dasar kartu Anda
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: '#94a3b8', font: { size: 11 }, padding: 10 }
                }
            },
            cutout: '72%'
        }
    });
});
</script>
@endsection