<?php

use App\Actions\Usuario\CriaUsuario;
use App\DTO\Request\Usuario\NovoUsuarioDTO;
use App\DTO\Response\Usuario\NovoUsuario;
use App\Models\Municipio\Municipio;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->action = new CriaUsuario;
});

test('cria usuario', function () {
    $municipio = Municipio::factory()->create();
    $dto = new NovoUsuarioDTO('John Doe', '000.000.000-00', 'johndoe@test.com', 'secret', $municipio->id, 'secretaria');

    $usuario = $this->action->executa($dto);

    expect($usuario)->toBeInstanceOf(NovoUsuario::class);
});

test('retorna usuario com campos validos', function () {
    $municipio = Municipio::factory()->create();
    $dto = new NovoUsuarioDTO('John Doe', '000.000.000-00', 'johndoe@test.com', 'secret', $municipio->id, 'secretaria');

    $usuario = $this->action->executa($dto);

    expect(Str::isUuid($usuario->uuid))
        ->and($usuario->nome)->toBe($dto->nome)
        ->and($usuario->email)->toBe($dto->email);
});
