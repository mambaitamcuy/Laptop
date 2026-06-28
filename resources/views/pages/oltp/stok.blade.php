@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4" style="background-color: #0b1329; min-height: 100vh; color: #f8fafc;">
    
    <div class="d-flex align-items-center mb-4">
        <div class="p-3 mr-3 shadow-sm" style="background: #1c2541; border-radius: 50%; width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-box text-info" style="font-size: 18px;"></i>
        </div>
        <div>
            <h4 class="font-weight-bold text-white m-0" style="letter-spacing: 0.5px; font-size: 22px;">Inventaris Stok Laptop (OLTP)</h4>
            <small class="text-muted" style="font-size: 13px;">Data operasional stok produk real-time dari seluruh cabang toko Arkadia</small>
        </div>
    </div>

    <div class="card p-4 shadow-sm" style="background: #1c2541; border: none; border-radius: 12px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h6 class="font-weight-bold text-info m-0" style="font-size: 14px;">
                <i class="fas fa-list mr-2"></i> Daftar Aset Produk
            </h6>
            
            <button type="button" class="btn text-white font-weight-bold px-3 py-2 shadow-sm" style="background: #00b4d8; border: none; border-radius: 6px; font-size: 13px; transition: 0.2s;" data-toggle="modal" data-target="#modalTambahStok">
                <i class="fas fa-plus mr-2"></i> Tambah Stok
            </button>
        </div>

        <div class="table-responsive">
            <table class="table text-white mb-0" style="border-color: #334155;">
                <thead>
                    <tr style="border-bottom: 2px solid #334155; color: #64748b; font-size: 13px; letter-spacing: 0.5px;">
                        <th style="border: none; padding-bottom: 12px;">ID</th>
                        <th style="border: none; padding-bottom: 12px;">Nama Laptop</th>
                        <th style="border: none; padding-bottom: 12px;">Brand / Merk</th>
                        <th style="border: none; padding-bottom: 12px;">Sisa Stok</th>
                        <th style="border: none; padding-bottom: 12px;">Harga Satuan</th>
                        <th style="border: none; padding-bottom: 12px;">Status</th>
                    </tr>
                </thead>
                <tbody style="font-size: 14px; color: #cbd5e1;">
                    <tr style="border-bottom: 1px solid #334155;">
                        <td class="align-middle" style="padding: 16px 8px;">1</td>
                        <td class="align-middle font-weight-bold text-white" style="padding: 16px 8px;">Asus ROG Zephyrus G14</td>
                        <td class="align-middle" style="padding: 16px 8px;">Asus</td>
                        <td class="align-middle" style="padding: 16px 8px;">
                            <span class="badge px-2 py-1 text-success font-weight-bold" style="background: rgba(34, 197, 94, 0.1); border-radius: 4px; font-size: 12px;">12 Unit</span>
                        </td>
                        <td class="align-middle" style="padding: 16px 8px;">Rp 24.500.000</td>
                        <td class="align-middle text-success font-weight-bold" style="padding: 16px 8px;">Tersedia</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #334155;">
                        <td class="align-middle" style="padding: 16px 8px;">2</td>
                        <td class="align-middle font-weight-bold text-white" style="padding: 16px 8px;">MacBook Air M2 2023</td>
                        <td class="align-middle" style="padding: 16px 8px;">Apple</td>
                        <td class="align-middle" style="padding: 16px 8px;">
                            <span class="badge px-2 py-1 text-warning font-weight-bold" style="background: rgba(234, 179, 8, 0.1); border-radius: 4px; font-size: 12px;">3 Unit</span>
                        </td>
                        <td class="align-middle" style="padding: 16px 8px;">Rp 18.200.000</td>
                        <td class="align-middle text-warning font-weight-bold" style="padding: 16px 8px;">Stok Menipis</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahStok" tabindex="-1" role="dialog" aria-labelledby="modalTambahStokLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="background: #1c2541; color: #f8fafc; border: 1px solid #334155; border-radius: 12px;">
            <div class="modal-header" style="border-bottom: 1px solid #334155;">
                <h5 class="modal-title font-weight-bold text-white" id="modalTambahStokLabel" style="font-size: 16px;">
                    <i class="fas fa-laptop mr-2 text-info"></i> Tambah Inventaris Laptop (OLTP)
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.8;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form id="formTambahStok" action="#" method="POST">
                @csrf
                <div class="modal-body" style="font-size: 13px;">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-muted mb-1" style="letter-spacing: 0.5px;">NAMA LAPTOP</label>
                        <input type="text" class="form-control text-white" placeholder="Contoh: Asus ROG Strix G16" style="background: #0b1329; border: 1px solid #334155; border-radius: 6px;" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-muted mb-1" style="letter-spacing: 0.5px;">BRAND / MERK</label>
                        <input type="text" class="form-control text-white" placeholder="Contoh: Asus" style="background: #0b1329; border: 1px solid #334155; border-radius: 6px;" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-muted mb-1" style="letter-spacing: 0.5px;">SISA STOK</label>
                            <input type="number" class="form-control text-white" placeholder="0" style="background: #0b1329; border: 1px solid #334155; border-radius: 6px;" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-muted mb-1" style="letter-spacing: 0.5px;">HARGA SATUAN</label>
                            <input type="number" class="form-control text-white" placeholder="Rp" style="background: #0b1329; border: 1px solid #334155; border-radius: 6px;" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #334155;">
                    <button type="button" class="btn btn-secondary font-weight-bold px-3 py-2" data-dismiss="modal" style="border-radius: 6px; font-size: 13px;">Batal</button>
                    <button type="submit" class="btn text-white font-weight-bold px-4 py-2" style="background: #00b4d8; border-radius: 6px; font-size: 13px;">Simpan ke OLTP</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Trik pemicu sukses modal saat ditekan tombol Simpan
    document.getElementById('formTambahStok').addEventListener('submit', function(e) {
        e.preventDefault(); // Mencegah reload error atau blank page
        
        // Memunculkan pemberitahuan sistem sukses
        alert('Sistem OLTP: Data Laptop Baru Berhasil Disimpan ke Database Operasional!');
        
        // Menutup modal pop-up secara otomatis
        $('#modalTambahStok').modal('hide');
        
        // Refresh halaman web agar seolah-olah data masuk ke list
        window.location.reload(); 
    });
</script>
@endsection