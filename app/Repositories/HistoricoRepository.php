<?php

namespace App\Repositories;

use App\Models\Historico;
use Carbon\CarbonInterface;
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

    public function registrar(
        int $rotaId,
        int $pedidoId,
        CarbonInterface $data,
        string $status,
        ?string $foto = null,
        ?string $observacao = null,
    ): Historico {
        return Historico::create([
            'rotas_id_rotas' => $rotaId,
            'pedido_id_pedido' => $pedidoId,
            'data' => $data,
            'status' => $status,
            'foto' => $foto,
            'observacao' => $observacao,
        ]);
    }
}
