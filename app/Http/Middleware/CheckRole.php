<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    // verificar si el usuario tiene el rol necesario
    public function handle(Request $request, Closure $next, string $rol)
    {
        $usuario = $request->user();

        if (!$usuario) {
            return response()->json([
                'mensaje' => 'No autenticado'
            ], 401);
        }

        // si no tiene el rol requerido, se le niega el acceso
        if (!$usuario->hasRole($rol)) {
            return response()->json([
                'mensaje' => 'No tienes permiso para hacer esto'
            ], 403);
        }

        return $next($request);
    }
}
