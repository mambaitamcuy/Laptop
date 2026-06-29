<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MFA Verification - Arkadia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { 
            background-color: #0f172a; 
            height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-family: 'Segoe UI', sans-serif; 
        }
        .otp-card { 
            background: #1e293b; 
            border: 1px solid #334155; 
            border-radius: 16px; 
            width: 100%; 
            max-width: 420px; 
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5), 0 10px 10px -5px rgba(0, 0, 0, 0.4); 
            color: #f8fafc; 
        }
        .form-control { 
            background-color: #334155; 
            border: 1px solid #475569; 
            color: #fff; 
            font-size: 24px; 
            letter-spacing: 6px; 
            text-align: center; 
        }
        .form-control:focus { 
            background-color: #334155; 
            border-color: #3b82f6; 
            color: #fff; 
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.25); 
        }
        /* 🎨 FIX WARNA TEKS BIAR KONTRAS & JELAS DIBACA */
        .text-slate-400 { 
            color: #94a3b8 !important; 
        }
        /* ✨ EFEK HOVER TOMBOL KEMBALI */
        .btn-back {
            color: #94a3b8;
            font-size: 0.875rem;
            transition: all 0.2s ease-in-out;
        }
        .btn-back:hover {
            color: #3b82f6;
            text-shadow: 0 0 8px rgba(59, 130, 246, 0.4);
        }
    </style>
</head>
<body>

<div class="card otp-card p-4 mx-3">
    <div class="text-center mb-4">
        <div class="display-6 text-primary mb-2">
            <i class="fa-solid fa-envelope-open-text"></i>
        </div>
        <h4 class="fw-bold text-white mb-2">Cek Email Anda</h4>
        <p class="text-slate-400 small px-2">
            Kami telah mengirimkan 6-digit kode verifikasi keamanan ke alamat email terdaftar Anda.
        </p>
    </div>

    {{-- Alert Pesan Error Validasi OTP --}}
    @if ($errors->any())
        <div class="alert alert-danger py-2 small border-0 text-white text-center mb-3" style="background-color: #e11d48 !important;" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-1"></i> {{ $errors->first() }}
        </div>
    @endif

    {{-- Form Verifikasi OTP --}}
    <form action="{{ route('otp.verify.submit') }}" method="POST">
        @csrf
        <div class="mb-4">
            <input type="text" name="otp" class="form-control rounded-3 py-2 fw-bold" placeholder="000000" maxlength="6" autocomplete="off" required autofocus>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 rounded-3 fw-bold shadow-sm mb-2">
            Verifikasi Kode
        </button>
    </form>
    
    {{-- Tombol Kembali yang Sudah Diperbaiki Kecerahannya --}}
    <div class="text-center mt-3">
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-link btn-back text-decoration-none p-0 m-0 border-0" style="vertical-align: baseline;">
                <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Login
            </button>
        </form>
    </div>
</div>

</body>
</html>