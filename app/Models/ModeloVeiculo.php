<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ModeloVeiculo
 * 
 * @property int $id_modelo_veiculo
 * @property string $marca
 * @property string $modelo
 * 
 * @property Collection|Veiculo[] $veiculos
 *
 * @package App\Models
 */
class ModeloVeiculo extends Model
{
    protected $table = 'modelo_veiculo';
    protected $primaryKey = 'id_modelo_veiculo';
    public $timestamps = false;

    protected $fillable = [
        'marca',
        'modelo',
        'categoria',
        'descricao',
        'status',
    ];

    public function veiculos()
    {
        return $this->hasMany(Veiculo::class, 'id_modelo_veiculo');
    }
}

