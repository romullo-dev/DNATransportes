<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAnalyticsAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $tipoUsuario = strtolower((string) $request->user()?->tipo_usuario);

        if (! in_array($tipoUsuario, ['admin', 'operador'], true)) {
            return response()->json([
                'message' => 'Acesso negado aos dados analíticos.',
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
