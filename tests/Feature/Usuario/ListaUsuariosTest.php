<?php

use App\Models\Municipio\Municipio;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('lista usuarios paginados', function () {
    Usuario::factory()->count(3)->create();

    $this->getJson(route('usuarios.lista'))
        ->assertSuccessful()
        ->assertJsonStructure([
            'data' => [
                '*' => ['uuid', 'nome', 'email', 'municipio', 'perfil'],
            ],
            'current_page',
            'per_page',
            'total',
        ]);
});

test('retorna dados corretos do usuario', function () {
    $municipio = Municipio::factory()->create();
    $usuario = Usuario::factory()->create(['municipio_id' => $municipio->id]);

    $this->getJson(route('usuarios.lista'))
        ->assertSuccessful()
        ->assertJsonFragment([
            'uuid' => $usuario->uuid,
            'nome' => $usuario->nome,
            'email' => $usuario->email,
            'municipio' => $municipio->nome,
            'perfil' => $usuario->perfil,
        ]);
});

test('retorna lista vazia quando nao ha usuarios', function () {
    $this->getJson(route('usuarios.lista'))
        ->assertSuccessful()
        ->assertJson(['data' => [], 'total' => 0]);
});
