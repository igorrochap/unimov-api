<?php

namespace App\Actions\Periodo;

use App\DTO\Request\Periodo\PeriodoVigenciaDTO;
use App\DTO\Response\Periodo\DadosPeriodo;
use App\Models\Periodo;

class CriaPeriodoVigencia
{
    public function executa(PeriodoVigenciaDTO $dto): DadosPeriodo
    {
        $periodo = Periodo::query()->create($dto->toArray());

        return DadosPeriodo::porPeriodo($periodo);
    }
}
