@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4" style="background-color: #0b1329; min-height: 100vh; color: #f8fafc;">
    
    <!-- HEADER HALAMAN -->
    <div class="d-flex align-items-center mb-4">
        <div class="p-3 mr-3 shadow-sm" style="background: #1c2541; border-radius: 50%; width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-file-invoice-dollar text-success" style="font-size: 18px;"></i>
        </div>
        <div>
            <h4 class="font-weight-bold text-white m-0" style="letter-spacing: 0.5px; font-size: 22px;">Tabel Fakta Penjualan & Profit (DWH)</h4>
            <small class="text-muted" style="font-size: 13px;">Struktur internal tabel `dwh_fact_penjualan` setelah transformasi pipa ETL untuk kebutuhan OLAP</small>
        </div>
    </div>

    <!-- TABEL UTAMA DWH FACT -->
    <div class="card p-4 shadow-sm" style="background: #1c2541; border: none; border-radius: 12px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h6 class="font-weight-bold text-success m-0" style="font-size: 14px;">
                <i class="fas fa-database mr-2"></i> Log Skema Bintang (Fact Table)
            </h6>
            <span class="badge text-success font-weight-bold px-3 py-2" style="background: rgba(16, 185, 129, 0.1); border-radius: 6px;">
                Total Profit Terkumpul: Rp 69.950.340.000
            </span>
        </div>

        <div class="table-responsive">
            <table class="table text-white mb-0" style="border-color: #334155;">
                <thead>
                    <tr style="border-bottom: 2px solid #334155; color: #64748b; font-size: 13px; letter-spacing: 0.5px;">
                        <th style="border: none; padding-bottom: 12px;">ID SK fakta</th>
                        <th style="border: none; padding-bottom: 12px;">ID Waktu (Key)</th>
                        <th style="border: none; padding-bottom: 12px;">ID Dim Cabang</th>
                        <th style="border: none; padding-bottom: 12px;">Qty Terjual</th>
                        <th style="border: none; padding-bottom: 12px;">Gross Revenue (Subtotal)</th>
                        <th style="border: none; padding-bottom: 12px;">Net Profit (Keuntungan)</th>
                    </tr>
                </thead>
                <tbody style="font-size: 14px; color: #cbd5e1; font-family: 'Courier New', Courier, monospace;">
                    <tr style="border-bottom: 1px solid #334155;">
                        <td>FACT-000219</td>
                        <td class="text-info">20260527</td>
                        <td>1 (Cabang Palu)</td>
                        <td>10.754 Unit</td>
                        <td>Rp 153.210.450.000</td>
                        <td class="text-success font-weight-bold">Rp 23.410.120.000</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #334155;">
                        <td>FACT-000220</td>
                        <td class="text-info">20260528</td>
                        <td>2 (Cabang Donggala)</td>
                        <td>10.680 Unit</td>
                        <td>Rp 152.842.100.000</td>
                        <td class="text-success font-weight-bold">Rp 23.120.040.000</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #334155;">
                        <td>FACT-000221</td>
                        <td class="text-info">20260529</td>
                        <td>3 (Cabang Parigi)</td>
                        <td>10.829 Unit</td>
                        <td>Rp 152.510.790.000</td>
                        <td class="text-success font-weight-bold">Rp 23.420.180.000</td>
                    </tr>
                    <tr class="font-weight-bold" style="background: rgba(255,255,255,0.02); color: #ffffff;">
                        <td colspan="3" class="text-right" style="padding: 16px;">TOTAL AGREGASI DWH:</td>
                        <td class="text-warning">32.263 Unit</td>
                        <td>Rp 458.563.340.000</td>
                        <td class="text-success" style="font-size: 15px;">Rp 69.950.340.000</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

