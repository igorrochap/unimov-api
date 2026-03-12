<?php

use App\Actions\Usuario\AtualizaUsuario;
use App\DTO\Request\Usuario\AtualizaUsuarioDTO;
use App\DTO\Response\Usuario\DadosUsuario;
use App\Models\Municipio\Municipio;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->action = new AtualizaUsuario;
});

test('atualiza usuario', function () {
    $usuario = Usuario::factory()->create();
    $municipio = Municipio::factory()->create();
    $dto = new AtualizaUsuarioDTO($usuario->uuid, 'Novo Nome', '999.999.999-99', 'novo@email.com', null, $municipio->id, 'admin');

    $resultado = $this->action->executa($dto);

    expect($resultado)->toBeInstanceOf(DadosUsuario::class);
});

test('retorna dados atualizados', function () {
    $usuario = Usuario::factory()->create();
    $municipio = Municipio::factory()->create();
    $dto = new AtualizaUsuarioDTO($usuario->uuid, 'Novo Nome', '999.999.999-99', 'novo@email.com', null, $municipio->id, 'admin');

    $resultado = $this->action->executa($dto);

    expect($resultado->uuid)->toBe($usuario->uuid)
        ->and($resultado->nome)->toBe('Novo Nome')
        ->and($resultado->email)->toBe('novo@email.com')
        ->and($resultado->municipio)->toBe($municipio->nome)
        ->and($resultado->perfil)->toBe('admin');
});

test('atualiza senha quando informada', function () {
    $usuario = Usuario::factory()->create();
    $dto = new AtualizaUsuarioDTO($usuario->uuid, $usuario->nome, $usuario->cpf, $usuario->email, 'nova_senha', $usuario->municipio_id, $usuario->perfil->value);

    $this->action->executa($dto);

    expect(password_verify('nova_senha', $usuario->fresh()->senha))->toBeTrue();
});

test('lanca excecao para uuid inexistente', function () {
    $municipio = Municipio::factory()->create();
    $dto = new AtualizaUsuarioDTO('uuid-inexistente', 'Nome', '000.000.000-00', 'email@test.com', null, $municipio->id, 'motorista');

    $this->action->executa($dto);
})->throws(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
