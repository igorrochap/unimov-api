<?php

namespace App\DTO\Request\Periodo;

use App\Http\Requests\Periodo\NovoPeriodoLetivoRequest;

class PeriodoLetivoDTO
{
    public function __construct(
        public \DateTime $inicio,
        public \DateTime $fim,
    ){}

    public static function porRequest(NovoPeriodoLetivoRequest $request): PeriodoLetivoDTO
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
