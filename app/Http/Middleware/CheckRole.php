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
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // 1. Verificar si el usuario está autenticado
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // 2. Obtener el nombre del rol del usuario autenticado
        $nombreRolUsuario = $user->rol?->nombre;

        // 3. Comprobar si el rol del usuario está dentro de la lista de roles permitidos en la ruta
        if (!$nombreRolUsuario || !in_array($nombreRolUsuario, $roles)) {
            abort(403, 'Acceso denegado: No tienes el perfil necesario para esta sección.');
        }

        return $next($request);
    }
}