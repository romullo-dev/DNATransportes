<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'cliente';
    protected $primaryKey = 'id_cliente';

    protected $fillable = [
        'nome',
        'documento',
        'tipo'
    ];

    public $timestamps = false;

    public function notasEmitidas()
    {
        return $this->hasMany(NotaFiscal::class, 'cliente_remetente');
    }

    public function notasRecebidas()
    {
        return $this->hasMany(NotaFiscal::class, 'cliente_destinatario');
    }
}

