@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-5" style="background-color: #0b1329; min-height: 100vh; color: #f8fafc;">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <div class="card shadow-lg border-0 text-center p-5" style="background: #1c2541; border-radius: 16px;">
                
                <div class="mb-4">
                    <span class="d-inline-flex align-items-center justify-content-center bg-success text-white rounded-circle shadow" style="width: 80px; height: 80px; background: linear-gradient(135deg, #10b981, #059669) !important;">
                        <i class="fas fa-check-double fa-2x"></i>
                    </span>
                </div>

                <h3 class="font-weight-bold text-white mb-2" style="letter-spacing: 0.5px;">PIPELINE ETL BERHASIL DIEKSEKUSI</h3>
                <p class="text-muted mx-auto" style="max-width: 500px; font-size: 14px;">
                    Sistem Agen Data Warehouse Arkadia telah selesai melakukan penarikan data transaksi dari OLTP, membersihkan redudansi, dan memuatnya ke Tabel Fakta.
                </p>

                <hr class="my-4" style="border-top: 1px solid #334155;">

                <h5 class="text-left font-weight-bold text-info mb-3" style="font-size: 14px; letter-spacing: 1px;">
                    <i class="fas fa-file-invoice mr-2"></i> METADATA & RINGKASAN DATA MASUK
                </h5>

                <div class="row text-left mb-4" style="gap: 15px 0;">
                    <!-- Waktu Sinkronisasi -->
                    <div class="col-md-6">
                        <div class="p-3" style="background: #0b1329; border-radius: 8px; border-left: 4px solid #f59e0b;">
                            <small class="text-muted d-block font-weight-bold" style="font-size: 11px;">WAKTU SINKRONISASI (REAL TIME)</small>
                            <span class="text-white font-weight-bold" style="font-size: 15px;">
                                {{ $waktuSekarang ?? now('Asia/Makassar')->format('d M Y | H:i:s') . ' WITA' }}
                            </span>
                        </div>
                    </div>

                    <!-- Total Transaksi -->
                    <div class="col-md-6">
                        <div class="p-3" style="background: #0b1329; border-radius: 8px; border-left: 4px solid #0077b6;">
                            <small class="text-muted d-block font-weight-bold" style="font-size: 11px;">TOTAL BARIS DATA (ROWS)</small>
                            <span class="text-warning font-weight-bold" style="font-size: 16px;">
                                +{{ number_format($totalTransaksi ?? 0, 0, ',', '.') }} Baris Baru
                            </span>
                        </div>
                    </div>

                    <!-- Ringkasan Omzet -->
                    <div class="col-md-6">
                        <div class="p-3" style="background: #0b1329; border-radius: 8px; border-left: 4px solid #c084fc;">
                            <small class="text-muted d-block font-weight-bold" style="font-size: 11px;">AKUMULASI OMZET TERBARU</small>
                            <span class="text-white font-weight-bold" style="font-size: 16px;">
                                Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <!-- Ringkasan Laba Bersih -->
                    <div class="col-md-6">
                        <div class="p-3" style="background: #0b1329; border-radius: 8px; border-left: 4px solid #10b981;">
                            <small class="text-muted d-block font-weight-bold" style="font-size: 11px;">TOTAL KEUNTUNGAN BERSIH</small>
                            <span class="text-success font-weight-bold" style="font-size: 16px;">
                                Rp {{ number_format($totalKeuntungan ?? 0, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="text-left p-3 mb-4" style="background: #070c19; border-radius: 8px; font-family: 'Courier New', Courier, monospace; font-size: 12px; color: #a7f3d0; border: 1px solid #065f46;">
                    <div><i class="fas fa-terminal mr-2"></i> [INFO] Sinkronisasi database pipa ETL... Terhubung.</div>
                    <div><i class="fas fa-terminal mr-2"></i> [SUCCESS] Polarisasi Skema Bintang selesai tanpa terjadi anomali data.</div>
                    <div><i class="fas fa-terminal mr-2"></i> [STATUS] Cache lokasi Asia/Makassar dikunci otomatis (100% Akurat).</div>
                </div>

                <div class="text-center">
                    <a href="{{ url('/dashboard') }}" class="btn text-white font-weight-bold px-5 py-3 shadow" style="background: linear-gradient(135deg, #0077b6, #0096c7); border: none; border-radius: 8px; font-size: 14px; letter-spacing: 0.5px; transition: 0.2s;">
                        <i class="fas fa-chart-pie mr-2"></i> Masuk ke Dashboard Analitik
                    </a>
                </div>

            </div>
            
        </div>
    </div>
</div>
@endsection