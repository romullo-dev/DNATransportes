<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class Veiculo extends Model
{
    protected $table = 'veiculo';
    protected $primaryKey = 'id_Veiculo';
    public $timestamps = true;

    protected $casts = [
        'Ano' => 'integer',
        'id_modelo_veiculo' => 'integer',
        'tara_kg' => 'float',
        'pbt_kg' => 'float',
        'capacidade_kg' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'placa',
        'ano',
        'cor',
        'status_veiculo',
        'observacoes',
        'id_modelo_veiculo',
        'renavam',
        'chassi',
        'tara_kg',
        'pbt_kg',
    ];

    public function modelo_veiculo()
    {
        return $this->belongsTo(ModeloVeiculo::class, 'id_modelo_veiculo', 'id_modelo_veiculo');
    }

    public function rotas()
    {
        return $this->hasMany(Rota::class, 'id_veiculo');
    }
}


