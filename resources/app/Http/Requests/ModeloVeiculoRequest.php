<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModeloVeiculoRequest extends FormRequest
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
    public function rules()
    {
        return [
            'marca' => 'required|string|max:45',
            'modelo' => 'required|string|max:100',
            'categoria' => 'required|string|max:30',
            'descricao' => 'nullable|string',
            'status' => 'required|in:ativo,inativo',
        ];
    }
}
