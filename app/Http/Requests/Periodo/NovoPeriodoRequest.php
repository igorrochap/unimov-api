<?php

namespace App\Http\Requests\Periodo;

use Illuminate\Foundation\Http\FormRequest;

class NovoPeriodoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'municipio_id' => 'required|exists:municipios,id',
            'descricao' => 'required|string',
            'inicio_inscricao' => 'required|date',
            'fim_inscricao' => 'required|date|after:inicio_inscricao',
            'inicio' => 'required|date',
            'fim' => 'required|date|after:inicio',
        ];
    }

    public function messages(): array
    {
        return [
            'municipio_id.required' => 'O município é obrigatório.',
            'municipio_id.exists' => 'O município informado não existe.',
            'descricao.required' => 'A descrição do período é obrigatória.',
            'inicio_inscricao.required' => 'A data de início das inscrições é obrigatória.',
            'inicio_inscricao.date' => 'Informe uma data e hora válida para o início.',
            'fim_inscricao.required' => 'A data de término das inscrições é obrigatória.',
            'fim_inscricao.after' => 'O término da inscrição deve ser após o início.',
            'inicio.required' => 'A data de início do período é obrigatório.',
            'inicio.date' => 'A data de início operacional deve ser válida.',
            'fim.required' => 'A data de término do período é obrigatório.',
            'fim.after' => 'O fim do período deve ser posterior ao início.',
        ];
    }
}
