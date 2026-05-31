<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedido';

    protected $primaryKey = 'id_pedido';

    protected $fillable = [
        'codigo_rastreamento',
        'id_notaFiscal',
        'status',
        'sla_previsto_em',
        'peso',
        'volume',
        'valor',
        'foto',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'sla_previsto_em' => 'datetime',
        'peso' => 'float',
        'volume' => 'float',
        'valor' => 'float',
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

    public function ocorrencias()
    {
        return $this->hasMany(Ocorrencia::class, 'id_pedido', 'id_pedido');
    }

    public function comprovantes()
    {
        return $this->hasMany(ComprovanteEntrega::class, 'id_pedido', 'id_pedido');
    }
}
