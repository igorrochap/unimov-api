<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\AutenticaUsuario;
use App\Actions\Auth\DeslogaUsuario;
use App\DTO\Request\Auth\LoginDTO;
use App\DTO\Response\Auth\UsuarioAutenticado;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

final class AuthController extends Controller
{
    public function login(LoginRequest $request, AutenticaUsuario $action): JsonResponse
    {
        $usuario = $action->executa(LoginDTO::porRequest($request));

        return $this->sucesso($usuario);
    }

    public function logout(DeslogaUsuario $action): JsonResponse
    {
        $action->executa();

        return $this->semConteudo();
    }

    public function me(): JsonResponse
    {
        /** @var Usuario $usuario */
        $usuario = Auth::user();

        return $this->sucesso(UsuarioAutenticado::porUsuario($usuario));
    }
}
