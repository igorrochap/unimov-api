<?php

namespace App\DTO\Request\Municipio;

use App\Http\Requests\Municipio\NovoMunicipioRequest;

final readonly class NovoMunicipioDTO
{
    public function __construct(
        public string $nome,
        public string $uf,
        public string $secretaria_responsavel,
        public string $email,
        public string $telefone
    ){}

    public static function porRequest(NovoMunicipioRequest $request) : NovoMunicipioDTO
    {
        return new self(
            $request->string('nome'),
            $request->string('uf'), //TODO consumir da API dadosbrasil?
            $request->string('secretaria_responsavel'),
            $request->string('email'),
            $request->string('telefone')
        );
    }

    public function toArray() : array
    {
        return [
            'nome' => $this->nome,
            'uf' => $this->uf,
            'secretaria_responsavel' => $this->secretaria_responsavel,
            'email' => $this->email,
            'telefone' => $this->telefone

        ];
    }

}
