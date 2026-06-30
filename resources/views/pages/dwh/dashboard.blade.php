@extends('layouts.app')

@section('content')
<div class="container-fluid p-4 text-white">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center p-4 mb-4" style="background: #1c2541; border: 1px solid #334155; border-radius: 12px; gap: 16px;">
        <div>
            <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
                <span class="text-muted font-weight-bold" style="font-size: 13px; letter-spacing: 0.5px;">Halaman:</span>
                <span class="badge px-3 py-2 font-weight-bold" style="background: rgba(6, 182, 212, 0.15); color: #06b6d4; border: 1px solid rgba(6, 182, 212, 0.3); font-size: 12px; letter-spacing: 0.5px;">DASHBOARD ANALITIK DWH</span>
            </div>
            <p class="text-muted small m-0 mt-2" style="font-size: 13px;">
                <i class="far fa-clock text-warning mr-1"></i> Sinkronisasi Terakhir: <strong class="text-white">{{ $syncTime }}</strong>
            </p>
        </div>
        
        <div class="d-flex flex-wrap align-items-center justify-content-start justify-content-md-end w-100 w-md-auto" style="gap: 12px;">
            
            <form action="{{ route('dwh.dashboard') }}" method="GET" id="form-filter-wilayah" class="m-0">
                <div class="d-flex align-items-center px-3 py-2" style="background: #0b1329; border: 1px solid #334155; border-radius: 8px;">
                    <label for="wilayah" class="text-muted small font-weight-bold m-0 mr-2">Wilayah:</label>
                    <select name="wilayah" id="wilayah" onchange="document.getElementById('form-filter-wilayah').submit();" class="text-white border-0 bg-transparent custom-select-sm font-weight-bold" style="outline: none; cursor: pointer; padding-right: 20px;">
                        <option value="all" style="background: #1c2541; color: white;" {{ $selectedWilayah == 'all' ? 'selected' : '' }}>🌏 Semua Cabang</option>
                        <option value="Palu" style="background: #1c2541; color: white;" {{ $selectedWilayah == 'Palu' ? 'selected' : '' }}>Cabang Palu</option>
                        <option value="Parigi" style="background: #1c2541; color: white;" {{ $selectedWilayah == 'Parigi' ? 'selected' : '' }}>Cabang Parigi</option>
                        <option value="Donggala" style="background: #1c2541; color: white;" {{ $selectedWilayah == 'Donggala' ? 'selected' : '' }}>Cabang Donggala</option>
                    </select>
                </div>
            </form>

            <button type="button" id="btn-etl" onclick="prosesPipaEtl()" class="btn font-weight-bold d-flex align-items-center shadow-sm" style="background: #06b6d4; color: #0b1329; border-radius: 8px; padding: 10px 18px; font-size: 13.5px; border: none; gap: 8px;">
                <i class="fas fa-sync-alt"></i> <span id="etl-text">Jalankan Pipa ETL</span>
            </button>

            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn btn-danger font-weight-bold d-flex align-items-center shadow-sm" style="background: #e11d48; border: none; border-radius: 8px; padding: 10px 18px; font-size: 13.5px; gap: 8px;">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </button>
            </form>
            
        </div>
    </div>

    <div class="row mb-2">
        
        <div class="col-12 col-sm-6 col-xl-3 mb-4">
            <div class="p-4 d-flex flex-column justify-content-between h-100" style="background: #1c2541; border: 1px solid #334155; border-radius: 12px; min-height: 150px;">
                <div>
                    <small class="text-muted font-weight-bold text-uppercase d-block mb-2" style="font-size: 11px; letter-spacing: 0.5px;">Total Pendapatan (Gross)</small>
                    <h3 class="font-weight-bold m-0 text-white tracking-tight" style="font-size: 23px;">
                        Rp {{ number_format($metrics->total_gross ?? 0, 0, ',', '.') }}
                    </h3>
                </div>
                <div class="small mt-3 font-weight-bold" style="color: #06b6d4; font-size: 12.5px;">
                    <i class="fas fa-database mr-1"></i> Dari dwh_fact_penjualan
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3 mb-4">
            <div class="p-4 d-flex flex-column justify-content-between h-100" style="background: #1c2541; border: 1px solid #334155; border-radius: 12px; min-height: 150px;">
                <div>
                    <small class="text-muted font-weight-bold text-uppercase d-block mb-2" style="font-size: 11px; letter-spacing: 0.5px;">Keuntungan Bersih (Profit)</small>
                    <h3 class="font-weight-bold m-0 tracking-tight" style="font-size: 23px; color: #10b981;">
                        Rp {{ number_format($metrics->total_profit ?? 0, 0, ',', '.') }}
                    </h3>
                </div>
                <div class="small mt-3 text-muted" style="font-size: 12.5px;">
                    Berdasarkan Margin HPP
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3 mb-4">
            <div class="p-4 d-flex flex-column justify-content-between h-100" style="background: #1c2541; border: 1px solid #334155; border-radius: 12px; min-height: 150px;">
                <div>
                    <small class="text-muted font-weight-bold text-uppercase d-block mb-2" style="font-size: 11px; letter-spacing: 0.5px;">Total Volume Terjual</small>
                    <h3 class="font-weight-bold m-0 text-white tracking-tight" style="font-size: 23px;">
                        {{ number_format($metrics->total_volume ?? 0, 0, ',', '.') }} <span class="text-muted font-weight-normal" style="font-size: 14px;">Unit</span>
                    </h3>
                </div>
                <div class="small mt-3 text-warning font-weight-bold" style="font-size: 12.5px;">
                    <i class="fas fa-truck mr-1"></i> Logistik Keluar Cabang
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3 mb-4">
            <div class="p-4 d-flex flex-column justify-content-between h-100" style="background: #1c2541; border: 1px solid #334155; border-radius: 12px; min-height: 150px;">
                <div>
                    <small class="text-muted font-weight-bold text-uppercase d-block mb-2" style="font-size: 11px; letter-spacing: 0.5px;">Total Transaksi Terproses</small>
                    <h3 class="font-weight-bold m-0 tracking-tight" style="font-size: 23px; color: #f59e0b;">
                        {{ number_format($metrics->total_rows ?? 0, 0, ',', '.') }} <span class="text-muted font-weight-normal" style="font-size: 14px;">Rows</span>
                    </h3>
                </div>
                <div class="small mt-3 font-weight-bold" style="color: #10b981; font-size: 12.5px;">
                    <i class="fas fa-check-circle mr-1"></i> Integrasi OLTP Berhasil
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        
        <div class="col-12 col-lg-6 mb-4">
            <div class="p-4" style="background: #1c2541; border: 1px solid #334155; border-radius: 12px; min-height: 400px;">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h5 class="text-white font-weight-bold m-0" style="font-size: 15px; letter-spacing: 0.5px;">TREN KEUNTUNGAN BERSIH MURNI</h5>
                        <span class="small font-weight-bold" style="color: #06b6d4;">
                            ({{ $selectedWilayah == 'all' ? 'Semua Wilayah' : 'Cabang ' . $selectedWilayah }})
                        </span>
                    </div>
                    <span class="badge badge-secondary px-2 py-1 text-uppercase font-weight-bold" style="background: #334155; font-size: 10px; letter-spacing: 0.5px; color: #94a3b8; border: none;">
                        REAL-TIME DATA WAREHOUSE
                    </span>
                </div>
                <div style="position: relative; height: 300px; width: 100%;">
                    <canvas id="profitTrendChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6 mb-4">
            <div class="p-4 d-flex flex-column justify-content-between" style="background: #1c2541; border: 1px solid #334155; border-radius: 12px; min-height: 400px;">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="text-white font-weight-bold m-0" style="font-size: 15px; letter-spacing: 0.5px;">OMZET ANTAR CABANG <span class="text-muted font-weight-normal" style="font-size: 11px; margin-left: 4px;">AKUMULASI REGIONAL</span></h5>
                    </div>
                    <span class="badge badge-secondary px-2 py-1 text-uppercase font-weight-bold" style="background: #334155; font-size: 10px; letter-spacing: 0.5px; color: #94a3b8; border: none;">
                        WILAYAH
                    </span>
                </div>
                <div class="d-flex align-items-center justify-content-center border rounded flex-grow-1 mb-2" style="border-style: dashed !important; border-color: #334155 !important; background: rgba(11, 19, 41, 0.3); min-height: 280px;">
                    <span class="text-muted small font-italic">Data Komparasi Distribusi Wilayah Aktif</span>
                </div>
            </div>
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // FUNGSI UTAMA UNTUK MENJALANKAN PIPELINE ETL ASLI
    function prosesPipaEtl() {
        const btn = document.getElementById('btn-etl');
        const text = document.getElementById('etl-text');
        
        // Kunci tombol agar tidak diklik berkali-kali (anti double submission)
        btn.disabled = true;
        btn.style.background = '#f59e0b';
        btn.style.color = '#0b1329';
        text.innerText = "ETL Sedang Berjalan...";
        
        // Menggunakan Fetch API untuk mengirim request POST ke backend Laravel secara realtime
        fetch("{{ route('dwh.run-etl') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Content-Type": "application/json",
                "Accept": "application/json"
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Gagal berkomunikasi dengan server backend.');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert("Sinkronisasi Berhasil!\n" + data.message);
                window.location.reload(); // Refresh halaman untuk merender ulang chart terbaru
            } else {
                alert("ETL Gagal: " + data.message);
                resetTombolEtl();
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("Terjadi kesalahan sistem atau jaringan saat memproses pipa ETL.");
            resetTombolEtl();
        });
    }

    // Fungsi untuk mengembalikan tampilan tombol jika proses ETL gagal
    function resetTombolEtl() {
        const btn = document.getElementById('btn-etl');
        const text = document.getElementById('etl-text');
        btn.disabled = false;
        btn.style.background = '#06b6d4';
        btn.style.color = '#0b1329';
        text.innerText = "Jalankan Pipa ETL";
    }

    // PROSES RENEDERING CHART.JS
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('profitTrendChart').getContext('2d');
        
        const labelsData = {!! json_encode($chartLabels) !!};
        const valuesData = {!! json_encode($chartValues) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labelsData,
                datasets: [{
                    label: 'Keuntungan Bersih (Rp)',
                    data: valuesData,
                    borderColor: '#10B981', 
                    backgroundColor: 'rgba(16, 185, 129, 0.06)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#34D399',
                    pointHoverBackgroundColor: '#10B981',
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        grid: { color: 'rgba(255, 255, 255, 0.04)' },
                        ticks: { 
                            color: '#94A3B8', 
                            font: { size: 10 },
                            callback: function(value) {
                                if (value >= 1e9) return 'Rp ' + (value / 1e9).toFixed(1) + ' M';
                                if (value >= 1e6) return 'Rp ' + (value / 1e6).toFixed(0) + ' Jt';
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94A3B8', font: { size: 11 } }
                    }
                }
            }
        });
    });
</script>
@endsection