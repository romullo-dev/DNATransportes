<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'rotas_id_rotas' => ['required', 'integer', 'exists:rotas,id_rotas'],
            'pedido_id_pedido' => ['nullable', 'integer', 'exists:pedido,id_pedido'],
            'data' => ['required', 'date', 'before_or_equal:now'],
            'status' => ['required', Rule::in(['Em trânsito', 'Finalizado', 'Ocorrência'])],
            'foto' => ['nullable', 'image', 'max:4096'],
            'observacao' => ['nullable', 'string', 'max:500'],
            'tipo' => ['required', 'string'],
        ];
    }
}
