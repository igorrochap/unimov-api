<?php

use App\Actions\Periodo\AtualizaPeriodoLetivo;
use App\DTO\Request\Periodo\PeriodoLetivoDTO;
use App\DTO\Response\Periodo\DadosPeriodo;
use App\Models\Municipio\Municipio;
use App\Models\Periodo;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->action = new AtualizaPeriodoLetivo;
});

test('atualiza periodo letivo', function () {
    $periodo = Periodo::factory()->create();
    $dto = new PeriodoLetivoDTO(
        new \DateTime('2025-02-01 17:52:00'),
        new \DateTime('2025-12-20 08:15:10'),
    );

    $resultado = $this->action->executa($periodo->id, $dto);

    expect($resultado)->toBeInstanceOf(DadosPeriodo::class);
});

test('retorna dados letivos atualizados', function () {
    $periodo = Periodo::factory()->create();
    $dto = new PeriodoLetivoDTO(new \DateTime('2025-02-01 17:52:00'), new \DateTime('2025-12-20 08:15:10'));

    $resultado = $this->action->executa($periodo->id, $dto);

    expect($resultado->id)->toBe($periodo->id)
        ->and($resultado->municipio_id)->toBe($periodo->municipio_id)
        ->and($resultado->descricao)->toBe($periodo->descricao)
        ->and($resultado->inicio)->toBe('2025-02-01 17:52:00')
        ->and($resultado->fim)->toBe('2025-12-20 08:15:10');
});

test('persiste alteracoes letivas no banco', function () {
    $periodo = Periodo::factory()->create();
    $dto = new PeriodoLetivoDTO(new \DateTime('2025-02-01 17:52:00'), new \DateTime('2025-12-20 08:15:10'));

    $this->action->executa($periodo->id, $dto);

    $this->assertDatabaseHas('periodos', [
        'id'     => $periodo->id,
        'inicio' => '2025-02-01 17:52:00',
        'fim'    => '2025-12-20 08:15:10',
    ]);
});

test('nao altera campos de inscricao ao atualizar periodo letivo', function () {
    $periodo = Periodo::factory()->create();
    $dto = new PeriodoLetivoDTO(new \DateTime('2025-02-01 17:52:00'), new \DateTime('2025-12-20 08:15:10'));

    $this->action->executa($periodo->id, $dto);

    $this->assertDatabaseHas('periodos', [
        'id'               => $periodo->id,
        'descricao'        => $periodo->descricao,
        'municipio_id'     => $periodo->municipio_id,
        'inicio_inscricao' => $periodo->inicio_inscricao->format('Y-m-d H:i:s'),
        'fim_inscricao'    => $periodo->fim_inscricao->format('Y-m-d H:i:s'),
    ]);
});

test('lanca excecao para id inexistente', function () {
    $dto = new PeriodoLetivoDTO(new \DateTime('2025-02-01'), new \DateTime('2025-12-20'));

    $this->action->executa(999, $dto);
})->throws(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
