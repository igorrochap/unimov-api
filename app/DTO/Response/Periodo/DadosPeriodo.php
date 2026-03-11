<?php

namespace App\DTO\Response\Periodo;

use App\Models\Periodo;

final readonly class DadosPeriodo
{
    public function __construct(
        public int $id,
        public string $municipio_nome,
        public string $descricao,
        public string $inicio_inscricao,
        public string $fim_inscricao,
        public string $inicio,
        public string $fim,
    ){}

    public static function porPeriodo(Periodo $periodo): DadosPeriodo
    {
        return new self(
            $periodo->id,
            $periodo->municipio->nome,
            $periodo->descricao,
            $periodo->inicio_inscricao->format('Y-m-d H:i:s'),
            $periodo->fim_inscricao->format('Y-m-d H:i:s'),
            $periodo->inicio->format('Y-m-d H:i:s'),
            $periodo->fim->format('Y-m-d H:i:s'),
        );
    }
}
