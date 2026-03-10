<?php

use App\Actions\Periodo\AtualizaPeriodoLetivo;
use App\DTO\Request\Periodo\PeriodoLetivoDTO;
use App\DTO\Response\Periodo\DadosPeriodo;
use App\Models\Periodo;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->action = new AtualizaPeriodoLetivo;
});

test('define vigencia letiva de um periodo existente', function () {
    $periodo = Periodo::factory()->create(['inicio' => null, 'fim' => null]);
    $dto = new PeriodoLetivoDTO(new \DateTime('2026-03-23 07:00:00'), new \DateTime('2026-07-15 22:30:00'));

    $resultado = $this->action->executa($periodo->id, $dto);

    expect($resultado)->toBeInstanceOf(DadosPeriodo::class);
});

test('retorna dados letivos atualizados', function () {
    $periodo = Periodo::factory()->create(['inicio' => null, 'fim' => null]);
    $dto = new PeriodoLetivoDTO(new \DateTime('2026-03-23 07:00:00'), new \DateTime('2026-07-15 22:30:00'));

    $resultado = $this->action->executa($periodo->id, $dto);

    expect($resultado->id)->toBe($periodo->id)
        ->and($resultado->inicio)->toBe('2026-03-23 07:00:00')
        ->and($resultado->fim)->toBe('2026-07-15 22:30:00');
});

test('persiste vigencia letiva no banco de dados', function () {
    $periodo = Periodo::factory()->create(['inicio' => null, 'fim' => null]);
    $dto = new PeriodoLetivoDTO(new \DateTime('2026-03-23 07:00:00'), new \DateTime('2026-07-15 22:30:00'));

    $this->action->executa($periodo->id, $dto);

    $this->assertDatabaseHas('periodos', [
        'id'     => $periodo->id,
        'inicio' => '2026-03-23 07:00:00',
        'fim'    => '2026-07-15 22:30:00',
    ]);
});

test('nao altera campos de inscricao ao definir vigencia letiva', function () {
    $periodo = Periodo::factory()->create(['inicio' => null, 'fim' => null]);
    $dto = new PeriodoLetivoDTO(new \DateTime('2026-03-23 07:00:00'), new \DateTime('2026-07-15 22:30:00'));

    $this->action->executa($periodo->id, $dto);

    $this->assertDatabaseHas('periodos', [
        'id'               => $periodo->id,
        'descricao'        => $periodo->descricao,
        'municipio_id'     => $periodo->municipio_id,
        'inicio_inscricao' => $periodo->inicio_inscricao->format('Y-m-d H:i:s'),
        'fim_inscricao'    => $periodo->fim_inscricao->format('Y-m-d H:i:s'),
    ]);
});

test('lanca excecao para periodo inexistente', function () {
    $dto = new PeriodoLetivoDTO(new \DateTime('2026-03-23 07:00:00'), new \DateTime('2026-07-15 22:30:00'));

    $this->action->executa(9999, $dto);
})->throws(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
