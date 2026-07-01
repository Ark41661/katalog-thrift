<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePartnerAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth('partner')->check()) {
            return redirect()->route('partner.login');
        }

        $user = auth('partner')->user();

        if (! $user->partner || ! $user->partner->isApproved()) {
            auth('partner')->logout();
            return redirect()->route('partner.login')
                ->withErrors(['email' => 'Akun mitra Anda belum disetujui atau telah ditangguhkan.']);
        }

        return $next($request);
    }
}
