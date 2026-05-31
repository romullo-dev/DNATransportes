<?php

namespace App\Repositories;

use App\Enums\StatusHistoricoPedido;
use App\Models\Frete;
use App\Models\NotaFiscal;
use App\Models\Pedido;
use Illuminate\Support\Collection;

class PedidoRepository
{
    public function buscarPorChavesNfe(array $chavesNota): Collection
    {
        if ($chavesNota === []) {
            return collect();
        }

        return Pedido::whereHas('notaFiscal', function ($query) use ($chavesNota) {
            $query->whereIn('chave_acesso', $chavesNota);
        })
            ->with(['notaFiscal.remetente', 'notaFiscal.destinatario', 'historicos'])
            ->get();
    }

    public function listarDisponiveisParaRota(): Collection
    {
        return Pedido::with(['notaFiscal.remetente', 'notaFiscal.destinatario', 'historicos'])
            ->where(fn ($query) => $this->aplicarFiltroNaoEntregue($query))
            ->orderByDesc('created_at')
            ->get();
    }

    public function idsEntregues(iterable $pedidoIds): Collection
    {
        $ids = collect($pedidoIds)
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values();

        if ($ids->isEmpty()) {
            return collect();
        }

        return Pedido::whereIn('id_pedido', $ids)
            ->where(fn ($query) => $this->aplicarFiltroEntregue($query))
            ->pluck('id_pedido')
            ->map(fn ($id) => (int) $id)
            ->values();
    }

    public function firstOrCreateFromNotaFiscal(NotaFiscal $notaFiscal): Pedido
    {
        $pedido = Pedido::firstOrCreate(
            ['id_notaFiscal' => $notaFiscal->id_notaFiscal],
            [
                'codigo_rastreamento' => uniqid('dna_'),
                'status' => 'Aguardando coleta',
            ],
        );

        Frete::firstOrCreate([
            'id_pedido' => $pedido->id_pedido,
        ]);

        return $pedido;
    }

    private function aplicarFiltroEntregue($query): void
    {
        $query
            ->where(fn ($statusQuery) => $statusQuery
                ->where('status', StatusHistoricoPedido::ENTREGA_REALIZADA->value)
                ->orWhere('status', 'entregue'))
            ->orWhereHas('historicos', fn ($historicoQuery) => $historicoQuery
                ->where('status', StatusHistoricoPedido::ENTREGA_REALIZADA->value));
    }

    private function aplicarFiltroNaoEntregue($query): void
    {
        $query
            ->whereNotIn('status', [StatusHistoricoPedido::ENTREGA_REALIZADA->value, 'entregue'])
            ->whereDoesntHave('historicos', fn ($historicoQuery) => $historicoQuery
                ->where('status', StatusHistoricoPedido::ENTREGA_REALIZADA->value));
    }
}
