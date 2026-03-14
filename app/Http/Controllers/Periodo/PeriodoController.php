<?php

namespace App\Http\Controllers\Periodo;

use App\Actions\Periodo\AtualizaPeriodo;
use App\Actions\Periodo\CriaPeriodo;
use App\Actions\Periodo\DeletaPeriodo;
use App\Actions\Periodo\ListaPeriodo;
use App\DTO\Request\Periodo\AtualizaPeriodoDTO;
use App\DTO\Request\Periodo\NovoPeriodoDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Periodo\NovoPeriodoRequest;
use Illuminate\Http\JsonResponse;

class PeriodoController extends Controller
{
    public function index(ListaPeriodo $action): JsonResponse
    {
        return $this->sucesso($action->executa());
    }

    public function store(NovoPeriodoRequest $request, CriaPeriodo $action): JsonResponse
    {
        $periodo = $action->executa(NovoPeriodoDTO::porPeriodo($request));

        return $this->criado($periodo);
    }

    public function update(int $id, NovoPeriodoRequest $request, AtualizaPeriodo $action): JsonResponse
    {
        $periodo = $action->executa(AtualizaPeriodoDTO::porPeriodo($request));

        return $this->sucesso($periodo);
    }

    public function destroy(int $id, DeletaPeriodo $action): JsonResponse
    {
        $action->executa($id);

        return $this->semConteudo();
    }
}
