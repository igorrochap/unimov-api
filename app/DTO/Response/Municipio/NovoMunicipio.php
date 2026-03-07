<?php

namespace App\DTO\Response\Municipio;

use App\Models\Municipio\Municipio;

final readonly class NovoMunicipio
{
    public function __construct(
        public string $uuid,
        public string $nome,
        public string $email
    ){}

    public static function porMunicipio(Municipio $municipio): NovoMunicipio
    {
        return new self(
            $municipio->uuid,
            $municipio->nome,
            $municipio->email
        );
    }
}
