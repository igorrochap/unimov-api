<?php

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('desloga usuario autenticado', function () {
    $usuario = Usuario::factory()->create();

    $this->actingAs($usuario)
        ->withHeader('Origin', config('app.url'))
        ->postJson(route('auth.logout'))
        ->assertNoContent();
});

test('rejeita logout sem autenticacao', function () {
    $this->postJson(route('auth.logout'))
        ->assertUnauthorized();
});
