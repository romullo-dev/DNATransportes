<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RotaRequest extends FormRequest
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
            'rotas_id_rotas' => 'required',
            'pedido_id_pedido' => 'required',
            'data' => 'required|date|before_or_equal:' . now()->format('Y-m-d H:i:s'),
            'status' => 'required',
            'foto' => 'nullable|image|max:2048',
            'observacao' => 'nullable|string|max:255',
            'tipo' => 'nullable'
        ];
    }
}
