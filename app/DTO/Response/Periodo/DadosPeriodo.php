<?php

namespace App\DTO\Response\Periodo;

use App\Models\Periodo;

final readonly class DadosPeriodo
{
    public function __construct(
        public int $id,
        public int $municipio_id,
        public string $descricao,
        public string $inicio_inscricao,
        public string $fim_inscricao,
        public string $inicio,
        public string $fim,
    ){}

    public static function porPeriodo(Periodo $request): DadosPeriodo
    {
        return new self(
            $request->id,
            $request->municipio_id,
            $request->descricao,
            $request->inicio_inscricao,
            $request->fim_inscricao,
            $request->inicio,
            $request->fim,
        );
    }
}
