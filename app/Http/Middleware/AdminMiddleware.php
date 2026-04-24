<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Verifica que el usuario autenticado tenga rol de administrador.
     * Si no lo es, devuelve un 403.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->isAdmin()) {
            abort(403, 'Acceso restringido a administradores.');
        }

        return $next($request);
    }
}
