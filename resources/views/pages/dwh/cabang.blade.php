@extends('layouts.app')

@section('content')
<div class="p-4 p-md-5">
    
    <div class="d-flex align-items-center mb-4">
        <div class="rounded-circle d-flex align-items-center justify-content-center" 
             style="width: 50px; height: 50px; background-color: #1c2541; border: 1px solid #334155;">
            <i class="fas fa-map-marked-alt text-warning" style="font-size: 18px;"></i>
        </div>
        <div class="ml-3">
            <h2 class="text-white font-weight-bold m-0" style="font-size: 22px; letter-spacing: -0.5px;">Analisis Wilayah Cabang (DWH)</h2>
            <p class="text-muted m-0" style="font-size: 13.5px;">Komparasi performa finansial dan kontribusi volume penjualan antar klaster regional</p>
        </div>
    </div>

    <div class="card border-0 shadow-lg" style="background-color: #1c2541; border: 1px solid #334155 !important; border-radius: 10px; overflow: hidden;">
        <div class="card-header border-0 d-flex align-items-center justify-content-between p-4" style="background-color: rgba(0,0,0,0.15); border-bottom: 1px solid #334155 !important;">
            <div class="text-warning font-weight-bold text-uppercase" style="font-size: 12.5px; letter-spacing: 0.8px;">
                <i class="fas fa-chart-pie mr-2"></i> Matriks Performa Lokasi Regional
            </div>
        </div>

        <div class="table-responsive">
            <table class="table text-white mb-0" style="font-size: 13.5px;">
                <thead>
                    <tr style="background: rgba(0,0,0,0.25); font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; border-bottom: 1px solid #334155;">
                        <th class="border-0 px-4 py-3">ID Lokasi</th>
                        <th class="border-0 px-4 py-3">Nama Wilayah / Cabang</th>
                        <th class="border-0 px-4 py-3">Akumulasi Transaksi</th>
                        <th class="border-0 px-4 py-3">Total Akumulasi Omzet</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($analisisCabang as $cab)
                        <tr style="border-bottom: 1px solid rgba(51, 65, 85, 0.4);" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.02)'" onmouseout="this.style.backgroundColor='transparent'">
                            <td class="px-4 py-3 font-weight-bold text-muted align-middle">
                                LOC-0{{ $cab->lokasi_id ?? '1' }}
                            </td>
                            <td class="px-4 py-3 font-weight-bold text-white align-middle">
                                @if(($cab->lokasi_id ?? '1') == '1')
                                    Arkadia Pusat (Headquarter)
                                @else
                                    Arkadia Cabang Regional {{ $cab->lokasi_id }}
                                @endif
                            </td>
                            <td class="px-4 py-3 text-white align-middle">
                                {{ number_format($cab->total_transaksi ?? 0, 0, ',', '.') }} Transaksi
                            </td>
                            <td class="px-4 py-3 font-weight-bold text-info align-middle" style="color: #00b4d8 !important;">
                                Rp {{ number_format($cab->total_omzet ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-5 text-center text-muted font-weight-bold">
                                <i class="fas fa-globe d-block mb-2 text-secondary" style="font-size: 22px;"></i>
                                Belum ada dimensi cabang yang terindeks dalam sistem pergudangan data.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($analisisCabang->hasPages())
            <div class="card-footer border-0 d-flex align-items-center justify-content-between p-4" style="background-color: rgba(0,0,0,0.15); border-top: 1px solid #334155 !important;">
                <div>{{ $analisisCabang->links() }}</div>
            </div>
        @endif
    </div>
</div>
@endsection