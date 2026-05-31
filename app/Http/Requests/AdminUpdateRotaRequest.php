<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminUpdateRotaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'pedido_ids' => $this->input('pedido_ids', []),
        ]);
    }

    public function rules(): array
    {
        return [
            'id_motorista' => ['required', 'integer', 'exists:motorista,id_motorista'],
            'id_veiculo' => ['required', 'integer', 'exists:veiculo,id_Veiculo'],
            'id_origem' => ['required', 'integer', 'exists:centro_distribuicao,id_centro_distribuicao'],
            'id_destino' => ['nullable', 'integer', 'exists:centro_distribuicao,id_centro_distribuicao'],
            'status' => ['required', Rule::in(['Planejada', 'Em andamento', 'Finalizada', 'Cancelada'])],
            'observacoes' => ['nullable', 'string', 'max:5000'],
            'motivo_alteracao' => ['required', 'string', 'min:5', 'max:1000'],
            'pedido_ids' => ['array'],
            'pedido_ids.*' => ['integer', 'exists:pedido,id_pedido'],
        ];
    }
}
