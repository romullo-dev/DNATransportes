<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Frete extends Model
{
    use HasFactory;

    protected $table = 'fretes';

    protected $primaryKey = 'id_fretes';

    public $timestamps = false;

    protected $fillable = [
        'id_pedido',
        'valor',
        'peso_cobrado',
    ];

    protected $casts = [
        'valor' => 'float',
        'peso_cobrado' => 'float',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido', 'id_pedido');
    }
}
