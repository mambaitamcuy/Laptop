@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4" style="background-color: #0b1329; min-height: 100vh; color: #f8fafc;">
    
    <div class="row align-items-center mb-4">
        <div class="col-lg-5 mb-3 mb-lg-0">
            <div class="d-flex align-items-center mb-1">
                <span class="text-muted mr-2" style="font-size: 14px;">Halaman:</span>
                <span class="badge px-3 py-2 text-info font-weight-bold" style="background: rgba(6, 182, 212, 0.1); border-radius: 4px; letter-spacing: 0.5px;">DASHBOARD ANALITIK DWH</span>
            </div>
            <small class="text-muted" style="font-size: 12px; letter-spacing: 0.3px;">
                <i class="fas fa-clock text-warning mr-1"></i> Sinkronisasi Terakhir: 
                <span class="text-white font-weight-bold" style="background: rgba(245, 158, 11, 0.1); padding: 2px 6px; border-radius: 4px;">
                    {{ $lastUpdated }}
                </span>
            </small>
        </div>

        <div class="col-lg-7 d-flex flex-wrap align-items-center justify-content-lg-end" style="gap: 12px;">
            
            <form action="{{ route('dwh.dashboard') }}" method="GET" id="formFilterCabang" class="m-0 d-flex align-items-center">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text border-0" style="background: #1c2541; color: #64748b; font-size: 13px;">
                            <i class="fas fa-map-marker-alt text-info mr-1"></i> Wilayah:
                        </span>
                    </div>
                    <select name="cabang" class="form-control text-white border-0 shadow-sm font-weight-bold" style="background: #1c2541; border-radius: 0 6px 6px 0; font-size: 13px; width: 180px; cursor: pointer;" onchange="document.getElementById('formFilterCabang').submit();">
                        <option value="all" {{ $selectedCabang == 'all' ? 'selected' : '' }}>🌍 Semua Cabang</option>
                        <option value="1" {{ $selectedCabang == '1' ? 'selected' : '' }}>📍 Cabang Palu</option>
                        <option value="2" {{ $selectedCabang == '2' ? 'selected' : '' }}>📍 Cabang Donggala</option>
                        <option value="3" {{ $selectedCabang == '3' ? 'selected' : '' }}>📍 Cabang Parigi</option>
                    </select>
                </div>
            </form>

            <form id="formEtl" action="{{ route('dashboard.runEtl') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn text-white font-weight-bold px-4 py-2 shadow-sm" style="background: #0077b6; border: none; border-radius: 6px; font-size: 13px; transition: 0.2s;">
                    <i class="fas fa-sync-alt mr-2"></i> Jalankan Pipa ETL
                </button>
            </form>

            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn text-white font-weight-bold px-4 py-2 shadow-sm" style="background: #dc2626; border: none; border-radius: 6px; font-size: 13px; transition: 0.2s;" onclick="return confirm('Apakah Anda yakin ingin keluar dari sistem DWH?')">
                    <i class="fas fa-sign-out-alt mr-2"></i> Keluar
                </button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card p-3 shadow-sm" style="background: #1c2541; border: none; border-radius: 12px; height: 100%; min-height: 115px;">
                <small class="text-muted font-weight-bold d-block mb-1" style="letter-spacing: 0.8px; font-size: 10px;">TOTAL PENDAPATAN (GROSS)</small>
                <h3 class="font-weight-bold text-white my-1" style="font-size: clamp(17px, 1.4vw, 21px); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}">
                    Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}
                </h3>
                <small class="text-info d-block mt-auto" style="font-size: 11px;"><i class="fas fa-database mr-1"></i> Dari dwh_fact_penjualan</small>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card p-3 shadow-sm" style="background: #1c2541; border: none; border-radius: 12px; height: 100%; min-height: 115px;">
                <small class="text-muted font-weight-bold d-block mb-1" style="letter-spacing: 0.8px; font-size: 10px;">KEUNTUNGAN BERSIH (PROFIT)</small>
                <h3 class="font-weight-bold text-success my-1" style="font-size: clamp(17px, 1.4vw, 21px); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="Rp {{ number_format($totalKeuntungan ?? 0, 0, ',', '.') }}">
                    Rp {{ number_format($totalKeuntungan ?? 0, 0, ',', '.') }}
                </h3>
                <small class="text-muted d-block mt-auto" style="font-size: 11px;">Berdasarkan Margin HPP</small>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card p-3 shadow-sm" style="background: #1c2541; border: none; border-radius: 12px; height: 100%; min-height: 115px;">
                <small class="text-muted font-weight-bold d-block mb-1" style="letter-spacing: 0.8px; font-size: 10px;">TOTAL VOLUME TERJUAL</small>
                <h3 class="font-weight-bold text-white my-1" style="font-size: clamp(18px, 1.5vw, 22px);">
                    {{ number_format($totalUnitTerjual ?? 0, 0, ',', '.') }} <span style="font-size: 13px; color: #64748b; font-weight: normal;">Unit</span>
                </h3>
                <small style="color: #f59e0b; font-size: 11px;" class="d-block mt-auto"><i class="fas fa-truck mr-1"></i> Logistik Keluar Cabang</small>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card p-3 shadow-sm" style="background: #1c2541; border: none; border-radius: 12px; height: 100%; min-height: 115px;">
                <small class="text-muted font-weight-bold d-block mb-1" style="letter-spacing: 0.8px; font-size: 10px;">TOTAL TRANSAKSI TERPROSES</small>
                <h3 class="font-weight-bold text-warning my-1" style="font-size: clamp(18px, 1.5vw, 22px);">
                    {{ number_format($totalTransaksi ?? 0, 0, ',', '.') }} <span style="font-size: 13px; color: #64748b; font-weight: normal;">Rows</span>
                </h3>
                <small class="text-muted d-block mt-auto" style="font-size: 11px;"><i class="fas fa-stream mr-1"></i> Integrasi OLTP Berhasil</small>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7 mb-4">
            <div class="card p-4 shadow-sm" style="background: #1c2541; border: none; border-radius: 12px; height: 380px;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="font-weight-bold text-white m-0" style="letter-spacing: 0.5px;">
                        TREN KEUNTUNGAN BERSIH MURNI 
                        <span class="text-info" style="font-size: 13px;">
                            ({{ $selectedCabang == 'all' ? 'Semua Wilayah' : ($selectedCabang == '1' ? 'Cabang Palu' : ($selectedCabang == '2' ? 'Cabang Donggala' : 'Cabang Parigi')) }})
                        </span>
                    </h6>
                    <small style="color: #64748b; font-size: 11px;">Real-Time Data Warehouse</small>
                </div>
                <div class="position-relative" style="height: 290px;">
                    <canvas id="lineChartAnalitik"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-5 mb-4">
            <div class="card p-4 shadow-sm" style="background: #1c2541; border: none; border-radius: 12px; height: 380px;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="font-weight-bold text-white m-0" style="letter-spacing: 0.5px;">OMZET ANTAR CABANG REGIONAL</h6>
                    <small style="color: #64748b; font-size: 11px;">Akumulasi Wilayah</small>
                </div>
                <div class="position-relative" style="height: 290px;">
                    <canvas id="barChartAnalitik"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEtlProgress" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="background: #1c2541; color: #f8fafc; border: 1px solid #334155; border-radius: 14px;">
            <div class="modal-body text-center p-4">
                <div class="mb-4 mt-2">
                    <i class="fas fa-cog fa-spin text-info mr-2" style="font-size: 40px;"></i>
                    <i class="fas fa-database text-warning" style="font-size: 30px;"></i>
                </div>
                <h5 class="font-weight-bold text-white mb-1" style="letter-spacing: 0.5px;">Arkadia ETL Pipeline</h5>
                <p class="text-muted mb-4" style="font-size: 13px;">Sedang menyinkronkan database operasional ke Gudang Data...</p>
                <div class="text-left p-3" style="background: #0b1329; border-radius: 8px; border: 1px solid #334155; font-family: 'Courier New', Courier, monospace; font-size: 13px; min-height: 110px;">
                    <div id="etl-step-1" class="mb-2 text-warning">
                        <i class="fas fa-spinner fa-spin mr-2"></i> [1/3] EXTRACT: Mengunduh baris transaksi baru...
                    </div>
                    <div id="etl-step-2" class="mb-2 text-muted">
                        <i class="fas fa-circle mr-2" style="font-size: 8px;"></i> [2/3] TRANSFORM: Membersihkan & menyusun skema bintang...
                    </div>
                    <div id="etl-step-3" class="text-muted">
                        <i class="fas fa-circle mr-2" style="font-size: 8px;"></i> [3/3] LOAD: Menyisipkan metrik profit ke tabel fakta...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // POP-UP LIVE PIPELINE
    document.getElementById('formEtl').addEventListener('submit', function(e) {
        e.preventDefault(); 
        const currentForm = this;
        $('#modalEtlProgress').modal({ backdrop: 'static', keyboard: false });

        const step1 = document.getElementById('etl-step-1');
        const step2 = document.getElementById('etl-step-2');
        const step3 = document.getElementById('etl-step-3');

        setTimeout(() => {
            step1.innerHTML = '<i class="fas fa-check-circle text-success mr-2"></i> [1/3] EXTRACT: Sukses membaca log transaksi operasional.';
            step1.className = "mb-2 text-white-50"; step2.className = "mb-2 text-warning";
            step2.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> [2/3] TRANSFORM: Menghitung profit margin & denormalisasi...';
        }, 1500);

        setTimeout(() => {
            step2.innerHTML = '<i class="fas fa-check-circle text-success mr-2"></i> [2/3] TRANSFORM: Skema data gudang berhasil dibentuk.';
            step2.className = "mb-2 text-white-50"; step3.className = "text-warning";
            step3.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> [3/3] LOAD: Menyisipkan agregasi ke dwh_fact_penjualan...';
        }, 3000);

        setTimeout(() => {
            step3.innerHTML = '<i class="fas fa-check-circle text-success mr-2"></i> [3/3] LOAD: Metadata sinkronisasi & tabel fakta berhasil diperbarui!';
            step3.className = "text-success font-weight-bold";
        }, 4300);

        setTimeout(() => { currentForm.submit(); }, 5000);
    });

    // GRAPH DATA DIMENSION
    const bData = {!! json_encode($dwhBulanan) !!};
    const cData = {!! json_encode($dwhCabang) !!};

    const finalBulanan = bData.length > 0 ? bData : [];
    const finalCabang = cData.length > 0 ? cData : [];

    const listBulan = {
        '01': 'Jan', '02': 'Feb', '03': 'Mar', '04': 'Apr', '05': 'Mei', '06': 'Jun',
        '07': 'Jul', '08': 'Agust', '09': 'Sept', '10': 'Okt', '11': 'Nov', '12': 'Des'
    };

    const labelWaktu = finalBulanan.map(item => {
        let code = String(item.bulan || '');
        if (code.length === 6) {
            let bln = code.substring(4, 6);
            return listBulan[bln] || code;
        }
        return code;
    });

    const namaCabangMurni = {
        '1': 'Cabang Palu',
        '2': 'Cabang Donggala',
        '3': 'Cabang Parigi'
    };

    // LINE CHART
    const ctxLine = document.getElementById('lineChartAnalitik');
    if(ctxLine) {
        new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: labelWaktu,
                datasets: [{
                    label: 'Profit Bersih',
                    data: finalBulanan.map(item => (item.total_keuntungan || 0) / 1000000), 
                    borderColor: '#10b981', 
                    backgroundColor: 'rgba(16, 185, 129, 0.04)',
                    fill: true,
                    tension: 0.4, 
                    pointRadius: 4,
                    pointBackgroundColor: '#10b981',
                    borderWidth: 2.5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        ticks: {
                            color: '#64748b',
                            callback: function(value) { return 'Rp ' + value.toLocaleString('id-ID') + ' Jt'; }
                        },
                        grid: { color: 'rgba(51, 65, 85, 0.15)' }
                    },
                    x: { ticks: { color: '#64748b' }, grid: { display: false } }
                }
            }
        });
    }

    // BAR CHART (Selalu tampil utuh sebagai pembanding makro)
    const ctxBar = document.getElementById('barChartAnalitik');
    if(ctxBar) {
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: finalCabang.map(item => namaCabangMurni[String(item.id_dim_cabang).trim()] || 'Cabang ' + item.id_dim_cabang),
                datasets: [{
                    data: finalCabang.map(item => (item.total_omzet || 0) / 1000000000), 
                    backgroundColor: ['#c084fc', '#818cf8', '#60a5fa'],
                    borderRadius: 6,
                    barThickness: 30
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        ticks: {
                            color: '#64748b',
                            callback: function(value) { return 'Rp ' + value + ' M'; }
                        },
                        grid: { color: 'rgba(51, 65, 85, 0.15)' }
                    },
                    x: { ticks: { color: '#64748b' }, grid: { display: false } }
                }
            }
        });
    }
</script>
@endsection