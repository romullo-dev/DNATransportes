<?php

namespace App\Services;

use App\DTOs\RegistrarHistoricoRotaData;
use App\Enums\StatusHistoricoPedido;
use App\Repositories\HistoricoRepository;
use App\Repositories\PedidoRepository;
use Carbon\CarbonInterface;
use DomainException;
use Illuminate\Support\Collection;

class RotaHistoricoService
{
    public function __construct(
        private readonly HistoricoRepository $historicos,
        private readonly PedidoRepository $pedidos,
    ) {}

    public function registrarMovimentacao(RegistrarHistoricoRotaData $data): Collection
    {
        $status = StatusHistoricoPedido::fromRouteAction($data->tipo, $data->acao);
        $pedidoIds = $this->historicos->idsPedidosDaRota($data->rotaId);

        if ($pedidoIds->isEmpty() && $data->pedidoId !== null) {
            $pedidoIds = collect([$data->pedidoId]);
        }

        if ($pedidoIds->isEmpty()) {
            throw new DomainException('Nenhum pedido vinculado a esta rota foi encontrado.');
        }

        $this->garantirPedidosNaoEntregues($pedidoIds);
        $this->garantirRotaAberta($data->rotaId);

        return $pedidoIds->map(fn (int $pedidoId) => $this->historicos->registrar(
            rotaId: $data->rotaId,
            pedidoId: $pedidoId,
            data: $data->data,
            status: $status->value,
            foto: $data->foto,
            observacao: $data->observacao,
        ));
    }

    public function registrarEntregaPedido(
        int $rotaId,
        int $pedidoId,
        string $status,
        CarbonInterface $data,
        ?string $foto = null,
        ?string $observacao = null,
    ) {
        $statusEntrega = StatusHistoricoPedido::entregaFinal($status);
        $this->garantirPedidosNaoEntregues(collect([$pedidoId]));

        return $this->historicos->registrar(
            rotaId: $rotaId,
            pedidoId: $pedidoId,
            data: $data,
            status: $statusEntrega->value,
            foto: $foto,
            observacao: $observacao,
        );
    }

    private function garantirRotaAberta(int $rotaId): void
    {
        $ultimoHistorico = $this->historicos->ultimoDaRota($rotaId);
        $status = StatusHistoricoPedido::tryFrom((string) $ultimoHistorico?->status);

        if ($status?->isFinalizadorDeRota()) {
            throw new DomainException('Esta rota já foi finalizada e não permite novas movimentações.');
        }
    }

    private function garantirPedidosNaoEntregues(Collection $pedidoIds): void
    {
        $idsEntregues = $this->pedidos->idsEntregues($pedidoIds);

        if ($idsEntregues->isEmpty()) {
            return;
        }

        if ($idsEntregues->count() === 1) {
            throw new DomainException('Este pedido já foi entregue e não pode entrar em uma nova rota.');
        }

        throw new DomainException('Estes pedidos já foram entregues e não podem entrar em uma nova rota: #'.$idsEntregues->implode(', #').'.');
    }
}
