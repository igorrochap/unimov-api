<?php

namespace App\Actions\Periodo;

use App\DTO\Request\Periodo\NovoPeriodoDTO;
use App\DTO\Response\Periodo\DadosPeriodo;
use App\Models\Periodo;

class CriaPeriodo
{
    public function executa(NovoPeriodoDTO $dto): DadosPeriodo
    {
        $periodo = Periodo::query()->create($dto->toArray());

        return DadosPeriodo::porPeriodo($periodo);
    }
}
