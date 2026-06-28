<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ArkadiaLP - Management Portal</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0b1329;
            overflow-x: hidden;
        }

        /* Struktur Wrapper Layout Flexbox */
        #wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
        }

        /* Sidebar Styling (Tema Gelap Premium) */
        #sidebar {
            min-width: 260px;
            max-width: 260px;
            background: #1c2541;
            color: #f8fafc;
            min-height: 100vh;
            transition: all 0.3s;
            border-right: 1px solid #334155;
            position: fixed;
            height: 100%;
            overflow-y: auto;
            z-index: 1000;
        }

        /* Main Content Container Adjustments */
        #content-wrapper {
            width: 100%;
            padding-left: 260px; /* Memberi ruang agar tidak tertutup sidebar yang fixed */
            min-height: 100vh;
            background-color: #0b1329;
        }

        /* Custom Hover & Active State Menu Sidebar */
        .nav-custom-link {
            transition: all 0.2s ease-in-out;
            border-left: 3px solid transparent;
            font-weight: 500;
            color: #94a3b8 !important;
        }

        .nav-custom-link:hover {
            background: rgba(255, 255, 255, 0.05);
            color: #ffffff !important;
            padding-left: 12px !important;
        }

        /* Style Khusus untuk Menu Aktif OLTP (Biru) */
        .nav-custom-link.active-oltp {
            background: rgba(59, 130, 246, 0.15);
            color: #3b82f6 !important;
            border-left: 3px solid #3b82f6;
            font-weight: 600;
        }

        /* Style Khusus untuk Menu Aktif DWH (Cyan/Info) */
        .nav-custom-link.active-dwh {
            background: rgba(6, 182, 212, 0.15);
            color: #06b6d4 !important;
            border-left: 3px solid #06b6d4;
            font-weight: 600;
        }

        /* Efek Animasi Berkedip untuk Server Status Indicators */
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: .4; }
        }

        /* Custom Scrollbar untuk Sidebar */
        #sidebar::-webkit-scrollbar {
            width: 5px;
        }
        #sidebar::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 4px;
        }
    </style>
</head>
<body>

    <div id="wrapper">
        
        <nav id="sidebar">
            <div class="sidebar-brand p-4">
                <h4 class="text-white font-weight-bold m-0" style="letter-spacing: 0.5px;">ARKADIALP</h4>
                <small class="text-muted font-weight-bold" style="font-size: 10px; letter-spacing: 0.5px;">MANAGEMENT PORTAL</small>
            </div>

            <hr style="border-top: 1px solid #334155;" class="mx-3 my-0">

            <div class="px-3 pt-4">
                <small class="text-muted font-weight-bold d-block mb-2" style="font-size: 10px; letter-spacing: 1px;">DATA OPERASIONAL (OLTP)</small>
                <ul class="nav flex-column" style="gap: 4px;">
                    <li class="nav-item">
                        <a class="nav-link p-2.5 d-block nav-custom-link {{ Request::routeIs('oltp.dashboard') ? 'active-oltp' : '' }}" href="{{ route('oltp.dashboard') }}" style="border-radius: 6px; font-size: 13.5px;">
                            <i class="fas fa-desktop mr-2 text-center" style="width: 20px;"></i> Dashboard Operasional
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link p-2.5 d-block nav-custom-link {{ Request::routeIs('oltp.stok') ? 'active-oltp' : '' }}" href="{{ route('oltp.stok') }}" style="border-radius: 6px; font-size: 13.5px;">
                            <i class="fas fa-boxes mr-2 text-center" style="width: 20px;"></i> Stok Laptop Gudang
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link p-2.5 d-block nav-custom-link {{ Request::routeIs('oltp.transaksi') ? 'active-oltp' : '' }}" href="{{ route('oltp.transaksi') }}" style="border-radius: 6px; font-size: 13.5px;">
                            <i class="fas fa-cash-register mr-2 text-center" style="width: 20px;"></i> Transaksi Kasir
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link p-2.5 d-block nav-custom-link {{ Request::routeIs('oltp.karyawan') ? 'active-oltp' : '' }}" href="{{ route('oltp.karyawan') }}" style="border-radius: 6px; font-size: 13.5px;">
                            <i class="fas fa-users mr-2 text-center" style="width: 20px;"></i> Manajemen Karyawan
                        </a>
                    </li>
                </ul>
            </div>

            <div class="my-3"></div>

            <div class="px-3">
                <small class="text-muted font-weight-bold d-block mb-2" style="font-size: 10px; letter-spacing: 1px;">ANALITIK EXECUTIVE (DWH)</small>
                <ul class="nav flex-column" style="gap: 4px;">
                    <li class="nav-item">
                        <a class="nav-link p-2.5 d-block nav-custom-link {{ Request::routeIs('dwh.dashboard') ? 'active-dwh' : '' }}" href="{{ route('dwh.dashboard') }}" style="border-radius: 6px; font-size: 13.5px;">
                            <i class="fas fa-chart-pie mr-2 text-center" style="width: 20px;"></i> Dashboard Eksekutif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link p-2.5 d-block nav-custom-link {{ Request::routeIs('dwh.profit') ? 'active-dwh' : '' }}" href="{{ route('dwh.profit') }}" style="border-radius: 6px; font-size: 13.5px;">
                            <i class="fas fa-table mr-2 text-center" style="width: 20px;"></i> Tabel Fakta Profit
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link p-2.5 d-block nav-custom-link {{ Request::routeIs('dwh.cabang') ? 'active-dwh' : '' }}" href="{{ route('dwh.cabang') }}" style="border-radius: 6px; font-size: 13.5px;">
                            <i class="fas fa-map-marked-alt mr-2 text-center" style="width: 20px;"></i> Analisis Wilayah Cabang
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="position-absolute b-0 w-100 p-3 text-center" style="bottom: 0; background: #0b1329; border-top: 1px solid #334155;">
                <small class="text-muted d-block" style="font-size: 11px;">Logged in as Senior Admin</small>
                <span class="badge badge-success px-2 py-1 mt-1 animate-pulse" style="font-size: 10px;">V2.0 Modular Alpha</span>
            </div>
        </nav>

        <div id="content-wrapper">
            
            @yield('content')
            
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    @yield('scripts')

</body>
</html>