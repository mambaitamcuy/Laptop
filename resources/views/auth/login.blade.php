<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Arkadia LP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { 
            background-color: #0f172a; 
            height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-family: 'Segoe UI', system-ui, sans-serif; 
        }
        .login-card { 
            background: #1e293b; 
            border: none; 
            border-radius: 16px; 
            width: 100%; 
            max-width: 430px; 
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.4); 
        }
        /* Perbaikan Kontras Form Sesuai SOP Keamanan & Kenyamanan Mata */
        .form-label {
            color: #cbd5e1 !important; /* Abu terang awet muda */
            font-weight: 500;
        }
        .input-group-text {
            background-color: #334155;
            border: 1px solid #475569;
            color: #94a3b8;
        }
        .form-control { 
            background-color: #334155; 
            border: 1px solid #475569; 
            color: #ffffff !important; /* Teks ketikan wajib putih bersih */
            font-size: 15px;
        }
        .form-control:focus { 
            background-color: #334155; 
            border-color: #3b82f6; 
            color: #fff; 
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.25); 
        }
        .form-control::placeholder { 
            color: #94a3b8 !important; /* Placeholder terbaca jelas */
            opacity: 1; 
        }
        /* Tombol Google Custom Premium */
        .btn-google {
            background-color: #ffffff;
            color: #1e293b;
            font-weight: 600;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease-in-out;
        }
        .btn-google:hover {
            background-color: #f8fafc;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(255,255,255,0.1);
            color: #0f172a;
        }
        /* Pembatas Garis */
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            color: #64748b;
            margin: 22px 0;
            font-size: 12px;
            letter-spacing: 1px;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #334155;
        }
        .divider:not(:empty)::before { margin-right: .75em; }
        .divider:not(:empty)::after { margin-left: .75em; }
    </style>
</head>
<body>

<div class="card login-card p-4 mx-3">
    <div class="text-center mb-4">
        <div class="text-primary display-5 mb-2">
            <i class="fa-solid fa-chart-pie"></i>
        </div>
        <h4 class="fw-bold text-white mb-1">Arkadia LP</h4>
        <p style="color: #94a3b8;" class="small">Silakan masuk ke sistem analitik DWH</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger py-2 small border-0 text-white text-center mb-3" style="background-color: #e11d48 !important;">
            <i class="fa-solid fa-triangle-exclamation me-1"></i> {{ $errors->first() }}
        </div>
    @endif

    <form action="#" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label small">Alamat Email</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                <input type="email" name="email" class="form-control" placeholder="nama@email.com" autocomplete="off" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label small">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
        </div>

        <div class="mb-4 form-check">
            <input type="checkbox" class="form-check-input" id="remember" name="remember">
            <label class="form-check-label small" style="color: #cbd5e1;" for="remember">Ingat Sesi Saya</label>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm mb-1">
            <i class="fa-solid fa-right-to-bracket me-1"></i> Masuk Sistem
        </button>
    </form>

    <div class="divider fw-bold">ATAU</div>

    <a href="{{ route('google.login') }}" class="btn btn-google w-100 py-2 rounded-3 text-decoration-none d-flex align-items-center justify-content-center">
        <i class="fa-brands fa-google text-danger me-2 fs-5"></i> Masuk lewat Google
    </a>
</div>

</body>
</html>