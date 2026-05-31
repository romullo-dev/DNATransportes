<?php

namespace App\Repositories;

use App\Models\Historico;
use App\Models\Pedido;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class HistoricoRepository
{
    public function ultimoDaRota(int $rotaId): ?Historico
    {
        return Historico::query()
            ->where('rotas_id_rotas', $rotaId)
            ->orderByDesc('data')
            ->orderByDesc('id_historico')
            ->first();
    }

    public function idsPedidosDaRota(int $rotaId): Collection
    {
        return Historico::query()
            ->where('rotas_id_rotas', $rotaId)
            ->pluck('pedido_id_pedido')
            ->filter()
            ->unique()
            ->values();
    }

    public function pedidoJaVinculadoNaRota(int $rotaId, int $pedidoId): bool
    {
        return Historico::where('rotas_id_rotas', $rotaId)
            ->where('pedido_id_pedido', $pedidoId)
            ->exists();
    }

    public function removerVinculosDaRota(int $rotaId, Collection $pedidoIds): int
    {
        if ($pedidoIds->isEmpty()) {
            return 0;
        }

        return Historico::query()
            ->where('rotas_id_rotas', $rotaId)
            ->whereIn('pedido_id_pedido', $pedidoIds->values()->all())
            ->delete();
    }

    public function semDuplicidades(Collection $historicos): Collection
    {
        $vistos = [];

        return $historicos
            ->sortByDesc(fn (Historico $historico) => (($historico->data?->timestamp ?? 0) * 1000000) + $historico->id_historico)
            ->filter(function (Historico $historico) use (&$vistos) {
                $timestamp = $historico->data?->timestamp ?? 0;
                $chave = implode('|', [
                    $historico->pedido_id_pedido,
                    $historico->status,
                ]);

                if (collect($vistos[$chave] ?? [])->contains(fn (int $visto) => abs($visto - $timestamp) <= 300)) {
                    return false;
                }

                $vistos[$chave][] = $timestamp;

                return true;
            })
            ->values();
    }

    public function registrar(
        int $rotaId,
        int $pedidoId,
        CarbonInterface $data,
        string $status,
        ?string $foto = null,
        ?string $observacao = null,
    ): Historico {
        $existente = $this->buscarDuplicadoProximo($rotaId, $pedidoId, $status, $data);

        if ($existente) {
            $this->atualizarStatusPedido($pedidoId, $status);

            return $existente;
        }

        $historico = Historico::create([
            'rotas_id_rotas' => $rotaId,
            'pedido_id_pedido' => $pedidoId,
            'data' => $data,
            'status' => $status,
            'foto' => $foto,
            'observacao' => $observacao,
        ]);

        $this->atualizarStatusPedido($pedidoId, $status);

        return $historico;
    }

    private function buscarDuplicadoProximo(int $rotaId, int $pedidoId, string $status, CarbonInterface $data): ?Historico
    {
        $dataReferencia = Carbon::instance($data);

        return Historico::query()
            ->where('rotas_id_rotas', $rotaId)
            ->where('pedido_id_pedido', $pedidoId)
            ->where('status', $status)
            ->whereBetween('data', [
                $dataReferencia->copy()->subMinutes(5),
                $dataReferencia->copy()->addMinutes(5),
            ])
            ->orderByDesc('data')
            ->orderByDesc('id_historico')
            ->first();
    }

    private function atualizarStatusPedido(int $pedidoId, string $status): void
    {
        Pedido::whereKey($pedidoId)->update(['status' => $status]);
    }
}
