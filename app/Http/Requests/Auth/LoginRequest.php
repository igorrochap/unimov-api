<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'credencial' => 'required|string',
            'senha' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'credencial.required' => 'A credencial é obrigatória.',
            'senha.required' => 'A senha é obrigatória.',
        ];
    }
}
