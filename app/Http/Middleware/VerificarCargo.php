<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificarCargo
{
    public function handle(
        Request $request,
        Closure $next,
        string ...$cargos
    ): Response {

        $usuario = $request->user();

        if (!$usuario || !$usuario->cargo) {
            abort(403, 'Acesso não autorizado.');
        }

        if (!in_array($usuario->cargo->nome, $cargos)) {
            abort(403, 'Você não possui permissão para realizar esta ação.');
        }

        return $next($request);
    }
}