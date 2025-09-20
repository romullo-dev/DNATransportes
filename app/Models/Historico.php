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
    // Nome da tabela no banco de dados
    protected $table = 'historico_rotas'; // Nome da tabela no banco de dados

    // Chave primária
    protected $primaryKey = 'id_historico';

    // Marcar como timestamps
    public $timestamps = true;

    // Casts para garantir que os campos sejam tratados como os tipos corretos
    protected $casts = [
        'id_historico' => 'int',
        'rotas_id_rotas' => 'int',
        'pedido_id_pedido' => 'int',
        'data' => 'datetime',  // Campo datetime
        'created_at' => 'datetime',  // Campo de timestamp
        'updated_at' => 'datetime',  // Campo de timestamp
    ];

    // Atributos que podem ser preenchidos em massa
    protected $fillable = [
        'rotas_id_rotas',
        'pedido_id_pedido',
        'data',
        'status',
        'foto',
        'observacao'
    ];

    /**
     * Relacionamento com o modelo Pedido
     */
    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class, 'pedido_id_pedido');
    }

    /**
     * Relacionamento com o modelo Rota
     */
    public function rota(): BelongsTo
    {
        return $this->belongsTo(Rota::class, 'rotas_id_rotas');
    }
}
