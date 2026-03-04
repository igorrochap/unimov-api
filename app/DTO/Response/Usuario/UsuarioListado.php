<?php

namespace App\DTO\Response\Usuario;

use App\Models\Usuario;

final readonly class UsuarioListado
{
    public function __construct(
        public string $uuid,
        public string $nome,
        public string $email,
        public string $municipio,
        public string $perfil,
    ) {}

    public static function porUsuario(Usuario $usuario): UsuarioListado
    {
        return new self(
            $usuario->uuid,
            $usuario->nome,
            $usuario->email,
            $usuario->municipio->nome,
            $usuario->perfil,
        );
    }
}
