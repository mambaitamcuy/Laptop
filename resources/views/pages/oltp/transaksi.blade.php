@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4" style="background-color: #0b1329; min-height: 100vh; color: #f8fafc;">
    
    <!-- HEADER HALAMAN (OLTP TRANSAKSI) -->
    <div class="d-flex align-items-center mb-4">
        <div class="p-3 mr-3 shadow-sm" style="background: #1c2541; border-radius: 50%; width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-shopping-cart text-warning" style="font-size: 18px;"></i>
        </div>
        <div>
            <h4 class="font-weight-bold text-white m-0" style="letter-spacing: 0.5px; font-size: 22px;">Transaksi Kasir Harian (OLTP)</h4>
            <small class="text-muted" style="font-size: 13px;">Pencatatan nota penjualan langsung toko operasional real-time</small>
        </div>
    </div>

    <!-- KARTU TABEL LOG PENJUALAN -->
    <div class="card p-4 shadow-sm" style="background: #1c2541; border: none; border-radius: 12px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h6 class="font-weight-bold m-0" style="font-size: 14px; color: #f59e0b;">
                <i class="fas fa-history mr-2"></i> Log Penjualan Hari Ini
            </h6>
            
            <!-- Tombol Interaktif buat Tambah Transaksi Dadakan Pas Demo -->
            <button type="button" class="btn text-white font-weight-bold px-3 py-2 shadow-sm" style="background: #f59e0b; border: none; border-radius: 6px; font-size: 13px; transition: 0.2s;" data-toggle="modal" data-target="#modalTambahTransaksi">
                <i class="fas fa-plus mr-2"></i> Transaksi Baru
            </button>
        </div>

        <!-- STRUKTUR TABEL DATA -->
        <div class="table-responsive">
            <table class="table text-white mb-0" style="border-color: #334155;">
                <thead>
                    <tr style="border-bottom: 2px solid #334155; color: #64748b; font-size: 13px; letter-spacing: 0.5px;">
                        <th style="border: none; padding-bottom: 12px;">No. Nota</th>
                        <th style="border: none; padding-bottom: 12px;">Tanggal & Waktu</th>
                        <th style="border: none; padding-bottom: 12px;">Produk Terjual</th>
                        <th style="border: none; padding-bottom: 12px;">Jumlah</th>
                        <th style="border: none; padding-bottom: 12px;">Total Pembayaran</th>
                        <th style="border: none; padding-bottom: 12px;">Metode Bayar</th>
                    </tr>
                </thead>
                <tbody style="font-size: 14px; color: #cbd5e1;">
                    <!-- Baris 1 (Asli dari Screenshot) -->
                    <tr style="border-bottom: 1px solid #334155;">
                        <td class="align-middle font-weight-bold" style="padding: 16px 8px;">#TRX-00821</td>
                        <td class="align-middle" style="padding: 16px 8px;">23 Juni 2026 - 10:15</td>
                        <td class="align-middle text-white" style="padding: 16px 8px;">Lenovo Legion Slim 5</td>
                        <td class="align-middle" style="padding: 16px 8px;">1 Unit</td>
                        <td class="align-middle font-weight-bold" style="padding: 16px 8px;">Rp 19.800.000</td>
                        <td class="align-middle" style="padding: 16px 8px;">
                            <span class="badge text-white px-2 py-1 font-weight-bold" style="background: #007bf5; border-radius: 4px; font-size: 11px;">Midtrans (Snap)</span>
                        </td>
                    </tr>
                    <!-- Baris 2 (Tambahan Realistis) -->
                    <tr style="border-bottom: 1px solid #334155;">
                        <td class="align-middle font-weight-bold" style="padding: 16px 8px;">#TRX-00822</td>
                        <td class="align-middle" style="padding: 16px 8px;">23 Juni 2026 - 11:30</td>
                        <td class="align-middle text-white" style="padding: 16px 8px;">Asus ROG Zephyrus G14</td>
                        <td class="align-middle" style="padding: 16px 8px;">1 Unit</td>
                        <td class="align-middle font-weight-bold" style="padding: 16px 8px;">Rp 24.500.000</td>
                        <td class="align-middle" style="padding: 16px 8px;">
                            <span class="badge text-white px-2 py-1 font-weight-bold" style="background: #10b981; border-radius: 4px; font-size: 11px;">Tunai / Cash</span>
                        </td>
                    </tr>
                    <!-- Baris 3 (Tambahan Realistis) -->
                    <tr style="border-bottom: 1px solid #334155;">
                        <td class="align-middle font-weight-bold" style="padding: 16px 8px;">#TRX-00823</td>
                        <td class="align-middle" style="padding: 16px 8px;">23 Juni 2026 - 13:05</td>
                        <td class="align-middle text-white" style="padding: 16px 8px;">MacBook Air M2 2023</td>
                        <td class="align-middle" style="padding: 16px 8px;">2 Unit</td>
                        <td class="align-middle font-weight-bold" style="padding: 16px 8px;">Rp 36.400.000</td>
                        <td class="align-middle" style="padding: 16px 8px;">
                            <span class="badge text-white px-2 py-1 font-weight-bold" style="background: #007bf5; border-radius: 4px; font-size: 11px;">Midtrans (Snap)</span>
                        </td>
                    </tr>
                    <!-- Baris 4 (Tambahan Realistis) -->
                    <tr style="border-bottom: 1px solid #334155;">
                        <td class="align-middle font-weight-bold" style="padding: 16px 8px;">#TRX-00824</td>
                        <td class="align-middle" style="padding: 16px 8px;">23 Juni 2026 - 14:20</td>
                        <td class="align-middle text-white" style="padding: 16px 8px;">Asus ROG Zephyrus G14</td>
                        <td class="align-middle" style="padding: 16px 8px;">1 Unit</td>
                        <td class="align-middle font-weight-bold" style="padding: 16px 8px;">Rp 24.500.000</td>
                        <td class="align-middle" style="padding: 16px 8px;">
                            <span class="badge text-white px-2 py-1 font-weight-bold" style="background: #6366f1; border-radius: 4px; font-size: 11px;">Transfer Bank</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL POP-UP INPUT TRANSAKSI BARU -->
<div class="modal fade" id="modalTambahTransaksi" tabindex="-1" role="dialog" aria-labelledby="modalTambahTransaksiLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="background: #1c2541; color: #f8fafc; border: 1px solid #334155; border-radius: 12px;">
            <div class="modal-header" style="border-bottom: 1px solid #334155;">
                <h5 class="modal-title font-weight-bold text-white" id="modalTambahTransaksiLabel" style="font-size: 16px;">
                    <i class="fas fa-cash-register mr-2 text-warning"></i> Input Transaksi Kasir Baru
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.8;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form id="formTambahTransaksi" action="#" method="POST">
                @csrf
                <div class="modal-body" style="font-size: 13px;">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-muted mb-1" style="letter-spacing: 0.5px;">PRODUK LAPTOP</label>
                        <select class="form-control text-white" style="background: #0b1329; border: 1px solid #334155; border-radius: 6px;" required>
                            <option value="">-- Pilih Laptop --</option>
                            <option value="1">Asus ROG Zephyrus G14</option>
                            <option value="2">MacBook Air M2 2023</option>
                            <option value="3">Lenovo Legion Slim 5</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group mb-3">
                            <label class="font-weight-bold text-muted mb-1" style="letter-spacing: 0.5px;">QTY</label>
                            <input type="number" class="form-control text-white" value="1" min="1" style="background: #0b1329; border: 1px solid #334155; border-radius: 6px;" required>
                        </div>
                        <div class="col-md-8 form-group mb-3">
                            <label class="font-weight-bold text-muted mb-1" style="letter-spacing: 0.5px;">METODE PEMBAYARAN</label>
                            <select class="form-control text-white" style="background: #0b1329; border: 1px solid #334155; border-radius: 6px;" required>
                                <option value="Midtrans">Midtrans (Snap)</option>
                                <option value="Cash">Tunai / Cash</option>
                                <option value="Transfer">Transfer Bank</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #334155;">
                    <button type="button" class="btn btn-secondary font-weight-bold px-3 py-2" data-dismiss="modal" style="border-radius: 6px; font-size: 13px;">Batal</button>
                    <button type="submit" class="btn text-white font-weight-bold px-4 py-2" style="background: #f59e0b; border-radius: 6px; font-size: 13px;">Selesaikan Pembayaran</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Handler Simulasi Transaksi Sukses Berhasil
    document.getElementById('formTambahTransaksi').addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Sistem OLTP: Transaksi Penjualan Berhasil Diproses & Nota Dicetak!');
        $('#modalTambahTransaksi').modal('hide');
        window.location.reload();
    });
</script>
@endsection