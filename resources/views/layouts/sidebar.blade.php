<div class="d-flex flex-column flex-shrink-0 p-3 text-white" style="width: 250px; background-color: #1c2541; min-height: 100vh;">
    <a href="#" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <i class="fas fa-laptop-code text-primary mr-2" style="font-size: 24px;"></i>
        <span class="fs-4 font-weight-bold" style="letter-spacing: 0.5px;">Arkadia LP</span>
    </a>
    <hr style="border-color: #334155;">

    <ul class="nav nav-pills flex-column mb-auto" style="gap: 5px;">
        <li class="nav-item">
            <small class="text-muted d-block px-2 mb-1 text-uppercase" style="font-size: 10px; letter-spacing: 1px;">Menu Operasional (OLTP)</small>
        </li>
        
        <li>
            <a href="{{ route('oltp.dashboard') }}" class="nav-link text-white px-2 py-2 d-flex align-items-center rounded hover-effect">
                <i class="fas fa-chart-pie mr-2 text-muted" style="width: 20px;"></i> Dashboard OLTP
            </a>
        </li>
        <li>
            <a href="{{ route('oltp.stok') }}" class="nav-link text-white px-2 py-2 d-flex align-items-center rounded hover-effect">
                <i class="fas fa-boxes mr-2 text-muted" style="width: 20px;"></i> Stok Laptop
            </a>
        </li>
        <li>
            <a href="{{ route('oltp.transaksi') }}" class="nav-link text-white px-2 py-2 d-flex align-items-center rounded hover-effect">
                <i class="fas fa-cash-register mr-2 text-muted" style="width: 20px;"></i> Transaksi Kasir
            </a>
        </li>

        {{-- 🛑 PROTEKSI 1: Hanya Super Admin (Owner) dan Admin (Manager) yang bisa melihat menu karyawan --}}
        @can('access-management')
        <li class="mt-2">
            <a href="{{ route('oltp.karyawan') }}" class="nav-link text-white px-2 py-2 d-flex align-items-center rounded hover-effect" style="background: rgba(2, 132, 199, 0.2);">
                <i class="fas fa-users mr-2 text-primary" style="width: 20px;"></i> Manajemen Karyawan
            </a>
        </li>
        @endcan

        {{-- 🛑 PROTEKSI 2: Karyawan biasa tidak boleh melihat menu eksekutif DWH ini --}}
        @can('access-management')
        <li class="mt-4">
            <small class="text-muted d-block px-2 mb-1 text-uppercase" style="font-size: 10px; letter-spacing: 1px;">Analitik Eksekutif (DWH)</small>
        </li>
        <li>
            <a href="{{ route('dwh.dashboard') }}" class="nav-link text-white px-2 py-2 d-flex align-items-center rounded hover-effect">
                <i class="fas fa-chart-line mr-2 text-success" style="width: 20px;"></i> Dashboard DWH
            </a>
        </li>
        <li>
            <a href="{{ route('dwh.profit') }}" class="nav-link text-white px-2 py-2 d-flex align-items-center rounded hover-effect">
                <i class="fas fa-wallet mr-2 text-success" style="width: 20px;"></i> Analisis Profit
            </a>
        </li>
        <li>
            <a href="{{ route('dwh.cabang') }}" class="nav-link text-white px-2 py-2 d-flex align-items-center rounded hover-effect">
                <i class="fas fa-store mr-2 text-success" style="width: 20px;"></i> Performa Cabang
            </a>
        </li>
        @endcan

        <li class="mt-3">
            <hr style="border-color: #334155; margin: 5px 0;">
        </li>
        <li>
            <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin keluar dari sistem Arkadia LP?')">
                @csrf
                <button type="submit" class="nav-link text-danger px-2 py-2 d-flex align-items-center rounded hover-effect-danger" style="background: none; border: none; width: 100%; text-align: left; font-size: inherit; font-family: inherit; cursor: pointer;">
                    <i class="fas fa-sign-out-alt mr-2" style="width: 20px;"></i> Keluar Sistem
                </button>
            </form>
        </li>
    </ul>
    
    <hr style="border-color: #334155;">
    
    <div class="dropdown">
        <div class="d-flex align-items-center text-white text-decoration-none">
            <div class="bg-primary text-center rounded-circle font-weight-bold mr-2" style="width: 32px; height: 32px; line-height: 32px;">
                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
            </div>
            <div style="line-height: 1.2;">
                <span class="d-block font-weight-bold" style="font-size: 13px;">{{ Auth::user()->name ?? 'Guest' }}</span>
                <span class="text-muted text-capitalize" style="font-size: 11px;">{{ Auth::user()->role ?? 'No Role' }}</span>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-effect:hover {
        background-color: #0b1329;
        color: #0284c7 !important;
    }
    .hover-effect-danger:hover {
        background-color: rgba(220, 53, 69, 0.15);
        color: #dc3545 !important;
    }
</style>