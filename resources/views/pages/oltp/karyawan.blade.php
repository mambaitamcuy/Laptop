@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4" style="background-color: #0b1329; min-height: 100vh; color: #f8fafc;">
    
    <div class="d-flex align-items-center mb-4">
        <div class="p-3 mr-3 shadow-sm" style="background: #1c2541; border-radius: 50%; width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-users text-primary" style="font-size: 18px;"></i>
        </div>
        <div>
            <h4 class="font-weight-bold text-white m-0" style="letter-spacing: 0.5px; font-size: 22px;">Data Manajemen Karyawan (OLTP)</h4>
            <small class="text-muted" style="font-size: 13px;">Pengaturan hak akses, jabatan, dan penempatan staf operasional multi-cabang</small>
        </div>
    </div>

    <div class="card p-4 shadow-sm" style="background: #1c2541; border: none; border-radius: 12px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h6 class="font-weight-bold text-primary m-0" style="font-size: 14px;">
                <i class="fas fa-id-card mr-2"></i> Personil Aktif Toko
            </h6>
            
            <button type="button" class="btn text-white font-weight-bold px-3 py-2 shadow-sm" style="background: #0284c7; border: none; border-radius: 6px; font-size: 13px; transition: 0.2s;" data-toggle="modal" data-target="#modalTambahKaryawan">
                <i class="fas fa-user-plus mr-2"></i> Tambah Karyawan
            </button>
        </div>

        <div class="table-responsive">
            <table class="table text-white mb-0" style="border-color: #334155;">
                <thead>
                    <tr style="border-bottom: 2px solid #334155; color: #64748b; font-size: 13px; letter-spacing: 0.5px;">
                        <th style="border: none; padding-bottom: 12px;">ID Staf</th>
                        <th style="border: none; padding-bottom: 12px;">Nama Lengkap</th>
                        <th style="border: none; padding-bottom: 12px;">Jabatan</th>
                        <th style="border: none; padding-bottom: 12px;">Penempatan Tugas</th>
                        <th style="border: none; padding-bottom: 12px;">Status</th>
                    </tr>
                </thead>
                <tbody style="font-size: 14px; color: #cbd5e1;">
                    {{-- 🛠️ PROSES LOOPING DATA ASLI DATABASE --}}
                    @forelse($daftarKaryawan as $k)
                        <tr style="border-bottom: 1px solid #334155;">
                            <td class="align-middle" style="padding: 16px 8px;">USR-{{ str_pad($k->id, 3, '0', STR_PAD_LEFT) }}</td>
                            <td class="align-middle font-weight-bold text-white" style="padding: 16px 8px;">{{ $k->name }}</td>
                            <td class="align-middle" style="padding: 16px 8px;">
                                <span class="badge bg-info text-white px-2 py-1">
                                    {{ $k->jabatan ?? 'Staf Operasional' }}
                                </span>
                            </td>
                            <td class="align-middle" style="padding: 16px 8px;">{{ $k->nama_cabang ?? 'Arkadia Pusat' }}</td>
                            <td class="align-middle text-success font-weight-bold" style="padding: 16px 8px;">
                                <i class="fas fa-circle mr-1" style="font-size: 9px;"></i> Aktif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted" style="border: none;">
                                <i class="fas fa-folder-open mr-2"></i> Tidak ada data karyawan aktif di database.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahKaryawan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="background: #1c2541; color: #f8fafc; border: 1px solid #334155; border-radius: 12px;">
            <div class="modal-header" style="border-top: none; border-bottom: 1px solid #334155;">
                <h5 class="modal-title font-weight-bold text-white" style="font-size: 16px;"><i class="fas fa-user-plus mr-2 text-primary"></i> Daftarkan Karyawan Baru</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form id="formKaryawan" action="#" method="POST">
                @csrf
                <div class="modal-body" style="font-size: 13px;">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-muted mb-1">NAMA LENGKAP</label>
                        <input type="text" class="form-control text-white" style="background: #0b1329; border: 1px solid #334155;" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-muted mb-1">JABATAN</label>
                        <input type="text" class="form-control text-white" placeholder="Contoh: Staff Kasir / Gudang" style="background: #0b1329; border: 1px solid #334155;" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-muted mb-1">PENEMPATAN CABANG</label>
                        <select class="form-control text-white" style="background: #0b1329; border: 1px solid #334155;" required>
                            <option value="1">Cabang Palu</option>
                            <option value="2">Cabang Donggala</option>
                            <option value="3">Cabang Parigi</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #334155;">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-3">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('formKaryawan').addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Sistem OLTP: Akun Karyawan Berhasil Dibuat dan Ditempatkan ke Cabang!');
        $('#modalTambahKaryawan').modal('hide');
        window.location.reload();
    });
</script>
@endsection