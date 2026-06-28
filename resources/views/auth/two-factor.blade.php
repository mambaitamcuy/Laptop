<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ArkadiaLP - Verifikasi Dua Faktor</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-[#0f172a] text-[#f1f5f9] flex items-center justify-center min-h-screen p-4">

    <div class="bg-[#1e293b] border border-[#334155] w-full max-w-md p-6 rounded-xl shadow-xl">
        <div class="text-center mb-6">
            <h2 class="text-xl font-bold tracking-wider text-amber-400">OTENTIKASI DUA FAKTOR</h2>
            <p class="text-xs text-slate-400 mt-1">Sistem mendeteksi upaya login. Masukkan 6 digit kode keamanan yang dikirimkan ke email Anda untuk melanjutkan.</p>
        </div>

        @if ($errors->any())
            <div class="bg-rose-500/10 border border-rose-500/20 text-rose-400 p-3 rounded-lg text-xs mb-4">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('verify.store') }}" class="space-y-4 text-xs">
            @csrf

            <div>
                <label class="block text-slate-300 font-medium mb-2 text-center">Kode Verifikasi Keamanan (6 Digit)</label>
                <input type="text" name="two_factor_code" maxlength="6" required autofocus placeholder="000000"
                    class="w-full bg-[#0f172a] border border-slate-700 rounded-lg p-3 text-center text-lg tracking-widest text-white font-bold focus:outline-none focus:border-amber-500 transition">
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full bg-amber-600 hover:bg-amber-500 text-white font-semibold p-2.5 rounded-lg transition shadow-md cursor-pointer text-center tracking-wide">
                    VERIFIKASI DAN MASUK
                </button>
            </div>
        </form>
    </div>

</body>
</html>