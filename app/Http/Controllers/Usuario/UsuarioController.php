<?php

namespace App\Http\Controllers\Usuario;

use App\Actions\Usuario\CriaUsuario;
use App\DTO\Request\Usuario\NovoUsuarioDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Usuario\NovoUsuarioRequest;
use Illuminate\Http\JsonResponse;

final class UsuarioController extends Controller
{
    public function store(NovoUsuarioRequest $request, CriaUsuario $action): JsonResponse
    {
        $usuario = $action->executa(NovoUsuarioDTO::porRequest($request));

        return $this->criado($usuario);
    }
}
