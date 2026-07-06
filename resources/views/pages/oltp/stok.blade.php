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

    {{-- Notifikasi Sukses --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 text-white mb-4" role="alert" style="background-color: #28a745; border-radius: 8px;">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close text-white" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- Notifikasi Gagal Validasi / Eror Sistem --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 text-white mb-4" role="alert" style="background-color: #dc3545; border-radius: 8px;">
            <h6 class="font-weight-bold mb-2"><i class="fas fa-exclamation-triangle mr-2"></i> Pengisian Form Gagal:</h6>
            <ul class="mb-0 pl-3" style="font-size: 13px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close text-white" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card border-0 shadow-lg" style="background-color: #1c2541; border: 1px solid #334155 !important; border-radius: 10px; overflow: hidden;">
        
        <div class="card-header border-0 d-flex align-items-center justify-content-between p-4" style="background-color: rgba(0,0,0,0.15); border-bottom: 1px solid #334155 !important;">
            <div class="d-flex align-items-center text-warning font-weight-bold text-uppercase" style="font-size: 12.5px; letter-spacing: 0.8px;">
                <i class="fas fa-layer-group mr-2"></i> Daftar Inventaris Produk
            </div>
            <button class="btn btn-warning font-weight-bold btn-sm px-3 py-2" data-toggle="modal" data-target="#modalTambahLaptop" style="border-radius: 6px; font-size: 12px; color: #0b1329;">
                <i class="fas fa-plus mr-1"></i> Tambah Laptop
            </button>
        </div>

        <div class="table-responsive">
            <table class="table text-white mb-0" style="font-size: 13.5px; background-color: #1c2541;">
                <thead>
                    <tr style="background: rgba(0,0,0,0.25); font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; border-bottom: 1px solid #334155;">
                        <th class="border-0 px-4 py-3">Kode Barang</th>
                        <th class="border-0 px-4 py-3">Nama Laptop</th>
                        <th class="border-0 px-4 py-3">Brand</th>
                        <th class="border-0 px-4 py-3">Cabang</th>
                        <th class="border-0 px-4 py-3">Harga Satuan</th>
                        <th class="border-0 px-4 py-3">Sisa Stok</th>
                        <th class="border-0 px-4 py-3">Status</th>
                        <th class="border-0 px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($daftarStok as $stok)
                        <tr style="border-bottom: 1px solid rgba(51, 65, 85, 0.4);" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.02)'" onmouseout="this.style.backgroundColor='transparent'">
                            <td class="px-4 py-3 font-weight-bold text-muted align-middle">
                                LP-{{ $stok->id }}
                            </td>
                            <td class="px-4 py-3 font-weight-bold text-white align-middle">
                                {{ $stok->nama }}
                            </td>
                            <td class="px-4 py-3 text-white align-middle">
                                {{ ucfirst($stok->brand) }}
                            </td>
                            <td class="px-4 py-3 text-info align-middle">
                                <i class="fas fa-map-marker-alt mr-1" style="font-size: 11px;"></i>{{ $stok->cabang }}
                            </td>
                            <td class="px-4 py-3 font-weight-bold text-white align-middle">
                                Rp {{ number_format($stok->harga, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 font-weight-bold text-warning align-middle">
                                {{ $stok->stok }} Unit
                            </td>
                            <td class="px-4 py-3 align-middle">
                                @if($stok->stok > 5)
                                    <span class="badge px-2 py-1" style="background: rgba(40, 167, 69, 0.15); color: #28a745; border: 1px solid rgba(40, 167, 69, 0.3);">Ready Stok</span>
                                @elseif($stok->stok > 0)
                                    <span class="badge px-2 py-1" style="background: rgba(255, 193, 7, 0.15); color: #ffc107; border: 1px solid rgba(255, 193, 7, 0.3);">Stok Tipis</span>
                                @else
                                    <span class="badge px-2 py-1" style="background: rgba(220, 53, 69, 0.15); color: #dc3545; border: 1px solid rgba(220, 53, 69, 0.3);">Habis</span>
                                @endif
                            </td>
                            {{-- Kolom Tombol Edit & Hapus --}}
                            <td class="px-4 py-3 align-middle text-center" style="gap: 6px;">
                                <button class="btn btn-sm btn-outline-info mr-1" data-toggle="modal" data-target="#modalEditLaptop-{{ $stok->id }}" title="Edit Data" style="border-radius: 6px; padding: 4px 10px; font-size: 12px;">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" data-toggle="modal" data-target="#modalHapusLaptop-{{ $stok->id }}" title="Hapus Data" style="border-radius: 6px; padding: 4px 10px; font-size: 12px;">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-5 text-center text-muted font-weight-bold">
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

{{-- ─── POP-UP MODAL FORM TAMBAH LAPTOP ─── --}}
<div class="modal fade" id="modalTambahLaptop" tabindex="-1" role="dialog" aria-labelledby="modalTambahLaptopLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="background-color: #1c2541; border: 1px solid #334155 !important; border-radius: 12px;">
            <div class="modal-header border-0 p-4" style="background-color: rgba(0,0,0,0.15); border-bottom: 1px solid #334155 !important;">
                <h5 class="modal-title text-white font-weight-bold" id="modalTambahLaptopLabel" style="font-size: 16px;">
                    <i class="fas fa-plus-circle text-warning mr-2"></i>Tambah Unit Laptop Baru
                </h5>
                <button type="button" class="close text-white shadow-none" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form action="{{ route('oltp.stok.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 text-white" style="font-size: 13.5px;">
                    
                    <div class="form-group mb-3">
                        <label class="text-muted font-weight-bold mb-2">Nama Laptop <span class="text-danger">*</span></label>
                        <input type="text" name="nama_laptop" class="form-control text-white border-0 px-3 py-2" value="{{ old('nama_laptop') }}" placeholder="Contoh: Arkadia Phantom X" required style="background-color: #0b1329; border-radius: 6px;">
                    </div>

                    <div class="form-group mb-3">
                        <label class="text-muted font-weight-bold mb-2">Brand / Manufaktur <span class="text-danger">*</span></label>
                        <input type="text" name="brand" class="form-control text-white border-0 px-3 py-2" value="{{ old('brand') }}" placeholder="Contoh: Asus, Lenovo, HP" required style="background-color: #0b1329; border-radius: 6px;">
                    </div>

                    <div class="form-group mb-3">
                        <label class="text-muted font-weight-bold mb-2">Cabang Distribusi <span class="text-danger">*</span></label>
                        <select name="cabang" class="form-control text-white border-0 px-3" required style="background-color: #0b1329; border-radius: 6px; height: 40px;">
                            <option value="" disabled {{ old('cabang') ? '' : 'selected' }}>-- Pilih Cabang Gudang --</option>
                            <option value="Palu" {{ old('cabang') == 'Palu' ? 'selected' : '' }}>Palu</option>
                            <option value="Donggala" {{ old('cabang') == 'Donggala' ? 'selected' : '' }}>Donggala</option>
                            <option value="Parigi" {{ old('cabang') == 'Parigi' ? 'selected' : '' }}>Parigi</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-7 form-group mb-3">
                            <label class="text-muted font-weight-bold mb-2">Harga Satuan (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="harga" class="form-control text-white border-0 px-3 py-2" value="{{ old('harga') }}" placeholder="0" required style="background-color: #0b1329; border-radius: 6px;">
                        </div>
                        <div class="col-md-5 form-group mb-3">
                            <label class="text-muted font-weight-bold mb-2">Sisa Stok <span class="text-danger">*</span></label>
                            <input type="number" name="stok" class="form-control text-white border-0 px-3 py-2" value="{{ old('stok') }}" placeholder="0" required style="background-color: #0b1329; border-radius: 6px;">
                        </div>
                    </div>

                </div>
                <div class="modal-footer border-0 p-3 d-flex justify-content-end" style="background-color: rgba(0,0,0,0.15); border-top: 1px solid #334155 !important; gap: 8px;">
                    <button type="button" class="btn btn-sm font-weight-bold px-3 py-2 text-muted" data-dismiss="modal" style="background: #0b1329; border: 1px solid #334155; border-radius: 6px; font-size: 12px;">Batal</button>
                    <button type="submit" class="btn btn-sm btn-warning font-weight-bold px-3 py-2" style="border-radius: 6px; font-size: 12px; color: #0b1329;">Simpan Aset</button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- ─── LOOPING MODAL EDIT & HAPUS (THEME MATCHED) ─── --}}
@foreach($daftarStok as $stok)

    {{-- 1. POP-UP MODAL EDIT LAPTOP --}}
    <div class="modal fade" id="modalEditLaptop-{{ $stok->id }}" tabindex="-1" role="dialog" aria-labelledby="modalEditLaptopLabel-{{ $stok->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg" style="background-color: #1c2541; border: 1px solid #334155 !important; border-radius: 12px;">
                <div class="modal-header border-0 p-4" style="background-color: rgba(0,0,0,0.15); border-bottom: 1px solid #334155 !important;">
                    <h5 class="modal-title text-white font-weight-bold" id="modalEditLaptopLabel-{{ $stok->id }}" style="font-size: 16px;">
                        <i class="fas fa-edit text-info mr-2"></i>Edit Data Unit Laptop
                    </h5>
                    <button type="button" class="close text-white shadow-none" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                {{-- Mengarah ke rute update --}}
                <form action="{{ route('oltp.stok.update', $stok->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4 text-white" style="font-size: 13.5px;">
                        
                        <div class="form-group mb-3">
                            <label class="text-muted font-weight-bold mb-2">Nama Laptop <span class="text-danger">*</span></label>
                            <input type="text" name="nama_laptop" class="form-control text-white border-0 px-3 py-2" value="{{ old('nama_laptop', $stok->nama) }}" required style="background-color: #0b1329; border-radius: 6px;">
                        </div>

                        <div class="form-group mb-3">
                            <label class="text-muted font-weight-bold mb-2">Brand / Manufaktur <span class="text-danger">*</span></label>
                            <input type="text" name="brand" class="form-control text-white border-0 px-3 py-2" value="{{ old('brand', $stok->brand) }}" required style="background-color: #0b1329; border-radius: 6px;">
                        </div>

                        <div class="form-group mb-3">
                            <label class="text-muted font-weight-bold mb-2">Cabang Distribusi <span class="text-danger">*</span></label>
                            <select name="cabang" class="form-control text-white border-0 px-3" required style="background-color: #0b1329; border-radius: 6px; height: 40px;">
                                <option value="Palu" {{ old('cabang', $stok->cabang) == 'Palu' ? 'selected' : '' }}>Palu</option>
                                <option value="Donggala" {{ old('cabang', $stok->cabang) == 'Donggala' ? 'selected' : '' }}>Donggala</option>
                                <option value="Parigi" {{ old('cabang', $stok->cabang) == 'Parigi' ? 'selected' : '' }}>Parigi</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-7 form-group mb-3">
                                <label class="text-muted font-weight-bold mb-2">Harga Satuan (Rp) <span class="text-danger">*</span></label>
                                <input type="number" name="harga" class="form-control text-white border-0 px-3 py-2" value="{{ old('harga', $stok->harga) }}" required style="background-color: #0b1329; border-radius: 6px;">
                            </div>
                            <div class="col-md-5 form-group mb-3">
                                <label class="text-muted font-weight-bold mb-2">Sisa Stok <span class="text-danger">*</span></label>
                                <input type="number" name="stok" class="form-control text-white border-0 px-3 py-2" value="{{ old('stok', $stok->stok) }}" required style="background-color: #0b1329; border-radius: 6px;">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer border-0 p-3 d-flex justify-content-end" style="background-color: rgba(0,0,0,0.15); border-top: 1px solid #334155 !important; gap: 8px;">
                        <button type="button" class="btn btn-sm font-weight-bold px-3 py-2 text-muted" data-dismiss="modal" style="background: #0b1329; border: 1px solid #334155; border-radius: 6px; font-size: 12px;">Batal</button>
                        <button type="submit" class="btn btn-sm btn-info text-white font-weight-bold px-3 py-2" style="border-radius: 6px; font-size: 12px;">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- 2. POP-UP MODAL CONFIRM HAPUS LAPTOP --}}
    <div class="modal fade" id="modalHapusLaptop-{{ $stok->id }}" tabindex="-1" role="dialog" aria-labelledby="modalHapusLaptopLabel-{{ $stok->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content border-0 shadow-lg" style="background-color: #1c2541; border: 1px solid #334155 !important; border-radius: 12px;">
                <div class="modal-header border-0 p-3" style="background-color: rgba(0,0,0,0.15); border-bottom: 1px solid #334155 !important;">
                    <h5 class="modal-title text-white font-weight-bold" id="modalHapusLaptopLabel-{{ $stok->id }}" style="font-size: 14px;">
                        <i class="fas fa-exclamation-triangle text-danger mr-2"></i>Konfirmasi Hapus
                    </h5>
                    <button type="button" class="close text-white shadow-none" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                {{-- Mengarah ke rute destroy --}}
                <form action="{{ route('oltp.stok.destroy', $stok->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body p-4 text-center text-white" style="font-size: 13.5px;">
                        <p class="mb-1">Apakah Anda yakin ingin menghapus aset laptop ini?</p>
                        <strong class="text-warning d-block mt-2">{{ $stok->nama }}</strong>
                        <small class="text-muted d-block mt-1">(LP-{{ $stok->id }} - Cabang {{ $stok->cabang }})</small>
                    </div>
                    <div class="modal-footer border-0 p-3 d-flex justify-content-center" style="background-color: rgba(0,0,0,0.15); border-top: 1px solid #334155 !important; gap: 8px;">
                        <button type="button" class="btn btn-sm font-weight-bold px-3 py-2 text-muted" data-dismiss="modal" style="background: #0b1329; border: 1px solid #334155; border-radius: 6px; font-size: 12px;">Batal</button>
                        <button type="submit" class="btn btn-sm btn-danger font-weight-bold px-3 py-2" style="border-radius: 6px; font-size: 12px;">Ya, Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endforeach

@endsection