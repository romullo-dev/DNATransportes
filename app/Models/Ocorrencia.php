<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ocorrencia extends Model
{
    use HasFactory;

    protected $table = 'ocorrencias';

    protected $fillable = [
        'id_pedido',
        'id_rotas',
        'id_historico',
        'tipo',
        'status',
        'descricao',
        'resolvida_em',
    ];

    protected $casts = [
        'resolvida_em' => 'datetime',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido', 'id_pedido');
    }

    public function rota()
    {
        return $this->belongsTo(Rota::class, 'id_rotas', 'id_rotas');
    }

    public function historico()
    {
        return $this->belongsTo(Historico::class, 'id_historico', 'id_historico');
    }
}
