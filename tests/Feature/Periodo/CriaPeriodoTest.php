<?php

use App\Models\Municipio\Municipio;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('cria um período', function () {
    $municipio = Municipio::factory()->create();

    $payload = [
        'municipio_id' => $municipio->id,
        'descricao' => 'Semestre Letivo 2026.1',
        'inicio_inscricao' => '2026-03-10 08:00:00',
        'fim_inscricao' => '2026-03-20 23:59:59',
        'inicio' => '2026-03-25 07:00:00',
        'fim' => '2026-07-15 22:00:00',
    ];

    $response = $this->postJson(route('periodos.cria'), $payload);

    $response->assertCreated();
    $response->assertJsonStructure([
        'id', 'descricao', 'municipio_nome'
    ]);

    $this->assertDatabaseHas('periodos', [
        'descricao' => 'Semestre Letivo 2026.1',
        'municipio_id' => $municipio->id
    ]);
});

test('valida campos obrigatórios no período', function (string $campo) {
    $municipio = Municipio::factory()->create();

    $payload = [
        'municipio_id' => $municipio->id,
        'descricao' => 'Teste Validação',
        'inicio_inscricao' => '2026-03-10 08:00:00',
        'fim_inscricao' => '2026-03-20 23:59:59',
        'inicio' => '2026-03-25 07:00:00',
        'fim' => '2026-07-15 22:00:00',
    ];

    unset($payload[$campo]);

    $this->postJson(route('periodos.cria'), $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors([$campo]);
})->with([
    'municipio_id',
    'descricao',
    'inicio_inscricao',
    'fim_inscricao',
    'inicio',
    'fim',
]);

test('valida que o fim da inscrição deve ser após o início', function () {
    $municipio = Municipio::factory()->create();

    $this->postJson(route('periodos.cria'), [
        'municipio_id' => $municipio->id,
        'descricao' => 'Datas Inválidas',
        'inicio_inscricao' => '2026-03-20 08:00:00',
        'fim_inscricao' => '2026-03-10 08:00:00', // Data anterior ao início
        'inicio' => '2026-03-25 00:00:00',
        'fim' => '2026-07-15 00:00:00',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['fim_inscricao']);
});
