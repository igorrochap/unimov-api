<?php

namespace App\DTO\Request\Usuario;

use App\Http\Requests\Usuario\NovoUsuarioRequest;

final readonly class NovoUsuarioDTO
{
    public function __construct(
        public string $nome,
        public string $cpf,
        public string $email,
        public string $senha,
        public int $municipio,
        public string $perfil
    ) {}

    public static function porRequest(NovoUsuarioRequest $request): NovoUsuarioDTO
    {
        return new self(
            $request->string('nome'),
            $request->string('cpf'),
            $request->string('email'),
            $request->string('senha'),
            $request->integer('municipio'),
            $request->string('perfil'),
        );
    }

    public function toArray(): array
    {
        return [
            'nome' => $this->nome,
            'cpf' => $this->cpf,
            'email' => $this->email,
            'senha' => $this->senha,
            'municipio_id' => $this->municipio,
            'perfil' => $this->perfil,
        ];
    }
}
