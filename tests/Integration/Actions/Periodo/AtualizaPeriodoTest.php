<?php

use App\Actions\Periodo\AtualizaPeriodo;
use App\DTO\Request\Periodo\PeriodoDTO;
use App\DTO\Response\Periodo\DadosPeriodo;
use App\Models\Municipio\Municipio;
use App\Models\Periodo;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->action = new AtualizaPeriodo;
});

test('atualiza periodo', function () {
    $periodo   = Periodo::factory()->create();
    $municipio = Municipio::factory()->create();
    $dto = new PeriodoDTO(
        $municipio->id,
        'Período Atualizado',
        new \DateTime('2025-01-01 00:00:00'),
        new \DateTime('2025-01-10 00:00:00'),
        new \DateTime('2025-02-01 00:00:00'),
        new \DateTime('2025-02-10 00:00:00'));

    $resultado = $this->action->executa($periodo->id, $dto);

    expect($resultado)->toBeInstanceOf(DadosPeriodo::class);
});

test('retorna dados atualizados', function () {
    $periodo   = Periodo::factory()->create();
    $municipio = Municipio::factory()->create();
    $dto = new PeriodoDTO($municipio->id, 'Período Atualizado', new \DateTime('2025-01-01 00:00:00'), new \DateTime('2025-01-10 00:00:00'), new \DateTime('2025-02-01 00:00:00'), new \DateTime('2025-02-10 00:00:00'));

    $resultado = $this->action->executa($periodo->id, $dto);

    expect($resultado->id)->toBe($periodo->id)
        ->and($resultado->descricao)->toBe('Período Atualizado')
        ->and($resultado->municipio_id)->toBe($municipio->id);
});

test('persiste alteracoes no banco de dados', function () {
    $periodo   = Periodo::factory()->create();
    $municipio = Municipio::factory()->create();
    $dto = new PeriodoDTO($municipio->id, 'Período Atualizado', new \DateTime('2025-01-01 00:00:00'), new \DateTime('2025-01-10 00:00:00'), new \DateTime('2025-02-01 00:00:00'), new \DateTime('2025-02-10 00:00:00'));

    $this->action->executa($periodo->id, $dto);

    $this->assertDatabaseHas('periodos', [
        'id'               => $periodo->id,
        'descricao'        => 'Período Atualizado',
        'municipio_id'     => $municipio->id,
        'inicio_inscricao' => '2025-01-01 00:00:00',
        'fim_inscricao'    => '2025-01-10 00:00:00',
        'inicio'           => '2025-02-01 00:00:00',
        'fim'              => '2025-02-10 00:00:00',
    ]);
});

test('lanca excecao para id inexistente', function () {
    $municipio = Municipio::factory()->create();
    $dto = new PeriodoDTO($municipio->id, 'Período Atualizado', new \DateTime('2025-01-01 00:00:00'), new \DateTime('2025-01-10 00:00:00'), new \DateTime('2025-02-01 00:00:00'), new \DateTime('2025-02-10 00:00:00'));

    $this->action->executa(999, $dto);
})->throws(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
