<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManutencaoVeiculo extends Model
{
    use HasFactory;

    protected $table = 'manutencoes_veiculos';

    protected $fillable = [
        'id_Veiculo',
        'tipo',
        'data_manutencao',
        'proxima_manutencao',
        'valor',
        'observacao',
    ];

    protected $casts = [
        'data_manutencao' => 'date',
        'proxima_manutencao' => 'date',
        'valor' => 'float',
    ];

    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class, 'id_Veiculo', 'id_Veiculo');
    }
}
