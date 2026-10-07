<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Cek apakah pengguna belum login ATAU role-nya tidak sesuai
        if (! $request->user() || $request->user()->role !== $role) {
            abort(403, 'Halaman ini hanya untuk peran admin.');
        }

        return $next($request);
    }
}