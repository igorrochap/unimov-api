<?php

use App\Models\Municipio\Municipio;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('atualiza usuario', function () {
    $usuario = Usuario::factory()->create();
    $municipio = Municipio::factory()->create();

    $this->putJson(route('usuarios.atualiza', $usuario->uuid), [
        'nome' => 'Novo Nome',
        'cpf' => '999.999.999-99',
        'email' => 'novo@email.com',
        'municipio' => $municipio->id,
        'perfil' => 'admin',
    ])
        ->assertSuccessful()
        ->assertJsonStructure(['uuid', 'nome', 'email', 'municipio', 'perfil']);
});

test('retorna dados atualizados na resposta', function () {
    $usuario = Usuario::factory()->create();
    $municipio = Municipio::factory()->create();

    $this->putJson(route('usuarios.atualiza', $usuario->uuid), [
        'nome' => 'Novo Nome',
        'cpf' => '999.999.999-99',
        'email' => 'novo@email.com',
        'municipio' => $municipio->id,
        'perfil' => 'admin',
    ])
        ->assertSuccessful()
        ->assertJsonFragment([
            'uuid' => $usuario->uuid,
            'nome' => 'Novo Nome',
            'email' => 'novo@email.com',
            'municipio' => $municipio->nome,
            'perfil' => 'admin',
        ]);
});

test('retorna 404 para usuario inexistente', function () {
    $municipio = Municipio::factory()->create();

    $this->putJson(route('usuarios.atualiza', 'uuid-inexistente'), [
        'nome' => 'Novo Nome',
        'cpf' => '999.999.999-99',
        'email' => 'novo@email.com',
        'municipio' => $municipio->id,
        'perfil' => 'admin',
    ])->assertNotFound();
});

test('valida campos obrigatorios na atualizacao', function (string $campo) {
    $usuario = Usuario::factory()->create();

    $payload = [
        'nome' => 'Novo Nome',
        'cpf' => '999.999.999-99',
        'email' => 'novo@email.com',
        'municipio' => $usuario->municipio_id,
        'perfil' => 'admin',
    ];

    unset($payload[$campo]);

    $this->putJson(route('usuarios.atualiza', $usuario->uuid), $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors([$campo]);
})->with([
    'nome' => 'nome',
    'email' => 'email',
    'cpf' => 'cpf',
    'municipio' => 'municipio',
    'perfil' => 'perfil',
]);

test('permite manter o proprio email na atualizacao', function () {
    $usuario = Usuario::factory()->create();

    $this->putJson(route('usuarios.atualiza', $usuario->uuid), [
        'nome' => $usuario->nome,
        'cpf' => $usuario->cpf,
        'email' => $usuario->email,
        'municipio' => $usuario->municipio_id,
        'perfil' => $usuario->perfil,
    ])->assertSuccessful();
});

test('valida email duplicado de outro usuario na atualizacao', function () {
    $outro = Usuario::factory()->create(['email' => 'existente@test.com']);
    $usuario = Usuario::factory()->create();

    $this->putJson(route('usuarios.atualiza', $usuario->uuid), [
        'nome' => $usuario->nome,
        'cpf' => $usuario->cpf,
        'email' => 'existente@test.com',
        'municipio' => $usuario->municipio_id,
        'perfil' => $usuario->perfil,
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});

test('valida cpf duplicado de outro usuario na atualizacao', function () {
    $outro = Usuario::factory()->create(['cpf' => '111.111.111-11']);
    $usuario = Usuario::factory()->create();

    $this->putJson(route('usuarios.atualiza', $usuario->uuid), [
        'nome' => $usuario->nome,
        'cpf' => '111.111.111-11',
        'email' => $usuario->email,
        'municipio' => $usuario->municipio_id,
        'perfil' => $usuario->perfil,
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['cpf']);
});
