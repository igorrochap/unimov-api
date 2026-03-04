<?php

namespace App\DTO\Request\Usuario;

use App\Http\Requests\Usuario\AtualizaUsuarioRequest;

final readonly class AtualizaUsuarioDTO
{
    public function __construct(
        public string $uuid,
        public string $nome,
        public string $cpf,
        public string $email,
        public ?string $senha,
        public int $municipio,
        public string $perfil,
    ) {}

    public static function porRequest(AtualizaUsuarioRequest $request): AtualizaUsuarioDTO
    {
        return new self(
            $request->route('uuid'),
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
        $dados = [
            'nome' => $this->nome,
            'cpf' => $this->cpf,
            'email' => $this->email,
            'municipio_id' => $this->municipio,
            'perfil' => $this->perfil,
        ];

        if ($this->senha !== null) {
            $dados['senha'] = $this->senha;
        }

        return $dados;
    }
}
