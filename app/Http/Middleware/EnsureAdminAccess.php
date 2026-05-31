<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if (strtolower((string) $request->user()?->tipo_usuario) !== 'admin') {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Acesso negado. Apenas administradores podem editar rotas.',
                ], Response::HTTP_FORBIDDEN);
            }

            return redirect()
                ->route('rotas.index')
                ->with('error', 'Acesso negado. Apenas administradores podem editar rotas.');
        }

        return $next($request);
    }
}
