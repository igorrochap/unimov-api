<?php

use App\Models\Municipio\Municipio;
use App\Models\Periodo;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('lista periodos paginados', function () {
    // Cria 3 períodos usando a factory
    Periodo::factory()->count(3)->create();

    $this->getJson(route('periodos.lista'))
        ->assertSuccessful()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'municipio_nome',
                    'descricao',
                    'inicio_inscricao',
                    'fim_inscricao',
                    'inicio',
                    'fim'
                ],
            ],
            'current_page',
            'per_page',
            'total',
        ]);
});

test('retorna dados corretos do periodo', function () {
    $municipio = Municipio::factory()->create();

    $periodo = Periodo::factory()->create([
        'municipio_id' => $municipio->id,
        'descricao' => 'Semestre Letivo 2026.1'
    ]);

    $this->getJson(route('periodos.lista'))
        ->assertSuccessful()
        ->assertJsonFragment([
            'id' => $periodo->id,
            'municipio_nome' => $municipio->nome,
            'descricao' => 'Semestre Letivo 2026.1',
            'inicio_inscricao' => $periodo->inicio_inscricao->format('Y-m-d H:i:s'),
        ]);
});

test('retorna lista vazia quando nao ha periodos', function () {
    $this->getJson(route('periodos.lista'))
        ->assertSuccessful()
        ->assertJson(['data' => [], 'total' => 0]);
});
