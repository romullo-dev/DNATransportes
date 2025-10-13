<?php

namespace App\Exports;

use App\Models\Pedido;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PedidosExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Pedido::with(['historicos' => function ($q) {
            $q->latest('data');
        }])
        ->get()
        ->map(function ($pedido) {
            $ultimoStatus = optional($pedido->historicos->first())->status ?? 'Sem status';

            return [
                'ID Pedido' => $pedido->id_pedido,
                'Código de Rastreamento' => $pedido->codigo_rastreamento,
                'Status Atual' => $ultimoStatus,
                'Criado em' => $pedido->created_at?->format('d/m/Y H:i'),
                'Atualizado em' => $pedido->updated_at?->format('d/m/Y H:i'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID Pedido',
            'Código de Rastreamento',
            'Status Atual',
            'Criado em',
            'Atualizado em'
        ];
    }
}
