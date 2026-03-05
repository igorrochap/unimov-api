<?php

use App\Actions\Usuario\ListaUsuarios;
use App\DTO\Response\Usuario\DadosUsuario;
use App\Models\Municipio\Municipio;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->action = new ListaUsuarios;
});

test('retorna paginador', function () {
    $resultado = $this->action->executa();

    expect($resultado)->toBeInstanceOf(LengthAwarePaginator::class);
});

test('retorna usuarios listados como dto', function () {
    Usuario::factory()->create();

    $resultado = $this->action->executa();

    expect($resultado->first())->toBeInstanceOf(DadosUsuario::class);
});

test('retorna campos corretos no dto', function () {
    $municipio = Municipio::factory()->create();
    $usuario = Usuario::factory()->create(['municipio_id' => $municipio->id]);

    $resultado = $this->action->executa();
    $dto = $resultado->first();

    expect($dto->uuid)->toBe($usuario->uuid)
        ->and($dto->nome)->toBe($usuario->nome)
        ->and($dto->email)->toBe($usuario->email)
        ->and($dto->municipio)->toBe($municipio->nome)
        ->and($dto->perfil)->toBe($usuario->perfil);
});

test('retorna lista vazia quando nao ha usuarios', function () {
    $resultado = $this->action->executa();

    expect($resultado->total())->toBe(0);
});
