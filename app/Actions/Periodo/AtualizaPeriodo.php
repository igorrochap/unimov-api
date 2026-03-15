<?php

namespace App\Actions\Periodo;

use App\DTO\Request\Periodo\AtualizaPeriodoDTO;
use App\DTO\Response\Periodo\DadosPeriodo;
use App\Models\Periodo;

class AtualizaPeriodo
{
    public function executa(AtualizaPeriodoDTO $dto): DadosPeriodo
    {
        $periodo = Periodo::query()
            ->with('municipio')
            ->where('id', $dto->id)
            ->firstOrFail();

        $periodo->update($dto->toArray());

        return DadosPeriodo::porPeriodo($periodo->fresh('municipio'));
    }
}
