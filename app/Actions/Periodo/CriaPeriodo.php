<?php

namespace App\Actions\Periodo;

use App\DTO\Request\Periodo\PeriodoDTO;
use App\DTO\Response\Periodo\DadosPeriodo;
use App\Models\Periodo;

class CriaPeriodo
{
    public function executa(PeriodoDTO $dto): DadosPeriodo
    {
        $periodo = Periodo::query()->create($dto->toArray());

        return DadosPeriodo::porPeriodo($periodo);
    }
}
