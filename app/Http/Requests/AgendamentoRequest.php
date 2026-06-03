<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AgendamentoRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nome_cliente' => ['required', 'string', 'min:3', 'max:255'],
            'telefone_cliente' => ['required', 'string', 'min:10', 'max:20'],
        ];
        
    }

    public function messages(): array
    {
        return [
            'nome_cliente.required' => 'O nome do cliente é obrigatório.',
            'telefone_cliente.required' => 'O telefone é obrigatório.',
        ];
    }
}
