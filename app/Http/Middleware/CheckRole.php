<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle($request, Closure $next, string $rol)
{
    // Si el usuario no está logueado o su rol no coincide con el requerido, se bloquea el acceso
    if (!auth()->check() || auth()->user()->rol->nombre !== $rol) {
        abort(403, 'Acceso denegado: No tienes el perfil necesario para esta sección.');
    }

    return $next($request);
}
}
