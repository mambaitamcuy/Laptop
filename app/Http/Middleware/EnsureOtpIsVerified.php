<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureOtpIsVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && !session('otp_verified')) {
            if (!$request->is('login/verify*') && !$request->is('logout')) {
                return redirect()->route('otp.verify');
            }
        }

        return $next($request);
    }
}