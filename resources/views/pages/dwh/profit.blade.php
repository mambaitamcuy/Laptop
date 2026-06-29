@extends('layouts.app')

@section('content')
<div class="p-4 p-md-5">
    
    <div class="d-flex align-items-center mb-4">
        <div class="rounded-circle d-flex align-items-center justify-content-center" 
             style="width: 50px; height: 50px; background-color: #1c2541; border: 1px solid #334155;">
            <i class="fas fa-table text-info" style="font-size: 18px;"></i>
        </div>
        <div class="ml-3">
            <h2 class="text-white font-weight-bold m-0" style="font-size: 22px; letter-spacing: -0.5px;">Tabel Fakta Profit (DWH)</h2>
            <p class="text-muted m-0" style="font-size: 13.5px;">Penyimpanan terpusat data dimensi finansial dan margin laba bersih produk</p>
        </div>
    </div>

    <div class="card border-0 shadow-lg" style="background-color: #1c2541; border: 1px solid #334155 !important; border-radius: 10px; overflow: hidden;">
        
        <div class="card-header border-0 d-flex align-items-center justify-content-between p-4" style="background-color: rgba(0,0,0,0.15); border-bottom: 1px solid #334155 !important;">
            <div class="d-flex align-items-center text-info font-weight-bold text-uppercase" style="font-size: 12.5px; letter-spacing: 0.8px;">
                <i class="fas fa-database mr-2"></i> Fact Table Records
            </div>
            <span class="badge badge-dark px-3 py-2 text-muted font-weight-bold" style="background-color: #0b1329; font-size: 11px;">ReadOnly Warehouse Matrix</span>
        </div>

        <div class="table-responsive">
            <table class="table text-white mb-0" style="font-size: 13.5px; background-color: #1c2541;">
                <thead>
                    <tr style="background: rgba(0,0,0,0.25); font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; border-bottom: 1px solid #334155;">
                        <th class="border-0 px-4 py-3">Fact ID</th>
                        <th class="border-0 px-4 py-3">SKU / Laptop ID</th>
                        <th class="border-0 px-4 py-3">Total Gross Revenue</th>
                        <th class="border-0 px-4 py-3">Total Cost (HPP)</th>
                        <th class="border-0 px-4 py-3">Net Profit</th>
                        <th class="border-0 px-4 py-3">Cabang ID</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($daftarProfit as $row)
                        <tr style="border-bottom: 1px solid rgba(51, 65, 85, 0.4);" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.02)'" onmouseout="this.style.backgroundColor='transparent'">
                            
                            <td class="px-4 py-3 font-weight-bold text-muted align-middle">
                                #FCT-{{ $row->id_fact ?? $row->id ?? $loop->iteration }}
                            </td>
                            
                            <td class="px-4 py-3 font-weight-bold text-white align-middle">
                                {{ $row->id_laptop ?? $row->laptop_id ?? $row->sku_produk ?? 'Unit SKU' }}
                            </td>
                            
                            <td class="px-4 py-3 text-white align-middle">
                                Rp {{ number_format($row->total_pendapatan ?? $row->gross ?? $row->total_harga ?? 0, 0, ',', '.') }}
                            </td>
                            
                            <td class="px-4 py-3 text-muted align-middle">
                                Rp {{ number_format($row->hpp ?? $row->total_cost ?? 0, 0, ',', '.') }}
                            </td>
                            
                            <td class="px-4 py-3 font-weight-bold text-success align-middle" style="color: #10b981 !important;">
                                Rp {{ number_format($row->keuntungan_bersih ?? $row->profit ?? $row->keuntungan ?? 0, 0, ',', '.') }}
                            </td>
                            
                            <td class="px-4 py-3 text-info font-weight-bold align-middle">
                                <span class="badge px-2.5 py-1.5" style="background: rgba(6, 182, 212, 0.1); color: #06b6d4; border: 1px solid rgba(6, 182, 212, 0.2);">
                                    LOC-{{ $row->id_cabang ?? $row->cabang ?? '01' }}
                                </span>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-5 text-center text-muted font-weight-bold">
                                <i class="fas fa-server d-block mb-2 text-secondary" style="font-size: 22px;"></i>
                                Pipa ETL belum dieksekusi. Tidak ada row di dalam tabel fakta `dwh_fact_penjualan`.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($daftarProfit->hasPages())
            <div class="card-footer border-0 d-flex align-items-center justify-content-between p-4" style="background-color: rgba(0,0,0,0.15); border-top: 1px solid #334155 !important;">
                <div class="text-muted" style="font-size: 12.5px;">
                    Menampilkan <span class="text-white font-weight-bold">{{ $daftarProfit->firstItem() }}</span> - <span class="text-white font-weight-bold">{{ $daftarProfit->lastItem() }}</span> dari <span class="text-white font-weight-bold">{{ number_format($daftarProfit->total(), 0, ',', '.') }}</span> Baris Fakta DWH
                </div>
                <div class="d-flex" style="gap: 8px;">
                    @if($daftarProfit->onFirstPage())
                        <span class="btn btn-sm text-muted disabled" style="background: #0b1329; border: 1px solid #334155; font-size: 12px; cursor: not-allowed;">Sebelumnya</span>
                    @else
                        <a href="{{ $daftarProfit->previousPageUrl() }}" class="btn btn-sm text-white" style="background: #0b1329; border: 1px solid #334155; font-size: 12px;">Sebelumnya</a>
                    @endif

                    @if($daftarProfit->hasMorePages())
                        <a href="{{ $daftarProfit->nextPageUrl() }}" class="btn btn-sm text-white" style="background: #0b1329; border: 1px solid #334155; font-size: 12px;">Selanjutnya</a>
                    @else
                        <span class="btn btn-sm text-muted disabled" style="background: #0b1329; border: 1px solid #334155; font-size: 12px; cursor: not-allowed;">Selanjutnya</span>
                    @endif
                </div>
            </div>
        @endif

    </div>
</div>
@endsection