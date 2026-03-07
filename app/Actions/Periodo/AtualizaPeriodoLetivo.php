<?php

namespace App\Actions\Periodo;

use App\DTO\Request\Periodo\PeriodoLetivoDTO;
use App\DTO\Response\Periodo\DadosPeriodo;
use App\Models\Periodo;

class AtualizaPeriodoLetivo
{
    public function executa(int $id, PeriodoLetivoDTO $dto): DadosPeriodo
    {
        $periodo = Periodo::query()
            ->with('municipio')
            ->where('id', $id)
            ->firstOrFail();

        $periodo->update($dto->toArray());

        return DadosPeriodo::porPeriodo($periodo->fresh('municipio'));
    }
}
