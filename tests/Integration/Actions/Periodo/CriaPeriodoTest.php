<?php

use App\Actions\Periodo\CriaPeriodo;
use App\DTO\Request\Periodo\NovoPeriodoDTO;
use App\DTO\Response\Periodo\DadosPeriodo;
use App\Models\Municipio\Municipio;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->action = new CriaPeriodo;
});

test('cria periodo', function () {
    $municipio = Municipio::factory()->create();
    $dto = new NovoPeriodoDTO($municipio->id, 'Semestre Letivo 2026.1', new \DateTime('2026-03-10 08:00:00'), new \DateTime('2026-03-20 23:59:59'), new \DateTime('2026-03-25 07:00:00'), new \DateTime('2026-07-15 22:00:00'));

    $resultado = $this->action->executa($dto);

    expect($resultado)->toBeInstanceOf(DadosPeriodo::class);
});

test('retorna periodo com campos validos', function () {
    $municipio = Municipio::factory()->create();
    $dto = new NovoPeriodoDTO($municipio->id, 'Semestre Letivo 2026.1', new \DateTime('2026-03-10 08:00:00'), new \DateTime('2026-03-20 23:59:59'), new \DateTime('2026-03-25 07:00:00'), new \DateTime('2026-07-15 22:00:00'));

    $resultado = $this->action->executa($dto);

    expect($resultado->id)->not->toBeNull()
        ->and($resultado->descricao)->toBe('Semestre Letivo 2026.1')
        ->and($resultado->municipio_nome)->toBe($municipio->nome);
});

test('persiste periodo no banco de dados', function () {
    $municipio = Municipio::factory()->create();
    $dto = new NovoPeriodoDTO($municipio->id, 'Semestre Letivo 2026.1', new \DateTime('2026-03-10 08:00:00'), new \DateTime('2026-03-20 23:59:59'), new \DateTime('2026-03-25 07:00:00'), new \DateTime('2026-07-15 22:00:00'));

    $this->action->executa($dto);

    $this->assertDatabaseHas('periodos', [
        'descricao'    => 'Semestre Letivo 2026.1',
        'municipio_id' => $municipio->id,
    ]);
});
