<?php

namespace App\Http\Middleware;

use App\Enums\Perfil;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificaPerfil
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$perfis): Response
    {
        $usuario = $request->user();

        if ($usuario->perfil === Perfil::Admin) {
            return $next($request);
        }

        $perfisPermitidos = array_map(
            fn (string $perfil): Perfil => Perfil::from($perfil),
            $perfis
        );

        if (! in_array($usuario->perfil, $perfisPermitidos, strict: true)) {
            return response()->json(
                ['message' => 'Acesso não autorizado.'],
                Response::HTTP_FORBIDDEN,
            );
        }

        return $next($request);
    }
}
