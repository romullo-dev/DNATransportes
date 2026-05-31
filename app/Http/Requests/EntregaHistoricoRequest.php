<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EntregaHistoricoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pedido_id_pedido' => ['required', 'integer', 'exists:pedido,id_pedido'],
            'rotas_id_rotas' => ['required', 'integer', 'exists:rotas,id_rotas'],
            'tipo' => ['required', 'string'],
            'status' => ['required', Rule::in(['Entrega realizada', 'Entrega não realizada'])],
            'foto' => ['nullable', 'image', 'max:4096'],
            'data' => ['required', 'date', 'before_or_equal:now'],
            'observacao' => ['nullable', 'string', 'max:500'],
        ];
    }
}
