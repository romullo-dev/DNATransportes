<?php

namespace App\Repositories;

use App\DTOs\AdminRotaUpdateData;
use App\DTOs\RotaData;
use App\Models\CentroDistribuicao;
use App\Models\Motorista;
use App\Models\Pedido;
use App\Models\Rota;
use App\Models\Veiculo;
use Illuminate\Support\Collection;

class RotaRepository
{
    public function listarComRelacionamentos(): Collection
    {
        return Rota::with(['pedidos.historicos', 'motorista.usuario', 'veiculo', 'origem', 'destino', 'historicos'])
            ->orderByDesc('data_inicio')
            ->get();
    }

    /**
     * @return array{centros: Collection, motoristas: Collection, veiculos: Collection, pedido: Collection}
     */
    public function dadosFormulario(): array
    {
        return [
            'centros' => CentroDistribuicao::where('status', 'Ativo')->get(),
            'motoristas' => Motorista::with('usuario')->get(),
            'veiculos' => Veiculo::where('status_veiculo', 'Ativo')->get(),
            'pedido' => Pedido::with('historicos')->orderByDesc('created_at')->get(),
        ];
    }

    public function criar(RotaData $data): Rota
    {
        return Rota::create($this->payload($data, incluiDataCriacao: true));
    }

    public function atualizar(Rota $rota, RotaData $data): Rota
    {
        $rota->update($this->payload($data, incluiDataCriacao: false));

        return $rota->refresh();
    }

    public function carregarParaEdicaoAdmin(Rota $rota): Rota
    {
        return $rota->loadMissing([
            'pedidos.notaFiscal.remetente',
            'pedidos.notaFiscal.destinatario',
            'pedidos.historicos',
            'motorista.usuario',
            'veiculo',
            'origem',
            'destino',
            'historicos',
            'alteracoes.usuario',
        ]);
    }

    public function atualizarAdmin(Rota $rota, AdminRotaUpdateData $data): Rota
    {
        $rota->update([
            'id_motorista' => $data->motoristaId,
            'id_veiculo' => $data->veiculoId,
            'id_origem' => $data->origemId,
            'id_destino' => $data->destinoId ?? $data->origemId,
            'status' => $data->status,
            'observacoes' => $data->observacoes,
        ]);

        return $rota->refresh();
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(RotaData $data, bool $incluiDataCriacao): array
    {
        $payload = [
            'tipo' => $data->tipoParaBanco(),
            'id_origem' => $data->origemId,
            'id_destino' => $data->destinoFinalId(),
            'distancia' => $data->distancia,
            'previsao' => $data->previsao,
            'data_rota' => $data->dataInicio,
            'data_inicio' => $data->dataInicio,
            'id_motorista' => $data->motoristaId,
            'id_veiculo' => $data->veiculoId,
            'observacoes' => $data->observacoes ?? '',
        ];

        if ($incluiDataCriacao) {
            $payload['data_criacao'] = now();
        }

        return $payload;
    }
}
