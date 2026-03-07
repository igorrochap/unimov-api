<?php

namespace App\Http\Requests\Periodo;

use Illuminate\Foundation\Http\FormRequest;

class NovoPeriodoVigenciaRequest extends FormRequest
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
            // não pedimos município ou descrição, já estaria cadastrado no momento da inscrição
            'inicio' => 'required|date',
            'fim' => 'required|date|after:inicio',
        ];
    }

    public function messages(): array
    {
        return [
            'inicio.required' => 'A data de início do período é obrigatório.',
            'inicio.date' => 'A data de início operacional deve ser válida.',
            'fim.required' => 'A data de término do período é obrigatório.',
            'fim.after' => 'O fim do período deve ser posterior ao início.',
        ];
    }
}
