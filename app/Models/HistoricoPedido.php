<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class HistoricoPedido
 * 
 * @property int $id
 * @property int $id_pedido
 * @property int $id_rotas
 * @property \DateTime $data_pedido
 * @property string $status
 * @property int $historico_rotas_id_historico
 * 
 * @property Pedido $pedido
 * @property Rota $rota
 * @property Historico $historicoRotas
 */
class HistoricoPedido extends Model
{
    protected $table = 'historico_pedido';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'id_pedido',
        'status',
        'historico_rotas_id_historico',
    ];

    protected $casts = [
        'id' => 'int',
        'id_pedido' => 'int',
        'historico_rotas_id_historico' => 'int',
        'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido');
    }

    public function historicoRotas()
    {
        return $this->belongsTo(Historico::class, 'historico_rotas_id_historico', 'id_historico');
    }
}
