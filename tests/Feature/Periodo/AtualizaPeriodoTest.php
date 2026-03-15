<?php


use App\Models\Periodo;
use App\Models\Municipio\Municipio;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Usuario;
use App\Enums\Perfil;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(Usuario::factory()->create(['perfil' => Perfil::Secretaria]));
});

test('atualiza periodo', function () {
    $municipio = Municipio::factory()->create();

    $periodo = Periodo::factory()->create([
        'municipio_id' => $municipio->id,
    ]);

    $this->putJson(route('periodos.atualiza', $periodo->id), [
        'municipio_id' => $municipio->id,
        'descricao' => 'Periodo Atualizado',
        'inicio_inscricao' => '2025-01-01',
        'fim_inscricao' => '2025-01-10',
        'inicio' => '2025-02-01',
        'fim' => '2025-02-10',
    ])
        ->assertSuccessful()
        ->assertJsonStructure([
            'id',
            'municipio_nome',
            'descricao',
            'inicio_inscricao',
            'fim_inscricao',
            'inicio',
            'fim',
        ]);
});

test('retorna dados atualizados na resposta', function () {
    $municipio = Municipio::factory()->create();

    $periodo = Periodo::factory()->create([
        'municipio_id' => $municipio->id,
    ]);

    $this->putJson(route('periodos.atualiza', $periodo->id), [
        'municipio_id' => $municipio->id,
        'descricao' => 'Periodo Atualizado',
        'inicio_inscricao' => '2025-01-01',
        'fim_inscricao' => '2025-01-10',
        'inicio' => '2025-02-01',
        'fim' => '2025-02-10',
    ])
        ->assertSuccessful()
        ->assertJsonFragment([
            'id' => $periodo->id,
            'municipio_nome' => $municipio->nome,
            'descricao' => 'Periodo Atualizado',
        ]);
});

test('retorna 404 para periodo inexistente', function () {
    $municipio = Municipio::factory()->create();

    $this->putJson(route('periodos.atualiza', 999), [
        'municipio_id' => $municipio->id,
        'descricao' => 'Periodo Atualizado',
        'inicio_inscricao' => '2025-01-01',
        'fim_inscricao' => '2025-01-10',
        'inicio' => '2025-02-01',
        'fim' => '2025-02-10',
    ])->assertNotFound();
});

test('valida campos obrigatorios na atualizacao', function (string $campo) {
    $municipio = Municipio::factory()->create();

    $periodo = Periodo::factory()->create([
        'municipio_id' => $municipio->id,
    ]);

    $payload = [
        'municipio_id' => $municipio->id,
        'descricao' => 'Periodo Atualizado',
        'inicio_inscricao' => '2025-01-01',
        'fim_inscricao' => '2025-01-10',
        'inicio' => '2025-02-01',
        'fim' => '2025-02-10',
    ];

    unset($payload[$campo]);

    $this->putJson(route('periodos.atualiza', $periodo->id), $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors([$campo]);
})->with([
    'municipio_id' => 'municipio_id',
    'descricao' => 'descricao',
    'inicio_inscricao' => 'inicio_inscricao',
    'fim_inscricao' => 'fim_inscricao',
    'inicio' => 'inicio',
    'fim' => 'fim',
]);

test('valida fim_inscricao apos inicio_inscricao', function () {
    $municipio = Municipio::factory()->create();

    $periodo = Periodo::factory()->create([
        'municipio_id' => $municipio->id,
    ]);

    $this->putJson(route('periodos.atualiza', $periodo->id), [
        'municipio_id' => $municipio->id,
        'descricao' => 'Periodo Atualizado',
        'inicio_inscricao' => '2025-01-10',
        'fim_inscricao' => '2025-01-01',
        'inicio' => '2025-02-01',
        'fim' => '2025-02-10',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['fim_inscricao']);
});

test('valida fim apos inicio', function () {
    $municipio = Municipio::factory()->create();

    $periodo = Periodo::factory()->create([
        'municipio_id' => $municipio->id,
    ]);

    $this->putJson(route('periodos.atualiza', $periodo->id), [
        'municipio_id' => $municipio->id,
        'descricao' => 'Periodo Atualizado',
        'inicio_inscricao' => '2025-01-01',
        'fim_inscricao' => '2025-01-10',
        'inicio' => '2025-02-10',
        'fim' => '2025-02-01',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['fim']);
});
