<?php


use App\Models\Periodo;
use App\Models\Municipio\Municipio;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('atualiza periodo de inscricao', function () {
    $municipio = Municipio::factory()->create();

    $periodo = Periodo::factory()->create([
        'municipio_id' => $municipio->id,
    ]);

    $this->putJson(route('periodos.inscricao.atualiza', $periodo->id), [
        'municipio_id' => $municipio->id,
        'descricao' => 'Periodo Inscrição Atualizado',
        'inicio_inscricao' => '2025-01-01',
        'fim_inscricao' => '2025-01-10',
    ])
        ->assertSuccessful()
        ->assertJsonStructure([
            'id',
            'municipio_id',
            'descricao',
            'inicio_inscricao',
            'fim_inscricao',
        ]);
});

test('retorna dados atualizados do periodo de inscricao', function () {
    $municipio = Municipio::factory()->create();

    $periodo = Periodo::factory()->create([
        'municipio_id' => $municipio->id,
    ]);

    $this->putJson(route('periodos.inscricao.atualiza', $periodo->id), [
        'municipio_id' => $municipio->id,
        'descricao' => 'Periodo Inscrição Atualizado',
        'inicio_inscricao' => '2025-01-01',
        'fim_inscricao' => '2025-01-10',
    ])
        ->assertSuccessful()
        ->assertJsonFragment([
            'id' => $periodo->id,
            'descricao' => 'Periodo Inscrição Atualizado',
        ]);
});

test('retorna 404 para periodo de inscricao inexistente', function () {
    $municipio = Municipio::factory()->create();

    $this->putJson(route('periodos.inscricao.atualiza', 999), [
        'municipio_id' => $municipio->id,
        'descricao' => 'Periodo Inscrição',
        'inicio_inscricao' => '2025-01-01',
        'fim_inscricao' => '2025-01-10',
    ])->assertNotFound();
});

test('valida campos obrigatorios do periodo de inscricao', function (string $campo) {
    $municipio = Municipio::factory()->create();

    $periodo = Periodo::factory()->create([
        'municipio_id' => $municipio->id,
    ]);

    $payload = [
        'municipio_id' => $municipio->id,
        'descricao' => 'Periodo Inscrição',
        'inicio_inscricao' => '2025-01-01',
        'fim_inscricao' => '2025-01-10',
    ];

    unset($payload[$campo]);

    $this->putJson(route('periodos.inscricao.atualiza', $periodo->id), $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors([$campo]);
})->with([
    'municipio_id' => 'municipio_id',
    'descricao' => 'descricao',
    'inicio_inscricao' => 'inicio_inscricao',
    'fim_inscricao' => 'fim_inscricao',
]);

test('valida fim_inscricao apos inicio_inscricao', function () {
    $municipio = Municipio::factory()->create();

    $periodo = Periodo::factory()->create([
        'municipio_id' => $municipio->id,
    ]);

    $this->putJson(route('periodos.inscricao.atualiza', $periodo->id), [
        'municipio_id' => $municipio->id,
        'descricao' => 'Periodo Inscrição',
        'inicio_inscricao' => '2025-01-10',
        'fim_inscricao' => '2025-01-01',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['fim_inscricao']);
});
