<?php

use App\Models\Municipio\Municipio;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('cria usuario', function () {
    $municipio = Municipio::factory()->create();

    $payload = [
        'nome' => 'John Doe',
        'cpf' => '000.000.000-00',
        'email' => 'mail@test.com',
        'senha' => 'secret',
        'municipio' => $municipio->id,
        'perfil' => 'motorista',
    ];

    $response = $this->postJson(route('usuarios.cria'), $payload);

    $response->assertCreated();
    $response->assertJsonStructure([
        'uuid', 'nome', 'email',
    ]);
});

test('valida campos obrigatorios', function (string $campo) {
    $municipio = Municipio::factory()->create();

    $payload = [
        'nome' => 'John Doe',
        'cpf' => '000.000.000-00',
        'email' => 'mail@test.com',
        'senha' => 'secret',
        'municipio' => $municipio->id,
        'perfil' => 'motorista',
    ];

    unset($payload[$campo]);

    $this->postJson(route('usuarios.cria'), $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors([$campo]);
})->with([
    'nome' => 'nome',
    'email' => 'email',
    'cpf' => 'cpf',
    'senha' => 'senha',
    'municipio' => 'municipio',
    'perfil' => 'perfil',
]);

test('valida formato de email invalido', function () {
    $municipio = Municipio::factory()->create();

    $this->postJson(route('usuarios.cria'), [
        'nome' => 'John Doe',
        'cpf' => '000.000.000-00',
        'email' => 'email-invalido',
        'senha' => 'secret',
        'municipio' => $municipio->id,
        'perfil' => 'motorista',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});

test('valida email duplicado', function () {
    $municipio = Municipio::factory()->create();

    Usuario::factory()->create([
        'cpf' => '111.111.111-11',
        'email' => 'existente@test.com',
        'municipio_id' => $municipio->id,
    ]);

    $this->postJson(route('usuarios.cria'), [
        'nome' => 'John Doe',
        'cpf' => '111.111.111-11',
        'email' => 'existente@test.com',
        'senha' => 'secret',
        'municipio' => $municipio->id,
        'perfil' => 'motorista',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});

test('valida cpf duplicado', function () {
    $municipio = Municipio::factory()->create();

    Usuario::factory()->create([
        'cpf' => '000.000.000-00',
        'municipio_id' => $municipio->id,
    ]);

    $this->postJson(route('usuarios.cria'), [
        'nome' => 'John Doe',
        'cpf' => '000.000.000-00',
        'email' => 'outro@test.com',
        'senha' => 'secret',
        'municipio' => $municipio->id,
        'perfil' => 'motorista',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['cpf']);
});
