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
 * @property string $foto
 * 
 * @property Pedido $pedido
 * @property Rota $rota
 */
class Historico extends Model
{
    protected $table = 'historico';
    protected $primaryKey = 'id_historico';
    public $timestamps = true; 
	
    protected $casts = [
        'id_historico' => 'int',
        'rotas_id_rotas' => 'int',
        'pedido_id_pedido' => 'int',
        'data' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $fillable = [
        'rotas_id_rotas',
        'pedido_id_pedido',
        'data',
        'status',
        'foto',
        'observacao'
    ];

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class, 'pedido_id_pedido');
    }

    public function rota(): BelongsTo
    {
        return $this->belongsTo(Rota::class, 'rotas_id_rotas');
    }
}
