<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CentroDistribuicao
 * 
 * @property int $id_centro_distribuicao
 * @property string $nome
 * @property string $cidade
 * @property string $uf
 * @property string $latitude
 * @property string $longitude
 * @property string $status
 * 
 * @property Collection|Rota[] $rotas
 *
 * @package App\Models
 */
class CentroDistribuicao extends Model
{
	protected $table = 'centro_distribuicao';
	protected $primaryKey = 'id_centro_distribuicao';
	public $timestamps = false;

	protected $fillable = [
		'nome',
		'cidade',
		'uf',
		'latitude',
		'longitude',
		'status',
		'logradouro',
		'cep',
		'bairro'
	];

	public function rotas()
	{
		return $this->hasMany(Rota::class, 'id_origem');
	}
}
