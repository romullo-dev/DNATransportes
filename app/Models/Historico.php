<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Historico
 * 
 * @property int $id_historico
 * @property int $rotas_id_rotas
 * @property int $pedido_id_pedido
 * @property \DateTime $data
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property string $status
 * @property string|null $foto
 * @property string|null $observacao
 * 
 * @property Pedido $pedido
 * @property Rota $rota
 */
class Historico extends Model
{
    protected $table = 'historico_rotas';
    protected $primaryKey = 'id_historico';
    public $timestamps = true;

    protected $fillable = [
        'rotas_id_rotas',
        'data',
        'status',
        'foto',
        'observacao',
    ];

    protected $casts = [
        'id_historico' => 'int',
        'rotas_id_rotas' => 'int',
        'data' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function rota()
    {
        return $this->belongsTo(Rota::class, 'rotas_id_rotas', 'id_rotas');
    }

    public function pedidos()
    {
        return $this->hasMany(HistoricoPedido::class, 'historico_rotas_id_historico', 'id_historico');
    }
}
