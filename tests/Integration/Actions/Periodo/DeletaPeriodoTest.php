<?php

use App\Actions\Periodo\DeletaPeriodo;
use App\Models\Periodo;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\assertDatabaseMissing;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->action = new DeletaPeriodo;
});

test('deleta periodo', function () {
    $periodo = Periodo::factory()->create();

    $id = $periodo->id;
    $this->action->executa($periodo->id);

    assertDatabaseMissing('periodos', ['id' => $id]);
});

test('lanca excecao para id inexistente', function () {
    $this->action->executa(-9);
})->throws(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
