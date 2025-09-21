<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pedido extends Model
{
    protected $table = 'pedido';
    protected $primaryKey = 'id_pedido';
    public $timestamps = true;

    protected $fillable = [
        'codigo_rastreamento',
        'id_notaFiscal',
    ];

    protected $casts = [
        'id_pedido' => 'int',
        'id_notaFiscal' => 'int',
        'codigo_rastreamento' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function notaFiscal()
    {
        return $this->belongsTo(NotaFiscal::class, 'id_notaFiscal');
    }

    public function frete()
    {
        return $this->hasOne(Frete::class, 'id_pedido', 'id_pedido');
    }

    public function historicos()
{
    return $this->hasMany(HistoricoPedido::class, 'id_pedido', 'id_pedido')
                ->orderBy('created_at', 'desc'); // mais recente primeiro
}


    public function historicoRotas()
    {
        return $this->hasManyThrough(
    Rota::class,
    HistoricoPedido::class,
    'id_pedido',   // FK em historico_pedido
    'id_rotas',    // FK em rota (mas isso NÃO existe em historico_pedido)
    'id_pedido',   // PK em pedido
    'id'           // PK em historico_pedido
);

    }
}
