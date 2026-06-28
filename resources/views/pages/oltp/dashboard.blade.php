@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4" style="background-color: #0b1329; min-height: 100vh; color: #f8fafc;">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <div class="p-3 mr-3 shadow-sm" style="background: #1c2541; border-radius: 50%; width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-chart-bar text-success" style="font-size: 18px;"></i>
            </div>
            <div>
                <h4 class="font-weight-bold text-white m-0" style="letter-spacing: 0.5px; font-size: 22px;">Executive Analytics Portal (DWH)</h4>
                <small class="text-muted" style="font-size: 13px;">Hasil ringkasan data multidimensi dari pipa ETL berkala</small>
            </div>
        </div>

        {{-- 🛑 PROTEKSI SANGAT KETAT: Tombol Sinkronisasi Data Prosedural ini hanya milik Super Admin --}}
        @can('run-etl-procedural')
            <button id="btnRunEtl" type="button" class="btn text-white font-weight-bold px-4 py-2 shadow-sm" style="background: #10b981; border: none; border-radius: 6px; font-size: 13px; transition: 0.2s;">
                <i class="fas fa-sync-alt mr-2"></i> Jalankan Pipa ETL
            </button>
        @endcan
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card p-4 shadow-sm" style="background: #1c2541; border: none; border-radius: 12px;">
                <small class="text-muted font-weight-bold text-uppercase" style="font-size: 11px;">Total Keuntungan Bersih</small>
                <h3 class="font-weight-bold text-white mt-2">Rp 145.250.000</h3>
                <span class="text-success" style="font-size: 12px;"><i class="fas fa-arrow-up mr-1"></i> +12% Bulan ini</span>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card p-4 shadow-sm" style="background: #1c2541; border: none; border-radius: 12px;">
                <small class="text-muted font-weight-bold text-uppercase" style="font-size: 11px;">Cabang Performa Tertinggi</small>
                <h3 class="font-weight-bold text-white mt-2">Arkadia Palu</h3>
                <span class="text-muted" style="font-size: 12px;">Kontribusi Penjualan 45%</span>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
{{-- Script AJAX agar saat tombol diklik tidak perlu reload satu halaman full --}}
@can('run-etl-procedural')
<script>
    document.getElementById('btnRunEtl').addEventListener('click', function() {
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses Pipa ETL...';

        fetch("{{ route('dashboard.runEtl') }}", {
            method: "POST",
            headers: {
                "X-CSR-Value": "{{ csrf_token() }}",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Content-Type": "application/json"
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert('Sukses: ' + data.message);
            } else {
                alert('Gagal: ' + data.message);
            }
        })
        .catch(error => {
            alert('Terjadi kesalahan jaringan atau server timeout!');
            console.error(error);
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-sync-alt mr-2"></i> Jalankan Pipa ETL';
        });
    });
</script>
@endcan
@endsection