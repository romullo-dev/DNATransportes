<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComprovanteEntrega extends Model
{
    use HasFactory;

    protected $table = 'comprovantes_entrega';

    protected $fillable = [
        'id_pedido',
        'id_historico',
        'imagem',
        'assinatura',
        'latitude',
        'longitude',
        'entregue_em',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'entregue_em' => 'datetime',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido', 'id_pedido');
    }

    public function historico()
    {
        return $this->belongsTo(Historico::class, 'id_historico', 'id_historico');
    }
}
