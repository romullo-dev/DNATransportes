<?php

namespace App\Http\Requests\Analytics;

use Illuminate\Foundation\Http\FormRequest;

class AnalyticsFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'data_inicio' => ['nullable', 'date'],
            'data_fim' => ['nullable', 'date', 'after_or_equal:data_inicio'],
            'cliente_id' => ['nullable', 'integer', 'exists:cliente,id_cliente'],
            'motorista_id' => ['nullable', 'integer', 'exists:motorista,id_motorista'],
            'veiculo_id' => ['nullable', 'integer', 'exists:veiculo,id_Veiculo'],
            'status' => ['nullable', 'string', 'max:100'],
            'rota_id' => ['nullable', 'integer', 'exists:rotas,id_rotas'],
            'tipo_ocorrencia' => ['nullable', 'string', 'max:60'],
            'filial_id' => ['nullable', 'integer', 'exists:centro_distribuicao,id_centro_distribuicao'],
        ];
    }
}
