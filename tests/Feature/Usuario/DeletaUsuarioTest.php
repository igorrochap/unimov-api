<?php

use App\Enums\Perfil;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\assertDatabaseMissing;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(Usuario::factory()->create(['perfil' => Perfil::Admin]));
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

test('retorna 403 para usuario nao admin', function (Perfil $perfil) {
    $this->actingAs(Usuario::factory()->create(['perfil' => $perfil]));

    $this->deleteJson(route('usuarios.deleta', 'qualquer-uuid'))
        ->assertForbidden();
})->with([
    'secretaria' => [Perfil::Secretaria],
    'fiscal' => [Perfil::Fiscal],
    'motorista' => [Perfil::Motorista],
    'aluno' => [Perfil::Aluno],
]);
