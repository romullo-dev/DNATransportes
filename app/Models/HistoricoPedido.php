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
 * @property HistoricoRotas $historicoRotas
 */
class HistoricoPedido extends Model
{
    protected $table = 'historico_pedido'; // Nome da tabela no banco
    protected $primaryKey = 'id'; // Chave primária
    public $timestamps = TRUE; // Defina para true se quiser usar created_at/updated_at

    protected $casts = [
        'id' => 'int',
        'id_pedido' => 'int',
        'id_rotas' => 'int',
        'data_pedido' => 'datetime',
        'status' => 'string',
        'historico_rotas_id_historico' => 'int',
    ];

    protected $fillable = [
        'id_pedido',
        'id_rotas',
        'data_pedido',
        'status',
        'historico_rotas_id_historico',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido');
    }

    public function rota()
    {
        return $this->belongsTo(Rota::class, 'id_rotas');
    }

    public function historicoRotas()
    {
        return $this->belongsTo(Historico::class, 'historico_rotas_id_historico');
    }
}
