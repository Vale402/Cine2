<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     * Only users with rol = 'administrador' can access admin routes.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || Auth::user()->rol !== 'administrador') {
            return redirect()->route('cartelera')->with('error', 'No tienes permisos para acceder al panel de administración.');
        }

        return $next($request);
    }
}
