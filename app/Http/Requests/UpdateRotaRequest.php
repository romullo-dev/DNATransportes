<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateRotaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('tipo')) {
            $this->merge([
                'tipo' => str_replace([' ', '_', '-'], '', Str::lower(Str::ascii((string) $this->input('tipo')))),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'tipo' => ['required', Rule::in(['coleta', 'transferencia', 'entrega'])],
            'id_origem' => ['required', 'integer', 'exists:centro_distribuicao,id_centro_distribuicao'],
            'id_destino' => [Rule::requiredIf($this->input('tipo') === 'transferencia'), 'nullable', 'integer', 'exists:centro_distribuicao,id_centro_distribuicao'],
            'distancia' => ['required', 'numeric', 'min:0'],
            'previsao' => ['required', 'date'],
            'data_inicio' => ['required', 'date'],
            'id_motorista' => ['required', 'integer', 'exists:motorista,id_motorista'],
            'id_veiculo' => ['required', 'integer', 'exists:veiculo,id_Veiculo'],
            'observacoes' => ['nullable', 'string', 'max:5000'],
            'pedido_id_pedido' => ['nullable', 'integer', 'exists:pedido,id_pedido'],
            'chave_nota' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
