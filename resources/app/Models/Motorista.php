<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Motoristum
 * 
 * @property int $id_motorista
 * @property string $cnh
 * @property string $categoria
 * @property Carbon $validade_cnh
 * @property int $id_Usuario
 * 
 * @property Usuario $usuario
 * @property Collection|Rota[] $rotas
 *
 * @package App\Models
 */
class Motorista extends Model
{
	protected $table = 'motorista';
	public $timestamps = true;

	protected $casts = [
		'validade_cnh' => 'datetime',
		'id_Usuario' => 'int'
	];
	protected $primaryKey = 'id_motorista';


	protected $fillable = [
		'cnh',
		'categoria',
		'validade_cnh',
		'id_Usuario'
	];

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'id_Usuario');
	}

	public function rotas()
	{
		return $this->hasMany(Rota::class, 'id_motorista');
	}
}
