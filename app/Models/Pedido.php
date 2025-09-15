<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedido';
    protected $primaryKey = 'id_pedido';

    protected $fillable = [
        'codigo_rastreamento',
        'id_notaFiscal',
        'status',
        'created_at',
        'updated_at'
    ];

    public $timestamps = true;

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
        return $this->hasMany(Historico::class, 'pedido_id_pedido', 'id_pedido');
    }

      public function rotas()
    {
        return $this->hasManyThrough(Rota::class, Historico::class, 'pedido_id_pedido', 'id_rotas', 'id_pedido', 'rotas_id_rotas');
    }
}
