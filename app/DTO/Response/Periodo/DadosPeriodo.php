<?php

namespace App\DTO\Response\Periodo;

use App\Models\Periodo;

final readonly class DadosPeriodo
{
    public function __construct(
        public int $municipio,
        public string $descricao,
        public \DateTime $inicio_inscricao,
        public \DateTime $fim_inscricao,
        public \DateTime $inicio,
        public \DateTime $fim,
    ){}

    public static function porPeriodo(Periodo $request): DadosPeriodo
    {
        return new self(
            $request->integer('municipio'),
            $request->string('descricao'),
            $request->date('inicio_inscricao'),
            $request->date('fim_inscricao'),
            $request->date('inicio'),
            $request->date('fim'),
        );
    }
}
