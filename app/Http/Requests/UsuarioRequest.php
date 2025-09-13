<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsuarioRequest extends FormRequest
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
            'nome' => 'required|string|max:100',
            'user' => 'required|min:4|max:50|unique:usuario,user',
            'password' => 'required|string|min:6',
            'tipo_usuario' => 'required|in:admin,operador,motorista',
            'cpf' => 'required|min:11|max:14|unique:usuario,cpf',
            'status_funcionario' => 'required|in:ativo,inativo',
            'email' => 'required|email|unique:usuario,email',
            'foto' => 'nullable|image|max:2048',
            'telefone' => 'required|min:10|max:11|unique:usuario,telefone'
        ];
    }
}
