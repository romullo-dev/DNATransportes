<?php

namespace App\Services;

use App\DTOs\RotaData;
use App\Enums\StatusHistoricoPedido;
use App\Models\Rota;
use App\Repositories\HistoricoRepository;
use App\Repositories\PedidoRepository;
use App\Repositories\RotaRepository;
use Illuminate\Support\Facades\DB;

class RotaService
{
    public function __construct(
        private readonly RotaRepository $rotas,
        private readonly PedidoRepository $pedidos,
        private readonly HistoricoRepository $historicos,
    ) {}

    public function listar()
    {
        return $this->rotas->listarComRelacionamentos();
    }

    public function dadosFormulario(): array
    {
        return $this->rotas->dadosFormulario();
    }

    public function criar(RotaData $data): Rota
    {
        return DB::transaction(function () use ($data) {
            $rota = $this->rotas->criar($data);
            $this->vincularPedidosPorChaves($rota, $data);

            return $rota;
        });
    }

    public function atualizar(Rota $rota, RotaData $data): Rota
    {
        return DB::transaction(function () use ($rota, $data) {
            $rota = $this->rotas->atualizar($rota, $data);
            $this->vincularPedidosPorChaves($rota, $data);

            return $rota;
        });
    }

    private function vincularPedidosPorChaves(Rota $rota, RotaData $data): void
    {
        if ($data->chavesNota === []) {
            return;
        }

        $statusInicial = StatusHistoricoPedido::inicialDaRota($data->tipo);
        $pedidos = $this->pedidos->buscarPorChavesNfe($data->chavesNota);

        foreach ($pedidos as $pedido) {
            if ($this->historicos->pedidoJaVinculadoNaRota($rota->id_rotas, $pedido->id_pedido)) {
                continue;
            }

            $this->historicos->registrar(
                rotaId: $rota->id_rotas,
                pedidoId: $pedido->id_pedido,
                data: now(),
                status: $statusInicial->value,
                foto: '',
                observacao: $data->observacoes,
            );
        }
    }
}
