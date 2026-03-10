<?php


use App\Models\Periodo;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('define a vigência letiva de um período existente', function () {
    // Criamos um período que já passou pela fase de inscrição (inicio/fim nulos)
    $periodo = Periodo::factory()->create([
        'inicio' => null,
        'fim' => null,
    ]);

    $payload = [
        'inicio' => '2026-03-23 07:00:00',
        'fim' => '2026-07-15 22:30:00',
    ];

    $response = $this->putJson(route('periodos.letivo.atualiza', $periodo->id), $payload);

    $response->assertOk();

    $this->assertDatabaseHas('periodos', [
        'id' => $periodo->id,
        'inicio' => '2026-03-23 07:00:00',
        'fim' => '2026-07-15 22:30:00',
    ]);
});

test('valida campos obrigatórios da vigência letiva', function (string $campo) {
    $periodo = Periodo::factory()->create();

    $payload = [
        'inicio' => '2026-03-23 07:00:00',
        'fim' => '2026-07-15 22:30:00',
    ];

    unset($payload[$campo]);

    $this->putJson(route('periodos.letivo.atualiza', $periodo->id), $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors([$campo]);
})->with([
    'inicio',
    'fim',
]);

test('valida que o fim letivo deve ser após o início letivo', function () {
    $periodo = Periodo::factory()->create();

    $payload = [
        'inicio' => '2026-07-15 00:00:00',
        'fim' => '2026-03-23 00:00:00', // data anterior
    ];

    $this->putJson(route('periodos.letivo.atualiza', $periodo->id), $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['fim'])
        ->assertJsonPath('errors.fim.0', 'O fim do período deve ser posterior ao início.');
});

test('retorna 404 ao tentar definir vigência de período inexistente', function () {
    $this->putJson(route('periodos.letivo.atualiza', 9999), [
        'inicio' => '2026-03-23 07:00:00',
        'fim' => '2026-07-15 22:30:00',
    ])
        ->assertNotFound();
});
