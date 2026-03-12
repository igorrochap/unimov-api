<?php

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('retorna dados do usuario autenticado', function () {
    $usuario = Usuario::factory()->create();

    $response = $this->actingAs($usuario)
        ->getJson(route('auth.me'));

    $response->assertOk();
    $response->assertJsonStructure([
        'uuid', 'nome', 'email', 'perfil',
    ]);
    $response->assertJsonFragment([
        'uuid' => $usuario->uuid,
        'email' => $usuario->email,
    ]);
});

test('rejeita acesso sem autenticacao', function () {
    $this->getJson(route('auth.me'))
        ->assertUnauthorized();
});
