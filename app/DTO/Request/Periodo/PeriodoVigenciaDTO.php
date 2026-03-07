<?php

namespace App\DTO\Request\Periodo;

use App\Http\Requests\Periodo\NovoPeriodoVigenciaRequest;

class PeriodoVigenciaDTO
{
    public function __construct(
        public \DateTime $inicio,
        public \DateTime $fim,
    ){}

    public static function porRequest(NovoPeriodoVigenciaRequest $request): PeriodoVigenciaDTO
    {
        return new self(
            $request->date('inicio'),
            $request->date('fim'),
        );
    }

    public function toArray(): array
    {
        return [
            'inicio' => $this->inicio,
            'fim' => $this->fim,
        ];
    }
}
