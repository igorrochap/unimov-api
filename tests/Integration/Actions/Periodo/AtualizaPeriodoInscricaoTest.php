<?php

use App\Actions\Periodo\AtualizaPeriodoInscricao;
use App\DTO\Request\Periodo\PeriodoInscricaoDTO;
use App\DTO\Response\Periodo\DadosPeriodo;
use App\Models\Municipio\Municipio;
use App\Models\Periodo;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->action = new AtualizaPeriodoInscricao;
});

test('atualiza periodo de inscricao', function () {
    $periodo   = Periodo::factory()->create();
    $municipio = Municipio::factory()->create();
    $dto = new PeriodoInscricaoDTO($municipio->id, 'Período Inscrição Atualizado', new \DateTime('2025-01-01 00:00:00'), new \DateTime('2025-01-10 00:00:00'));

    $resultado = $this->action->executa($periodo->id, $dto);

    expect($resultado)->toBeInstanceOf(DadosPeriodo::class);
});

test('retorna dados atualizados do periodo de inscricao', function () {
    $periodo   = Periodo::factory()->create();
    $municipio = Municipio::factory()->create();
    $dto = new PeriodoInscricaoDTO($municipio->id, 'Período Inscrição Atualizado', new \DateTime('2025-01-01 00:00:00'), new \DateTime('2025-01-10 00:00:00'));

    $resultado = $this->action->executa($periodo->id, $dto);

    expect($resultado->id)->toBe($periodo->id)
        ->and($resultado->descricao)->toBe('Período Inscrição Atualizado')
        ->and($resultado->municipio_id)->toBe($municipio->id);
});

test('persiste alteracoes de inscricao no banco', function () {
    $periodo   = Periodo::factory()->create();
    $municipio = Municipio::factory()->create();
    $dto = new PeriodoInscricaoDTO($municipio->id, 'Período Inscrição Atualizado', new \DateTime('2025-01-01 00:00:00'), new \DateTime('2025-01-10 00:00:00'));

    $this->action->executa($periodo->id, $dto);

    $this->assertDatabaseHas('periodos', [
        'id'               => $periodo->id,
        'descricao'        => 'Período Inscrição Atualizado',
        'municipio_id'     => $municipio->id,
        'inicio_inscricao' => '2025-01-01 00:00:00',
        'fim_inscricao'    => '2025-01-10 00:00:00',
    ]);
});

test('nao altera campos letivos ao atualizar inscricao', function () {
    $periodo   = Periodo::factory()->create();
    $municipio = Municipio::factory()->create();
    $dto = new PeriodoInscricaoDTO($municipio->id, 'Período Inscrição Atualizado', new \DateTime('2025-01-01 00:00:00'), new \DateTime('2025-01-10 00:00:00'));

    $this->action->executa($periodo->id, $dto);

    $this->assertDatabaseHas('periodos', [
        'id'     => $periodo->id,
        'inicio' => $periodo->inicio?->format('Y-m-d H:i:s'),
        'fim'    => $periodo->fim?->format('Y-m-d H:i:s'),
    ]);
});

test('lanca excecao para id inexistente', function () {
    $municipio = Municipio::factory()->create();
    $dto = new PeriodoInscricaoDTO($municipio->id, 'Período Inscrição', new \DateTime('2025-01-01 00:00:00'), new \DateTime('2025-01-10 00:00:00'));

    $this->action->executa(999, $dto);
})->throws(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
