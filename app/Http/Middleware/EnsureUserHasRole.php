<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (empty($roles) || in_array($user->tipo_usuario, $roles, true)) {
            return $next($request);
        }

        abort(Response::HTTP_FORBIDDEN, 'Você não tem permissão para acessar esta página.');
    }
}
