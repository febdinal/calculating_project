<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Restrict access to admin-only routes.
 * Unauthenticated users are redirected to login.
 * Authenticated non-admin users receive a 403.
 */
class AdminOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        if (! $request->user()->isAdmin()) {
            abort(403, 'Akses admin diperlukan.');
        }

        return $next($request);
    }
}
