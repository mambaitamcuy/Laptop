@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4" style="background-color: #0b1329; min-height: 100vh; color: #f8fafc;">
    
    <div class="row align-items-center mb-4">
        <div class="col-lg-5 mb-3 mb-lg-0">
            <div class="d-flex align-items-center mb-1">
                <span class="text-muted mr-2" style="font-size: 14px;">Halaman:</span>
                <span class="badge px-3 py-2 text-info font-weight-bold" style="background: rgba(6, 182, 212, 0.1); border-radius: 4px; letter-spacing: 0.5px;">DASHBOARD OPERASIONAL OLTP</span>
            </div>
            <small class="text-muted" style="font-size: 12px; letter-spacing: 0.3px;">
                <i class="fas fa-clock text-warning mr-1"></i> Sistem Diperbarui: 
                <span class="text-white font-weight-bold" style="background: rgba(245, 158, 11, 0.1); padding: 2px 6px; border-radius: 4px;">
                    {{ $lastUpdated }}
                </span>
            </small>
        </div>

        <div class="col-lg-7 d-flex flex-wrap align-items-center justify-content-lg-end" style="gap: 12px;">
            
            <form action="{{ route('oltp.dashboard') }}" method="GET" id="formFilterCabang" class="m-0 d-flex align-items-center">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text border-0" style="background: #1c2541; color: #64748b; font-size: 13px;">
                            <i class="fas fa-map-marker-alt text-info mr-1"></i> Wilayah:
                        </span>
                    </div>
                    <select name="cabang" class="form-control text-white border-0 shadow-sm font-weight-bold" style="background: #1c2541; border-radius: 0 6px 6px 0; font-size: 13px; width: 180px; cursor: pointer;" onchange="document.getElementById('formFilterCabang').submit();">
                        <option value="all" {{ $selectedCabang == 'all' ? 'selected' : '' }}>Semua Cabang</option>
                        <option value="1" {{ $selectedCabang == '1' ? 'selected' : '' }}>Cabang Palu</option>
                        <option value="2" {{ $selectedCabang == '2' ? 'selected' : '' }}>Cabang Donggala</option>
                        <option value="3" {{ $selectedCabang == '3' ? 'selected' : '' }}>Cabang Parigi</option>
                    </select>
                </div>
            </form>

            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn text-white font-weight-bold px-4 py-2 shadow-sm" style="background: #dc2626; border: none; border-radius: 6px; font-size: 13px; transition: 0.2s;" onclick="return confirm('Apakah Anda yakin ingin keluar dari sistem operasional?')">
                    <i class="fas fa-sign-out-alt mr-2"></i> Keluar
                </button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card p-3 shadow-sm" style="background: #1c2541; border: none; border-radius: 12px; height: 100%; min-height: 115px;">
                <small class="text-muted font-weight-bold d-block mb-1" style="letter-spacing: 0.8px; font-size: 10px;">TOTAL STOK FISIK (GROSS)</small>
                <h3 class="font-weight-bold text-white my-1" style="font-size: clamp(17px, 1.4vw, 21px); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ number_format($totalStok ?? 0, 0, ',', '.') }} Unit">
                    {{ number_format($totalStok ?? 0, 0, ',', '.') }} <span style="font-size: 13px; color: #64748b; font-weight: normal;">Unit</span>
                </h3>
                <small class="text-info d-block mt-auto" style="font-size: 11px;"><i class="fas fa-boxes mr-1"></i> Dari database inventori gudang</small>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card p-3 shadow-sm" style="background: #1c2541; border: none; border-radius: 12px; height: 100%; min-height: 115px;">
                <small class="text-muted font-weight-bold d-block mb-1" style="letter-spacing: 0.8px; font-size: 10px;">TOTAL TRANSAKSI TERPROSES</small>
                <h3 class="font-weight-bold text-success my-1" style="font-size: clamp(17px, 1.4vw, 21px); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ number_format($totalTransaksiHariIni ?? 0, 0, ',', '.') }} Rows">
                    {{ number_format($totalTransaksiHariIni ?? 0, 0, ',', '.') }} <span style="font-size: 13px; color: #64748b; font-weight: normal;">Rows</span>
                </h3>
                <small class="text-muted d-block mt-auto" style="font-size: 11px;"><i class="fas fa-cash-register mr-1"></i> Log aktivitas kasir aktif</small>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card p-3 shadow-sm" style="background: #1c2541; border: none; border-radius: 12px; height: 100%; min-height: 115px;">
                <small class="text-muted font-weight-bold d-block mb-1" style="letter-spacing: 0.8px; font-size: 10px;">KONEKSI ENGINE DATABASE</small>
                <h3 class="font-weight-bold text-white my-1" style="font-size: clamp(18px, 1.5vw, 22px);">
                    Connected
                </h3>
                <small style="color: #10b981; font-size: 11px;" class="d-block mt-auto"><i class="fas fa-check-circle mr-1"></i> Instance arkadialp_oltp aktif</small>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card p-3 shadow-sm" style="background: #1c2541; border: none; border-radius: 12px; height: 100%; min-height: 115px;">
                <small class="text-muted font-weight-bold d-block mb-1" style="letter-spacing: 0.8px; font-size: 10px;">KESIAPAN PIPA GUDANG DATA</small>
                <h3 class="font-weight-bold text-warning my-1" style="font-size: clamp(18px, 1.5vw, 22px);">
                    Ready
                </h3>
                <small class="text-muted d-block mt-auto" style="font-size: 11px;"><i class="fas fa-stream mr-1"></i> Saluran ETL siap digunakan</small>
            </div>
        </div>
    </div>

    </div>
@endsection