<?php


use App\Models\Periodo;
use App\Models\Municipio\Municipio;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('atualiza periodo letivo', function () {
    $municipio = Municipio::factory()->create();

    $periodo = Periodo::factory()->create([
        'municipio_id' => $municipio->id,
    ]);

    $this->putJson(route('periodos.letivo.atualiza', $periodo->id), [
        'inicio' => '2025-02-01',
        'fim' => '2025-12-20',
    ])
        ->assertSuccessful()
        ->assertJsonStructure([
            'inicio',
            'fim',
        ]);
});

test('retorna dados atualizados do periodo letivo', function () {
    $municipio = Municipio::factory()->create();

    $periodo = Periodo::factory()->create([
        'municipio_id' => $municipio->id,
    ]);

    $this->putJson(route('periodos.letivo.atualiza', $periodo->id), [
        'inicio' => '2025-02-01 17:52:00',
        'fim' => '2025-12-20 08:15:10',
    ])
        ->assertSuccessful()
        ->assertJsonFragment([
            'municipio_id' => $periodo->municipio_id,
            'descricao' => $periodo->descricao,
            'inicio' => '2025-02-01 17:52:00',
            'fim' => '2025-12-20 08:15:10',
        ]);
});

test('retorna 404 para periodo letivo inexistente', function () {
    $municipio = Municipio::factory()->create();

    $this->putJson(route('periodos.letivo.atualiza', 999), [
        'inicio' => '2025-02-01',
        'fim' => '2025-12-20',
    ])->assertNotFound();
});

test('valida campos obrigatorios do periodo letivo', function (string $campo) {
    $municipio = Municipio::factory()->create();

    $periodo = Periodo::factory()->create([
        'municipio_id' => $municipio->id,
    ]);

    $payload = [
        'inicio' => '2025-02-01',
        'fim' => '2025-12-20',
    ];

    unset($payload[$campo]);

    $this->putJson(route('periodos.letivo.atualiza', $periodo->id), $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors([$campo]);
})->with([
    'inicio' => 'inicio',
    'fim' => 'fim',
]);

test('valida fim apos inicio no periodo letivo', function () {
    $municipio = Municipio::factory()->create();

    $periodo = Periodo::factory()->create([
        'municipio_id' => $municipio->id,
    ]);

    $this->putJson(route('periodos.letivo.atualiza', $periodo->id), [
        'inicio' => '2025-12-20',
        'fim' => '2025-02-01',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['fim']);
});
