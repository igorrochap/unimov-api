<?php

use App\Models\Periodo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Usuario;
use App\Enums\Perfil;

use function Pest\Laravel\assertDatabaseMissing;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(Usuario::factory()->create(['perfil' => Perfil::Secretaria]));
});

test('deleta Período', function () {
    $periodo = Periodo::factory()->create();

    $id = $periodo->id;
    $this->deleteJson(route('periodos.deleta', $periodo->id))
        ->assertNoContent();

    assertDatabaseMissing('periodos', ['id' => $id]);
});

test('retorna 404 para usuario inexistente', function () {
    $this->deleteJson(route('periodos.deleta', -9)) // -9 para ser parâmetro inexistente
        ->assertNotFound();
});
