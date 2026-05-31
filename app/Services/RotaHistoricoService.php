<?php

namespace App\Services;

use App\DTOs\RegistrarHistoricoRotaData;
use App\Enums\StatusHistoricoPedido;
use App\Repositories\HistoricoRepository;
use Carbon\CarbonInterface;
use DomainException;
use Illuminate\Support\Collection;

class RotaHistoricoService
{
    public function __construct(
        private readonly HistoricoRepository $historicos,
    ) {}

    public function registrarMovimentacao(RegistrarHistoricoRotaData $data): Collection
    {
        $this->garantirRotaAberta($data->rotaId);

        $status = StatusHistoricoPedido::fromRouteAction($data->tipo, $data->acao);
        $pedidoIds = $this->historicos->idsPedidosDaRota($data->rotaId);

        if ($pedidoIds->isEmpty() && $data->pedidoId !== null) {
            $pedidoIds = collect([$data->pedidoId]);
        }

        if ($pedidoIds->isEmpty()) {
            throw new DomainException('Nenhum pedido vinculado a esta rota foi encontrado.');
        }

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
}
