<?php

use App\Actions\Periodo\CriaPeriodoInscricao;
use App\DTO\Request\Periodo\PeriodoInscricaoDTO;
use App\DTO\Response\Periodo\DadosPeriodo;
use App\Models\Municipio\Municipio;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->action = new CriaPeriodoInscricao;
});

test('cria periodo de inscricao', function () {
    $municipio = Municipio::factory()->create();
    $dto = new PeriodoInscricaoDTO($municipio->id, 'Inscrições Transporte 2026.1', new \DateTime('2026-03-10 08:00:00'), new \DateTime('2026-03-25 18:00:00'));

    $resultado = $this->action->executa($dto);

    expect($resultado)->toBeInstanceOf(DadosPeriodo::class);
});

test('retorna periodo de inscricao com campos validos', function () {
    $municipio = Municipio::factory()->create();
    $dto = new PeriodoInscricaoDTO($municipio->id, 'Inscrições Transporte 2026.1', new \DateTime('2026-03-10 08:00:00'), new \DateTime('2026-03-25 18:00:00'));

    $resultado = $this->action->executa($dto);

    expect($resultado->id)->not->toBeNull()
        ->and($resultado->descricao)->toBe('Inscrições Transporte 2026.1')
        ->and($resultado->municipio_id)->toBe($municipio->id)
        ->and($resultado->inicio)->toBeNull()
        ->and($resultado->fim)->toBeNull();
});

test('persiste periodo de inscricao no banco sem inicio e fim letivos', function () {
    $municipio = Municipio::factory()->create();
    $dto = new PeriodoInscricaoDTO($municipio->id, 'Inscrições Transporte 2026.1', new \DateTime('2026-03-10 08:00:00'), new \DateTime('2026-03-25 18:00:00'));

    $this->action->executa($dto);

    $this->assertDatabaseHas('periodos', [
        'descricao'        => 'Inscrições Transporte 2026.1',
        'municipio_id'     => $municipio->id,
        'inicio_inscricao' => '2026-03-10 08:00:00',
        'fim_inscricao'    => '2026-03-25 18:00:00',
        'inicio'           => null,
        'fim'              => null,
    ]);
});
