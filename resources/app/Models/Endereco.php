<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
    use HasFactory;

    protected $table = 'endereco';
    protected $primaryKey = 'id_endereco';

    protected $fillable = [
        'cep',
        'logradouro',
        'casa',
        'observacao',
        'uf',
        'bairro',
        'cidade'
    ];

    public $timestamps = false;

    public function notasRemetente()
    {
        return $this->hasMany(NotaFiscal::class, 'endereco_remetente');
    }

    public function notasDestinatario()
    {
        return $this->hasMany(NotaFiscal::class, 'endereco_destinatario');
    }
}

