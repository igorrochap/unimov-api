<?php

namespace App\Actions\Usuario;

use App\DTO\Request\Usuario\AtualizaUsuarioDTO;
use App\DTO\Response\Usuario\DadosUsuario;
use App\Models\Usuario;

final readonly class AtualizaUsuario
{
    public function executa(AtualizaUsuarioDTO $dto): DadosUsuario
    {
        $usuario = Usuario::query()
            ->with('municipio')
            ->where('uuid', $dto->uuid)
            ->firstOrFail();

        $usuario->update($dto->toArray());

        return DadosUsuario::porUsuario($usuario->fresh('municipio'));
    }
}
