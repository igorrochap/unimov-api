<?php

use App\Actions\Usuario\DeletaUsuario;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\assertDatabaseMissing;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->action = new DeletaUsuario;
});

test('deleta usuario', function () {
    $usuario = Usuario::factory()->create();
    
    $uuid = $usuario->uuid;
    $this->action->executa($usuario->uuid);

    assertDatabaseMissing('usuarios', ['uuid' => $uuid]);
});

test('lanca excecao para uuid inexistente', function () {
    $this->action->executa('uuid-inexistente');
})->throws(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
