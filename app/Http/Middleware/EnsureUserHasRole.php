<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  array<int, string>  $roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        // Si no hay usuario autenticado, redirigir al login (el middleware 'auth' debería manejar esto,
        // pero por si acaso, aquí también lo manejamos)
        if (!$user) {
            return redirect()->route('login');
        }

        // Si no se especificaron roles, denegar acceso
        if (empty($roles)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        if (!$user->hasRole(...$roles)) {
            // Si el usuario es un paciente, redirigirlo a su vista en lugar de mostrar 403
            if ($user->hasRole('Paciente')) {
                return redirect()->route('mis-turnos');
            }

            // Para otros usuarios sin permisos, mostrar 403
            abort(Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}


