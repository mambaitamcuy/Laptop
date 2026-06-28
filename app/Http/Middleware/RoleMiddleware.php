<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Menangani request yang masuk.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Pastikan user sudah login
        if (!auth()->check()) {
            return redirect('login');
        }

        // 2. Ambil role user saat ini
        $userRole = auth()->user()->role;

        // 3. Jika role user ada di dalam daftar role yang diizinkan, loloskan request
        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        // 4. Jika tidak memiliki akses, lempar ke halaman 403 (Unauthorized) atau kembalikan dengan error
        abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
    }
}