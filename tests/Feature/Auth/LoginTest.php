<?php

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('autentica usuario com email', function () {
    $usuario = Usuario::factory()->create();

    $response = $this->withHeader('Origin', config('app.url'))
        ->postJson(route('auth.login'), [
            'credencial' => $usuario->email,
            'senha' => 'password',
        ]);

    $response->assertOk();
    $response->assertJsonStructure([
        'uuid', 'nome', 'email', 'perfil',
    ]);
});

test('autentica usuario com cpf', function () {
    $usuario = Usuario::factory()->create();

    $response = $this->withHeader('Origin', config('app.url'))
        ->postJson(route('auth.login'), [
            'credencial' => $usuario->cpf,
            'senha' => 'password',
        ]);

    $response->assertOk();
    $response->assertJsonStructure([
        'uuid', 'nome', 'email', 'perfil',
    ]);
});

test('rejeita credenciais invalidas', function () {
    $usuario = Usuario::factory()->create();

    $this->withHeader('Origin', config('app.url'))
        ->postJson(route('auth.login'), [
            'credencial' => $usuario->email,
            'senha' => 'senha-errada',
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['credencial']);
});

test('rejeita login com usuario inexistente', function () {
    $this->withHeader('Origin', config('app.url'))
        ->postJson(route('auth.login'), [
            'credencial' => 'naoexiste@test.com',
            'senha' => 'password',
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['credencial']);
});

test('valida campos obrigatorios', function (string $campo) {
    $payload = [
        'credencial' => 'mail@test.com',
        'senha' => 'password',
    ];

    unset($payload[$campo]);

    $this->postJson(route('auth.login'), $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors([$campo]);
})->with([
    'credencial' => 'credencial',
    'senha' => 'senha',
]);
