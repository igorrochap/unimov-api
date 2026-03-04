<?php

namespace App\Http\Controllers\Usuario;

use App\Actions\Usuario\AtualizaUsuario;
use App\Actions\Usuario\CriaUsuario;
use App\Actions\Usuario\ListaUsuarios;
use App\DTO\Request\Usuario\AtualizaUsuarioDTO;
use App\DTO\Request\Usuario\NovoUsuarioDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Usuario\AtualizaUsuarioRequest;
use App\Http\Requests\Usuario\NovoUsuarioRequest;
use Illuminate\Http\JsonResponse;

final class UsuarioController extends Controller
{
    public function index(ListaUsuarios $action): JsonResponse
    {
        return $this->sucesso($action->executa());
    }

    public function store(NovoUsuarioRequest $request, CriaUsuario $action): JsonResponse
    {
        $usuario = $action->executa(NovoUsuarioDTO::porRequest($request));

        return $this->criado($usuario);
    }

    public function update(AtualizaUsuarioRequest $request, AtualizaUsuario $action): JsonResponse
    {
        $usuario = $action->executa(AtualizaUsuarioDTO::porRequest($request));

        return $this->sucesso($usuario);
    }
}
