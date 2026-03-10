<?php

use App\Actions\Periodo\ListaPeriodo;
use App\DTO\Response\Periodo\DadosPeriodo;
use App\Models\Municipio\Municipio;
use App\Models\Periodo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->action = new ListaPeriodo;
});

test('retorna paginador', function () {
    $resultado = $this->action->executa();

    expect($resultado)->toBeInstanceOf(LengthAwarePaginator::class);
});

test('retorna periodos listados como dto', function () {
    Periodo::factory()->create();

    $resultado = $this->action->executa();

    expect($resultado->first())->toBeInstanceOf(DadosPeriodo::class);
});

test('retorna campos corretos no dto', function () {
    $municipio = Municipio::factory()->create();
    $periodo   = Periodo::factory()->create(['municipio_id' => $municipio->id]);

    $resultado = $this->action->executa();
    $dto       = $resultado->first();

    expect($dto->id)->toBe($periodo->id)
        ->and($dto->municipio_id)->toBe($municipio->id)
        ->and($dto->descricao)->toBe($periodo->descricao)
        ->and($dto->inicio_inscricao)->toBe($periodo->inicio_inscricao->format('Y-m-d H:i:s'))
        ->and($dto->fim_inscricao)->toBe($periodo->fim_inscricao->format('Y-m-d H:i:s'))
        ->and($dto->inicio)->toBe($periodo->inicio?->format('Y-m-d H:i:s'))
        ->and($dto->fim)->toBe($periodo->fim?->format('Y-m-d H:i:s'));
});

test('retorna lista vazia quando nao ha periodos', function () {
    $resultado = $this->action->executa();

    expect($resultado->total())->toBe(0);
});
