<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VeiculoRequest extends FormRequest
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
            'placa' => 'required|string|max:7|unique:veiculo,placa',
            'ano' => 'required|digits:4|integer|min:1900|max:' . date('Y'),
            'cor' => 'required|string|max:100',
            'status_veiculo' => 'required|in:Ativo,Inativo',
            'observacoes' => 'nullable|string',
            'id_modelo_veiculo' => 'required|exists:modelo_veiculo,id_modelo_veiculo',
            'renavam' => 'nullable|string|max:20|unique:veiculo,renavam',
            'chassi' => 'nullable|string|max:30|unique:veiculo,chassi',
            'tara_kg' => 'required|numeric|min:0',
            'pbt_kg' => 'required|numeric|gte:tara_kg',
        ];
    }



    public function messages(): array
    {
        return [
            'placa.required' => 'A placa do veículo é obrigatória.',
            'placa.unique' => 'Essa placa já está cadastrada.',
            'ano.required' => 'O ano do veículo é obrigatório.',
            'ano.digits' => 'Informe um ano válido com 4 dígitos.',
            'status_veiculo.in' => 'Status inválido. Use: ativo, inativo ou manutenção.',
            'modelo_Veiculo.exists' => 'O modelo de veículo informado não existe.',
            'renavam.unique' => 'Esse RENAVAM já está em uso.',
            'chassi.unique' => 'Esse chassi já está em uso.',
            'pbt_kg.gt' => 'O peso bruto total deve ser maior que a tara.',
        ];
    }
}
