<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arkadia LP - Welcome</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body style="background-color: #0b1329; height: 100vh; display: flex; align-items: center; justify-content: center; color: #f8fafc; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">

    <div class="text-center animate-fade">
        <div class="mb-4">
            <i class="fas fa-laptop-code text-primary shadow-sm" style="font-size: 70px; filter: drop-shadow(0 0 15px rgba(2, 132, 199, 0.6));"></i>
        </div>
        <h1 class="font-weight-bold text-white mb-2" style="letter-spacing: 1px;">ARKADIA LP</h1>
        <p class="text-muted trade-text enterprise-sub mb-5" style="max-width: 500px; font-size: 15px;">
            Enterprise Resource Management & Multi-Dimensional Data Warehouse Platform.
        </p>

        <div>
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-primary font-weight-bold px-4 py-2 shadow" style="background: #0284c7; border: none; border-radius: 30px;">
                    <i class="fas fa-desktop mr-2"></i> Masuk ke Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary font-weight-bold px-5 py-2.5 shadow-lg" style="background: #0284c7; border: none; border-radius: 30px; font-size: 16px; transition: 0.3s;">
                    Masuk ke Portal <i class="fas fa-arrow-right ml-2"></i>
                </a>
            @endauth
        </div>
        
        <div class="mt-5 pt-4" style="border-top: 1px solid #1c2541;">
            <small class="text-muted tracking-widest text-uppercase" style="font-size: 10px;">V2.0 Modular Alpha • Secure Environment</small>
        </div>
    </div>

</body>
</html>