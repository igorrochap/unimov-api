<?php

namespace App\Actions\Periodo;

use App\DTO\Request\Periodo\PeriodoDTO;
use App\DTO\Response\Periodo\DadosPeriodo;
use App\Models\Periodo;

class AtualizaPeriodo
{
    public function executa(int $id, PeriodoDTO $dto): DadosPeriodo
    {
        $periodo = Periodo::query()
            ->with('municipio')
            ->where('id', $id)
            ->firstOrFail();

        $periodo->update($dto->toArray());

        return DadosPeriodo::porPeriodo($periodo->fresh('municipio'));
    }
}
