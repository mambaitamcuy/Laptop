<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Arkadia Analytics</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #0f172a; height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Segoe UI', sans-serif; }
        .login-card { background: #1e293b; border: none; border-radius: 16px; width: 100%; max-width: 420px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.3); color: #f8fafc; }
        .form-control { background-color: #334155; border: 1px solid #475569; color: #fff; }
        .form-control:focus { background-color: #334155; border-color: #3b82f6; color: #fff; box-shadow: none; }
        .btn-google { background-color: #fff; color: #1e293b; font-weight: 600; transition: all 0.2s; text-decoration: none; }
        .btn-google:hover { background-color: #f1f5f9; transform: translateY(-1px); }
    </style>
</head>
<body>

<div class="card login-card p-4 mx-3">
    <div class="text-center mb-4">
        <h3 class="fw-bold text-white mb-1"><i class="fa-solid fa-layer-group text-primary me-2"></i>Arkadia LP</h3>
        <p class="text-muted small">Silakan masuk ke sistem analitik DWH</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger py-2 small border-0 text-white text-center shadow-sm mb-3" style="background-color: #e11d48 !important;" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-1"></i> 
            <span class="fw-bold">{{ $errors->first() }}</span>
        </div>
    @endif

    <form action="/login" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label small text-muted">Alamat Email</label>
            <input type="email" name="email" class="form-control rounded-3" placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
        </div>

        <div class="mb-3">
            <label class="form-label small text-muted">Password</label>
            <input type="password" name="password" class="form-control rounded-3" placeholder="••••••••" required>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="remember" id="remember" class="form-check-input">
            <label class="form-check-label small text-muted" for="remember">Ingat Saya</label>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 rounded-3 fw-bold shadow-sm mb-3">
            Masuk Sistem
        </button>
    </form>

    <div class="position-relative my-4 text-center">
        <hr class="border-secondary">
        <span class="position-absolute top-50 start-50 translate-middle px-3 small text-muted" style="background: #1e293b;">Atau</span>
    </div>

    <a href="{{ route('google.login') }}" class="btn btn-google w-100 py-2 rounded-3 d-flex align-items-center justify-content-center gap-2 shadow-sm">
        <img src="https://fonts.gstatic.com/s/i/productlogos/googleg/v6/web-24dp/logo_googleg_color_24dp.png" alt="Google" style="width: 18px; height: 18px;">
        Masuk lewat Google
    </a>
</div>

</body>
</html>