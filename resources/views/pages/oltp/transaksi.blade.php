@extends('layouts.app')

@section('content')
<div class="p-4 p-md-5">
    
    <div class="d-flex align-items-center mb-4">
        <div class="rounded-circle d-flex align-items-center justify-content-center" 
             style="width: 50px; height: 50px; background-color: #1c2541; border: 1px solid #334155; shadow: 0 4px 6px rgba(0,0,0,0.3);">
            <i class="fas fa-shopping-cart text-warning" style="font-size: 18px;"></i>
        </div>
        <div class="ml-3">
            <h2 class="text-white font-weight-bold m-0" style="font-size: 22px; letter-spacing: -0.5px;">Transaksi Kasir Harian (OLTP)</h2>
            <p class="text-muted m-0" style="font-size: 13.5px;">Pencatatan nota penjualan langsung toko operasional secara real-time</p>
        </div>
    </div>

    <div class="card border-0 shadow-lg shadow-dark" style="background-color: #1c2541; border: 1px solid #334155 !important; border-radius: 10px; overflow: hidden;">
        
        <div class="card-header border-0 d-flex align-items-center justify-content-between p-4" style="background-color: rgba(0,0,0,0.15); border-bottom: 1px solid #334155 !important;">
            <div class="d-flex align-items-center text-warning font-weight-bold text-uppercase" style="font-size: 12.5px; letter-spacing: 0.8px;">
                <i class="fas fa-history mr-2"></i> Log Penjualan Transaksi
            </div>
            <button class="btn btn-warning font-weight-bold btn-sm px-3 py-2" style="border-radius: 6px; font-size: 12px; color: #0b1329; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.15);">
                <i class="fas fa-plus mr-1"></i> Transaksi Baru
            </button>
        </div>

        <div class="table-responsive">
            <table class="table text-white mb-0" style="font-size: 13.5px; background-color: #1c2541;">
                <thead>
                    <tr style="background: rgba(0,0,0,0.25); font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; border-bottom: 1px solid #334155;">
                        <th class="border-0 px-4 py-3">No. Nota</th>
                        <th class="border-0 px-4 py-3">Tanggal & Waktu</th>
                        <th class="border-0 px-4 py-3">Produk Terjual</th>
                        <th class="border-0 px-4 py-3">Jumlah</th>
                        <th class="border-0 px-4 py-3">Total Pembayaran</th>
                        <th class="border-0 px-4 py-3">Metode Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($daftarTransaksi as $trx)
                        <tr style="border-bottom: 1px solid rgba(51, 65, 85, 0.4); transition: background 0.2s;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.02)'" onmouseout="this.style.backgroundColor='transparent'">
                            <td class="px-4 py-3 font-weight-bold text-white tracking-wide align-middle">
                                #{{ $trx->no_transaksi ?? $trx->id_transaksi ?? $trx->id ?? 'TRX-Unknown' }}
                            </td>

                            <td class="px-4 py-3 text-muted align-middle">
                                {{ isset($trx->created_at) ? \Carbon\Carbon::parse($trx->created_at)->format('d F Y - H:i') : ($trx->tanggal ?? '-') }}
                            </td>

                            <td class="px-4 py-3 font-weight-bold text-white align-middle">
                                {{ $trx->nama_laptop ?? $trx->produk ?? $trx->item ?? 'Laptop Unit' }}
                            </td>

                            <td class="px-4 py-3 text-muted font-weight-bold align-middle">
                                {{ $trx->jumlah ?? $trx->qty ?? 1 }} Unit
                            </td>

                            <td class="px-4 py-3 font-weight-bold text-white align-middle">
                                Rp {{ number_format($trx->total_pembayaran ?? $trx->total_harga ?? $trx->total ?? 0, 0, ',', '.') }}
                            </td>

                            <td class="px-4 py-3 align-middle">
                                @php
                                    $metode = strtolower($trx->metode_bayar ?? $trx->metode_pembayaran ?? 'cash');
                                @endphp

                                @if(str_contains($metode, 'midtrans') || str_contains($metode, 'snap'))
                                    <span class="badge d-inline-block px-2.5 py-1.5" style="background: rgba(59, 130, 246, 0.15); color: #3b82f6; border: 1px solid rgba(59, 130, 246, 0.3); border-radius: 4px; font-size: 11px; font-weight: 600;">
                                        Midtrans (Snap)
                                    </span>
                                @elseif(str_contains($metode, 'cash') || str_contains($metode, 'tunai'))
                                    <span class="badge d-inline-block px-2.5 py-1.5" style="background: rgba(40, 167, 69, 0.15); color: #28a745; border: 1px solid rgba(40, 167, 69, 0.3); border-radius: 4px; font-size: 11px; font-weight: 600;">
                                        Tunai / Cash
                                    </span>
                                @else
                                    <span class="badge d-inline-block px-2.5 py-1.5" style="background: rgba(155, 89, 182, 0.15); color: #a855f7; border: 1px solid rgba(155, 89, 182, 0.3); border-radius: 4px; font-size: 11px; font-weight: 600;">
                                        {{ ucfirst($metode) }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-5 text-center text-muted font-weight-bold">
                                <i class="fas fa-folder-open d-block mb-2 text-secondary" style="font-size: 22px;"></i>
                                Belum ada catatan data transaksi riil yang masuk ke database OLTP.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($daftarTransaksi->hasPages())
            <div class="card-footer border-0 d-flex flex-column flex-sm-row align-items-center justify-content-between p-4" style="background-color: rgba(0,0,0,0.15); border-top: 1px solid #334155 !important; gap: 16px;">
                <div class="text-muted" style="font-size: 12.5px;">
                    Menampilkan <span class="text-white font-weight-bold">{{ $daftarTransaksi->firstItem() }}</span> sampai 
                    <span class="text-white font-weight-bold">{{ $daftarTransaksi->lastItem() }}</span> dari 
                    <span class="text-white font-weight-bold">{{ number_format($daftarTransaksi->total(), 0, ',', '.') }}</span> Total Baris Transaksi
                </div>
                <div class="d-flex align-items-center" style="gap: 8px;">
                    {{-- Tombol Halaman Sebelumnya --}}
                    @if($daftarTransaksi->onFirstPage())
                        <span class="btn btn-sm text-muted disabled" style="background-color: #0b1329; border: 1px solid #334155; font-size: 12px; font-weight: 500; border-radius: 5px; padding: 6px 14px; cursor: not-allowed;">Sebelumnya</span>
                    @else
                        <a href="{{ $daftarTransaksi->previousPageUrl() }}" class="btn btn-sm text-white" style="background-color: #0b1329; border: 1px solid #334155; font-size: 12px; font-weight: 500; border-radius: 5px; padding: 6px 14px; transition: all 0.2s;">Sebelumnya</a>
                    @endif

                    {{-- Tombol Halaman Selanjutnya --}}
                    @if($daftarTransaksi->hasMorePages())
                        <a href="{{ $daftarTransaksi->nextPageUrl() }}" class="btn btn-sm text-white" style="background-color: #0b1329; border: 1px solid #334155; font-size: 12px; font-weight: 500; border-radius: 5px; padding: 6px 14px; transition: all 0.2s;">Selanjutnya</a>
                    @else
                        <span class="btn btn-sm text-muted disabled" style="background-color: #0b1329; border: 1px solid #334155; font-size: 12px; font-weight: 500; border-radius: 5px; padding: 6px 14px; cursor: not-allowed;">Selanjutnya</span>
                    @endif
                </div>
            </div>
        @endif

    </div>
</div>
@endsection