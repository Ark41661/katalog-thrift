<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMemberAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect()->route('member.login')
                ->with('intended', $request->url());
        }

        if (! auth()->user()->isMember()) {
            abort(403, 'Akses khusus member.');
        }

        return $next($request);
    }
}
