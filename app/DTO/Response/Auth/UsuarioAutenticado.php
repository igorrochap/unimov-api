<?php

namespace App\DTO\Response\Auth;

use App\Models\Usuario;

final readonly class UsuarioAutenticado
{
    public function __construct(
        public string $uuid,
        public string $nome,
        public string $email,
        public string $perfil,
    ) {}

    public static function porUsuario(Usuario $usuario): UsuarioAutenticado
    {
        return new self(
            $usuario->uuid,
            $usuario->nome,
            $usuario->email,
            $usuario->perfil,
        );
    }
}
