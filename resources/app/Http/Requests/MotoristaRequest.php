<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MotoristaRequest extends FormRequest
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
            'id_Usuario' => 'required|exists:usuario,id_usuario',
            'cnh' => 'required|string|max:20',
            'categoria' => 'required|string|in:A,B,C,D,E,AB',
            'validade_cnh' => 'required|date|after_or_equal:today',
        ];
    }
}
