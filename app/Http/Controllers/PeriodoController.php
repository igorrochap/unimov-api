<?php

namespace App\Http\Controllers;

use App\Actions\Periodo\AtualizaPeriodo;
use App\Actions\Periodo\AtualizaPeriodoInscricao;
use App\Actions\Periodo\AtualizaPeriodoLetivo;
use App\Actions\Periodo\CriaPeriodo;
use App\Actions\Periodo\CriaPeriodoInscricao;
use App\Actions\Periodo\CriaPeriodoLetivo;
use App\Actions\Periodo\DeletaPeriodo;
use App\Actions\Periodo\ListaPeriodo;
use App\DTO\Request\Periodo\PeriodoDTO;
use App\DTO\Request\Periodo\PeriodoInscricaoDTO;
use App\DTO\Request\Periodo\PeriodoLetivoDTO;
use App\Http\Requests\Periodo\NovoPeriodoInscricaoRequest;
use App\Http\Requests\Periodo\NovoPeriodoRequest;
use App\Http\Requests\Periodo\NovoPeriodoLetivoRequest;
use Illuminate\Http\JsonResponse;

class PeriodoController extends Controller
{
    public function index(ListaPeriodo $action): JsonResponse
    {
        return $this->sucesso($action->executa());
    }

    // Metodo para criar Período Geral
    public function store(NovoPeriodoRequest $request, CriaPeriodo $action): JsonResponse
    {
        $periodo = $action->executa(PeriodoDTO::porPeriodo($request));

        return $this->criado($periodo);
    }

    // Metodo para criar Período Inscrição
    public function storePeriodoInscricao(NovoPeriodoInscricaoRequest $request, CriaPeriodoInscricao $action): JsonResponse
    {
        $periodo = $action->executa(PeriodoInscricaoDTO::porRequest($request));

        return $this->criado($periodo);
    }

    // Metodo para criar Período Vigencia Letiva
    public function storePeriodoLetivo(NovoPeriodoLetivoRequest $request, CriaPeriodoLetivo $action): JsonResponse
    {
        $periodo = $action->executa(PeriodoLetivoDTO::porRequest($request));

        return $this->criado($periodo);
    }

    // Metodo para criar Atualizar Período Geral
    public function update(string $id, NovoPeriodoRequest $request, AtualizaPeriodo $action): JsonResponse
    {
        $periodo = $action->executa($id, PeriodoDTO::porPeriodo($request));

        return $this->sucesso($periodo);
    }

    // Metodo para criar Atualizar Período Inscrição
    public function updatePeriodoInscricao(string $id, NovoPeriodoInscricaoRequest $request, AtualizaPeriodoInscricao $action): JsonResponse
    {
        $periodo = $action->executa($id, PeriodoInscricaoDTO::porRequest($request));

        return $this->sucesso($periodo);
    }

    // Metodo para criar Atualizar Período Letivo
    public function updatePeriodoLetivo(string $id, NovoPeriodoLetivoRequest $request, AtualizaPeriodoLetivo $action): JsonResponse
    {
        $periodo = $action->executa($id, PeriodoLetivoDTO::porRequest($request));

        return $this->sucesso($periodo);
    }

    public function destroy(string $id, DeletaPeriodo $action): JsonResponse
    {
        $action->executa($id);

        return $this->semConteudo();
    }
}
