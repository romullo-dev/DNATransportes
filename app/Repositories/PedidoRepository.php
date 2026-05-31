<?php

namespace App\Repositories;

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
        })->get();
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
}
