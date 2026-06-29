@extends('layouts.app')

@section('content')
<div class="p-4 p-md-5">
    
    <div class="d-flex align-items-center mb-4">
        <div class="rounded-circle d-flex align-items-center justify-content-center" 
            style="width: 50px; height: 50px; background-color: #1c2541; border: 1px solid #334155;">
            <i class="fas fa-boxes text-warning" style="font-size: 18px;"></i>
        </div>
        <div class="ml-3">
            <h2 class="text-white font-weight-bold m-0" style="font-size: 22px; letter-spacing: -0.5px;">Stok Laptop Gudang (OLTP)</h2>
            <p class="text-muted m-0" style="font-size: 13.5px;">Manajemen ketersediaan volume unit barang aktif</p>
        </div>
    </div>

    <div class="card border-0 shadow-lg" style="background-color: #1c2541; border: 1px solid #334155 !important; border-radius: 10px; overflow: hidden;">
        
        <div class="card-header border-0 d-flex align-items-center justify-content-between p-4" style="background-color: rgba(0,0,0,0.15); border-bottom: 1px solid #334155 !important;">
            <div class="d-flex align-items-center text-warning font-weight-bold text-uppercase" style="font-size: 12.5px; letter-spacing: 0.8px;">
                <i class="fas fa-layer-group mr-2"></i> Daftar Inventaris Produk
            </div>
            <button class="btn btn-warning font-weight-bold btn-sm px-3 py-2" style="border-radius: 6px; font-size: 12px; color: #0b1329;">
                <i class="fas fa-plus mr-1"></i> Tambah Laptop
            </button>
        </div>

        <div class="table-responsive">
            <table class="table text-white mb-0" style="font-size: 13.5px; background-color: #1c2541;">
                <thead>
                    <tr style="background: rgba(0,0,0,0.25); font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; border-bottom: 1px solid #334155;">
                        <th class="border-0 px-4 py-3">Kode Barang</th>
                        <th class="border-0 px-4 py-3">Nama Laptop</th>
                        <th class="border-0 px-4 py-3">Brand / Manufaktur</th>
                        <th class="border-0 px-4 py-3">Harga Satuan</th>
                        <th class="border-0 px-4 py-3">Sisa Stok</th>
                        <th class="border-0 px-4 py-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($daftarStok as $stok)
                        <tr style="border-bottom: 1px solid rgba(51, 65, 85, 0.4);" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.02)'" onmouseout="this.style.backgroundColor='transparent'">
                            <td class="px-4 py-3 font-weight-bold text-muted align-middle">
                                {{ $stok->kode_laptop ?? $stok->kode_produk ?? $stok->kode ?? 'LP-'.$stok->id }}
                            </td>
                            <td class="px-4 py-3 font-weight-bold text-white align-middle">
                                {{ $stok->nama_laptop ?? $stok->nama ?? $stok->produk ?? 'Unit Laptop' }}
                            </td>
                            <td class="px-4 py-3 text-white align-middle">
                                {{ ucfirst($stok->brand ?? $stok->merk ?? $stok->kategori ?? 'Generic') }}
                            </td>
                            <td class="px-4 py-3 font-weight-bold text-white align-middle">
                                Rp {{ number_format($stok->harga ?? $stok->harga_jual ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 font-weight-bold text-warning align-middle">
                                @php $qty = $stok->stok ?? $stok->qty ?? $stok->jumlah ?? 0; @endphp
                                {{ $qty }} Unit
                            </td>
                            <td class="px-4 py-3 align-middle">
                                @if($qty > 5)
                                    <span class="badge px-2 py-1" style="background: rgba(40, 167, 69, 0.15); color: #28a745; border: 1px solid rgba(40, 167, 69, 0.3);">Ready Stok</span>
                                @elseif($qty > 0)
                                    <span class="badge px-2 py-1" style="background: rgba(255, 193, 7, 0.15); color: #ffc107; border: 1px solid rgba(255, 193, 7, 0.3);">Stok Tipis</span>
                                @else
                                    <span class="badge px-2 py-1" style="background: rgba(220, 53, 69, 0.15); color: #dc3545; border: 1px solid rgba(220, 53, 69, 0.3);">Habis</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-5 text-center text-muted font-weight-bold">
                                <i class="fas fa-cubes d-block mb-2 text-secondary" style="font-size: 22px;"></i>
                                Belum ada data aset gudang yang terdaftar di database.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($daftarStok->hasPages())
            <div class="card-footer border-0 d-flex align-items-center justify-content-between p-4" style="background-color: rgba(0,0,0,0.15); border-top: 1px solid #334155 !important;">
                <div class="text-muted" style="font-size: 12.5px;">
                    Menampilkan <span class="text-white font-weight-bold">{{ $daftarStok->firstItem() }}</span> - <span class="text-white font-weight-bold">{{ $daftarStok->lastItem() }}</span> dari <span class="text-white font-weight-bold">{{ $daftarStok->total() }}</span> Barang
                </div>
                <div class="d-flex" style="gap: 8px;">
                    @if($daftarStok->onFirstPage())
                        <span class="btn btn-sm text-muted disabled" style="background: #0b1329; border: 1px solid #334155; font-size: 12px;">Sebelumnya</span>
                    @else
                        <a href="{{ $daftarStok->previousPageUrl() }}" class="btn btn-sm text-white" style="background: #0b1329; border: 1px solid #334155; font-size: 12px;">Sebelumnya</a>
                    @endif

                    @if($daftarStok->hasMorePages())
                        <a href="{{ $daftarStok->nextPageUrl() }}" class="btn btn-sm text-white" style="background: #0b1329; border: 1px solid #334155; font-size: 12px;">Selanjutnya</a>
                    @else
                        <span class="btn btn-sm text-muted disabled" style="background: #0b1329; border: 1px solid #334155; font-size: 12px;">Selanjutnya</span>
                    @endif
                </div>
            </div>
        @endif

    </div>
</div>
@endsection