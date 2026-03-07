<?php

namespace App\Actions\Periodo;

use App\DTO\Request\Periodo\PeriodoInscricaoDTO;
use App\DTO\Response\Periodo\DadosPeriodo;
use App\Models\Periodo;

class CriaPeriodoInscricao
{
    public function executa(PeriodoInscricaoDTO $dto): DadosPeriodo
    {
        $periodo = Periodo::query()->create($dto->toArray());

        return DadosPeriodo::porPeriodo($periodo);
    }
}
