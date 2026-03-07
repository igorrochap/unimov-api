<?php

namespace App\Http\Requests\Municipio;

use Illuminate\Foundation\Http\FormRequest;

class NovoMunicipioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "nome" => 'required|unique:municipios,nome',
            'uf' => 'required',
            'secretaria_responsavel' => 'required',
            'email' => 'required',
            'telefone' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O nome é obrigatório',
            'nome.unique' => 'Já existe um município com esse nome!',
            'uf.required' => 'A UF é obrigatória!',
            'secretaria_responsavel.required' => 'Informe a secretaria responsável!',
            'email.required' => 'Email é obrigatório!',
            'telefone.required' => 'Telefone é obrigatório!',
        ];
    }
}
