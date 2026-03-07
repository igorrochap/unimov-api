<?php

namespace App\DTO\Request\Periodo;

use App\Http\Requests\Periodo\NovoPeriodoInscricaoRequest;

final readonly class PeriodoInscricaoDTO
{
    public function __construct(
        public int $municipio_id,
        public string $descricao,
        public \DateTime $inicio_inscricao,
        public \DateTime $fim_inscricao,
    ){}

    public static function porRequest(NovoPeriodoInscricaoRequest $request): PeriodoInscricaoDTO
    {
        return new self(
            $request->integer('municipio_id'),
            $request->string('descricao'),
            $request->date('inicio_inscricao'),
            $request->date('fim_inscricao'),
        );
    }

    public function toArray(): array
    {
        return [
            'municipio_id' => $this->municipio_id,
            'descricao' => $this->descricao,
            'inicio_inscricao' => $this->inicio_inscricao,
            'fim_inscricao' => $this->fim_inscricao,
        ];
    }
}
