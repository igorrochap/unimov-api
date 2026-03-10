<?php


use App\Models\Municipio\Municipio;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('cria apenas o período de inscrição com sucesso', function () {
    $municipio = Municipio::factory()->create();

    $payload = [
        'municipio_id' => $municipio->id,
        'descricao' => 'Inscrições Transporte 2026.1',
        'inicio_inscricao' => '2026-03-10 08:00:00',
        'fim_inscricao' => '2026-03-25 18:00:00',
    ];

    $response = $this->postJson(route('periodos.inscricao.cria'), $payload);

    $response->assertCreated();

    $this->assertDatabaseHas('periodos', [
        'descricao' => 'Inscrições Transporte 2026.1',
        'municipio_id' => $municipio->id,
        'inicio_inscricao' => '2026-03-10 08:00:00',
        'fim_inscricao' => '2026-03-25 18:00:00',
    ]);
});

test('valida obrigatoriedade dos campos de inscrição', function (string $campo) {
    $municipio = Municipio::factory()->create();

    $payload = [
        'municipio_id' => $municipio->id,
        'descricao' => 'Cadastro de Teste',
        'inicio_inscricao' => '2026-03-10 08:00:00',
        'fim_inscricao' => '2026-03-25 18:00:00',
    ];

    unset($payload[$campo]);

    $this->postJson(route('periodos.inscricao.cria'), $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors([$campo]);
})->with([
    'municipio_id',
    'descricao',
    'inicio_inscricao',
    'fim_inscricao',
]);

test('impede que o término da inscrição seja igual ou anterior ao início', function () {
    $municipio = Municipio::factory()->create();

    $payload = [
        'municipio_id' => $municipio->id,
        'descricao' => 'Erro de Cronograma',
        'inicio_inscricao' => '2026-03-10 08:00:00',
        'fim_inscricao' => '2026-03-10 07:59:59', // Um segundo antes
    ];

    $this->postJson(route('periodos.inscricao.cria'), $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['fim_inscricao'])
        ->assertJsonPath('errors.fim_inscricao.0', 'O término da inscrição deve ser após o início.');
});

test('valida se o município informado existe no banco', function () {
    $this->postJson(route('periodos.inscricao.cria'), [
        'municipio_id' => 9999, // ID inexistente
        'descricao' => 'Período Fantasma',
        'inicio_inscricao' => '2026-03-10 08:00:00',
        'fim_inscricao' => '2026-03-25 18:00:00',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['municipio_id']);
});
