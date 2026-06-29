@extends('layouts.app')

@section('content')
<div class="p-4 p-md-5">
    
    <div class="d-flex align-items-center mb-4">
        <div class="rounded-circle d-flex align-items-center justify-content-center" 
             style="width: 50px; height: 50px; background-color: #1c2541; border: 1px solid #334155;">
            <i class="fas fa-users text-warning" style="font-size: 18px;"></i>
        </div>
        <div class="ml-3">
            <h2 class="text-white font-weight-bold m-0" style="font-size: 22px; letter-spacing: -0.5px;">Manajemen Karyawan (OLTP)</h2>
            <p class="text-muted m-0" style="font-size: 13.5px;">Daftar staf operasional dan hak akses kasir cabang</p>
        </div>
    </div>

    <div class="card border-0 shadow-lg" style="background-color: #1c2541; border: 1px solid #334155 !important; border-radius: 10px; overflow: hidden;">
        
        <div class="card-header border-0 d-flex align-items-center justify-content-between p-4" style="background-color: rgba(0,0,0,0.15); border-bottom: 1px solid #334155 !important;">
            <div class="d-flex align-items-center text-warning font-weight-bold text-uppercase" style="font-size: 12.5px; letter-spacing: 0.8px;">
                <i class="fas fa-id-card mr-2"></i> Direktori Sumber Daya Manusia
            </div>
            <button class="btn btn-warning font-weight-bold btn-sm px-3 py-2" style="border-radius: 6px; font-size: 12px; color: #0b1329;">
                <i class="fas fa-user-plus mr-1"></i> Daftarkan Karyawan
            </button>
        </div>

        <div class="table-responsive">
            <table class="table text-white mb-0" style="font-size: 13.5px; background-color: #1c2541;">
                <thead>
                    <tr style="background: rgba(0,0,0,0.25); font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; border-bottom: 1px solid #334155;">
                        <th class="border-0 px-4 py-3">ID Staf</th>
                        <th class="border-0 px-4 py-3">Nama Lengkap</th>
                        <th class="border-0 px-4 py-3">Email Kontak</th>
                        <th class="border-0 px-4 py-3">Jabatan / Otorisasi</th>
                        <th class="border-0 px-4 py-3">Penempatan Cabang</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($daftarKaryawan as $kry)
                        <tr style="border-bottom: 1px solid rgba(51, 65, 85, 0.4); transition: background 0.2s;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.02)'" onmouseout="this.style.backgroundColor='transparent'">
                            
                            <td class="px-4 py-3 font-weight-bold text-muted align-middle">
                                @php
                                    $idRiil = $kry->id_karyawan ?? $kry->id_pegawai ?? $kry->id_user ?? $kry->id ?? $loop->iteration;
                                @endphp
                                #STF-{{ str_pad($idRiil, 4, '0', STR_PAD_LEFT) }}
                            </td>

                            <td class="px-4 py-3 font-weight-bold text-white align-middle">
                                {{ $kry->nama ?? $kry->name ?? $kry->nama_lengkap ?? 'Karyawan Arkadia' }}
                            </td>
                            
                            <td class="px-4 py-3 text-muted align-middle">
                                {{ $kry->email ?? $kry->kontak ?? '-' }}
                            </td>
                            
                            <td class="px-4 py-3 align-middle">
                                <span class="badge text-white px-2.5 py-1" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); font-size: 11px;">
                                    {{ ucfirst($kry->jabatan ?? $kry->role ?? $kry->akses ?? 'Kasir') }}
                                </span>
                            </td>
                            
                            <td class="px-4 py-3 text-white font-weight-bold align-middle">
                                <i class="fas fa-store text-info mr-1" style="font-size: 11px;"></i>
                                @if(isset($kry->id_cabang) || isset($kry->cabang))
                                    Cabang {{ $kry->id_cabang ?? $kry->cabang }}
                                @else
                                    Pusat (Arkadia Headquarter)
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-5 text-center text-muted font-weight-bold">
                                <i class="fas fa-user-slash d-block mb-2 text-secondary" style="font-size: 22px;"></i>
                                Tidak ada data karyawan yang tersimpan di dalam skema database.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($daftarKaryawan->hasPages())
            <div class="card-footer border-0 d-flex align-items-center justify-content-between p-4" style="background-color: rgba(0,0,0,0.15); border-top: 1px solid #334155 !important;">
                <div class="text-muted" style="font-size: 12.5px;">
                    Menampilkan <span class="text-white font-weight-bold">{{ $daftarKaryawan->firstItem() }}</span> - <span class="text-white font-weight-bold">{{ $daftarKaryawan->lastItem() }}</span> dari <span class="text-white font-weight-bold">{{ number_format($daftarKaryawan->total(), 0, ',', '.') }}</span> Staf
                </div>
                <div class="d-flex" style="gap: 8px;">
                    {{-- Tombol Previous --}}
                    @if($daftarKaryawan->onFirstPage())
                        <span class="btn btn-sm text-muted disabled" style="background: #0b1329; border: 1px solid #334155; font-size: 12px; cursor: not-allowed;">Sebelumnya</span>
                    @else
                        <a href="{{ $daftarKaryawan->previousPageUrl() }}" class="btn btn-sm text-white" style="background: #0b1329; border: 1px solid #334155; font-size: 12px;">Sebelumnya</a>
                    @endif

                    {{-- Tombol Next --}}
                    @if($daftarKaryawan->hasMorePages())
                        <a href="{{ $daftarKaryawan->nextPageUrl() }}" class="btn btn-sm text-white" style="background: #0b1329; border: 1px solid #334155; font-size: 12px;">Selanjutnya</a>
                    @else
                        <span class="btn btn-sm text-muted disabled" style="background: #0b1329; border: 1px solid #334155; font-size: 12px; cursor: not-allowed;">Selanjutnya</span>
                    @endif
                </div>
            </div>
        @endif

    </div>
</div>
@endsection