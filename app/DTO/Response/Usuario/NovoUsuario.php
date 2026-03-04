<?php

namespace App\DTO\Response\Usuario;

use App\Models\Usuario;

final readonly class NovoUsuario
{
    public function __construct(
        public string $uuid,
        public string $nome,
        public string $email,
    ) {}

    public static function porUsuario(Usuario $usuario): NovoUsuario
    {
        return new self(
            $usuario->uuid,
            $usuario->nome,
            $usuario->email
        );
    }
}
