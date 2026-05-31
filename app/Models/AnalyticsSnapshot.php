<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalyticsSnapshot extends Model
{
    use HasFactory;

    protected $table = 'analytics_snapshots';

    protected $fillable = [
        'data_referencia',
        'total_pedidos',
        'pedidos_entregues',
        'pedidos_atrasados',
        'pedidos_em_aberto',
        'percentual_no_prazo',
        'percentual_fora_prazo',
        'total_ocorrencias',
        'valor_total_frete',
        'peso_total',
        'volumes_total',
        'tempo_medio_entrega_horas',
    ];

    protected $casts = [
        'data_referencia' => 'date',
        'percentual_no_prazo' => 'float',
        'percentual_fora_prazo' => 'float',
        'valor_total_frete' => 'float',
        'peso_total' => 'float',
        'tempo_medio_entrega_horas' => 'float',
    ];
}
