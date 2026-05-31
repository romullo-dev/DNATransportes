<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RotaAlteracao extends Model
{
    protected $table = 'rota_alteracoes';

    protected $fillable = [
        'rotas_id_rotas',
        'id_usuario',
        'motivo',
        'dados_anteriores',
        'dados_novos',
    ];

    protected $casts = [
        'dados_anteriores' => 'array',
        'dados_novos' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function rota()
    {
        return $this->belongsTo(Rota::class, 'rotas_id_rotas', 'id_rotas');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}
