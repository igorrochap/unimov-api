<?php

namespace App\Http\Requests\Usuario;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AtualizaUsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $uuid = $this->route('uuid');

        return [
            'nome' => 'required',
            'email' => ['required', 'email', Rule::unique('usuarios', 'email')->ignore($uuid, 'uuid')],
            'cpf' => ['required', Rule::unique('usuarios', 'cpf')->ignore($uuid, 'uuid')],
            'municipio' => 'required',
            'perfil' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O nome é obrigatório.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'O e-mail informado é inválido.',
            'email.unique' => 'Este e-mail já está em uso.',
            'cpf.required' => 'O CPF é obrigatório.',
            'cpf.unique' => 'Este CPF já está cadastrado.',
            'municipio.required' => 'O município é obrigatório.',
            'perfil.required' => 'O perfil é obrigatório.',
        ];
    }
}
