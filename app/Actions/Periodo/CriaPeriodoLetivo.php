<?php

namespace App\Actions\Periodo;

use App\DTO\Request\Periodo\PeriodoLetivoDTO;
use App\DTO\Response\Periodo\DadosPeriodo;
use App\Models\Periodo;

class CriaPeriodoLetivo
{
    public function executa(PeriodoLetivoDTO $dto): DadosPeriodo
    {
        $periodo = Periodo::query()->create($dto->toArray());

        return DadosPeriodo::porPeriodo($periodo);
    }
}
