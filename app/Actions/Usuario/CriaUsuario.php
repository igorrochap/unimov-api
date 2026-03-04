<?php

namespace App\Actions\Usuario;

use App\DTO\Request\Usuario\NovoUsuarioDTO;
use App\DTO\Response\Usuario\NovoUsuario;
use App\Models\Usuario;

final readonly class CriaUsuario
{
    public function executa(NovoUsuarioDTO $dto): NovoUsuario
    {
        $usuario = Usuario::query()->create($dto->toArray());

        return NovoUsuario::porUsuario($usuario);
    }
}
