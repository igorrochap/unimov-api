<?php

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\assertDatabaseMissing;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(Usuario::factory()->create());
});

test('deleta usuario', function () {
    $usuario = Usuario::factory()->create();

    $uuid = $usuario->uuid;
    $this->deleteJson(route('usuarios.deleta', $usuario->uuid))
        ->assertNoContent();

    assertDatabaseMissing('usuarios', ['uuid' => $uuid]);
});

test('retorna 404 para usuario inexistente', function () {
    $this->deleteJson(route('usuarios.deleta', 'uuid-inexistente'))
        ->assertNotFound();
});
