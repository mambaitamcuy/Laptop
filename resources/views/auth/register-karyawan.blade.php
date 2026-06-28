<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ArkadiaLP - Registrasi Staf Multi-Cabang</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        body { background-color: #0f172a; color: #f1f5f9; }
        .card-bg { background-color: #1e293b; border: 1px solid #334155; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">

    <div class="card-bg w-full max-w-md p-6 rounded-xl shadow-lg">
        <h2 class="text-lg font-bold text-sky-400 mb-1">Registrasi Akun Karyawan</h2>
        <p class="text-xs text-slate-400 mb-6">Penambahan otoritas hak akses regional Palu, Donggala, dan Parigi.</p>

        <form method="POST" action="{{ route('karyawan.register') }}" class="space-y-4 text-xs">
            @csrf

            <div>
                <label class="block text-slate-300 font-medium mb-1">Nama Lengkap</label>
                <input type="text" name="name" required class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2.5 text-white focus:outline-none focus:border-sky-500">
            </div>

            <div>
                <label class="block text-slate-300 font-medium mb-1">Alamat Email</label>
                <input type="email" name="email" required class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2.5 text-white focus:outline-none focus:border-sky-500">
            </div>

            <div>
                <label class="block text-slate-300 font-medium mb-1">Otoritas Tingkat (Role)</label>
                <select name="role" id="roleSelect" required class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2.5 text-white focus:outline-none focus:border-sky-500">
                    <option value="karyawan">Karyawan Biasa / Kasir</option>
                    <option value="cabang">Admin Cabang</option>
                    <option value="pusat">Manajemen Pusat (Palu Global)</option>
                </select>
            </div>

            <div id="cabangWrapper">
                <label class="block text-slate-300 font-medium mb-1">Lokasi Penempatan Cabang</label>
                <select name="id_cabang" class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2.5 text-white focus:outline-none focus:border-sky-500">
                    @foreach($daftarCabang as $cabang)
                        <option value="{{ $cabang->id_cabang }}">{{ $cabang->nama_cabang }} ({{ $cabang->kota }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-slate-300 font-medium mb-1">Password</label>
                <input type="password" name="password" required class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2.5 text-white focus:outline-none focus:border-sky-500">
            </div>

            <div>
                <label class="block text-slate-300 font-medium mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2.5 text-white focus:outline-none focus:border-sky-500">
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full bg-sky-600 hover:bg-sky-500 text-white font-semibold p-2.5 rounded-lg transition cursor-pointer text-center">
                    Simpan dan Daftarkan Karyawan
                </button>
            </div>
        </form>
    </div>

    <script>
        const roleSelect = document.getElementById('roleSelect');
        const cabangWrapper = document.getElementById('cabangWrapper');

        roleSelect.addEventListener('change', function() {
            if (this.value === 'pusat') {
                cabangWrapper.style.display = 'none';
            } else {
                cabangWrapper.style.display = 'block';
            }
        });
    </script>
</body>
</html>