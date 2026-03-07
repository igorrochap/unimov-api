<?php

namespace App\DTO\Request\Periodo;

use App\Http\Requests\Periodo\NovoPeriodoRequest;

final readonly class PeriodoDTO
{
    public function __construct(
        public int $municipio_id,
        public string $descricao,
        public \DateTime $inicio_inscricao,
        public \DateTime $fim_inscricao,
        public \DateTime $inicio,
        public \DateTime $fim,
    ){}

    public static function porPeriodo(NovoPeriodoRequest $request): PeriodoDTO
    {
        return new self(
            $request->integer('municipio_id'),
            $request->string('descricao'),
            $request->date('inicio_inscricao'),
            $request->date('fim_inscricao'),
            $request->date('inicio'),
            $request->date('fim'),
        );
    }

    public function toArray(): array
    {
        return [
            'municipio_id' => $this->municipio_id,
            'descricao' => $this->descricao,
            'inicio_inscricao' => $this->inicio_inscricao,
            'fim_inscricao' => $this->fim_inscricao,
            'inicio' => $this->inicio,
            'fim' => $this->fim,
        ];
    }
}
