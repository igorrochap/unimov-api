<?php

namespace App\Actions\Periodo;

use App\DTO\Response\Periodo\DadosPeriodo;
use App\Models\Periodo;
use Illuminate\Pagination\LengthAwarePaginator;

class ListaPeriodo
{
    public function executa(): LengthAwarePaginator
    {
        return Periodo::query()
            ->with('municipio')
            ->orderBy('inicio', 'desc')
            ->paginate()
            ->through(fn (Periodo $periodo) => DadosPeriodo::porPeriodo($periodo));
    }
}
