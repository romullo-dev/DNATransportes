<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClienteContato extends Model
{
    use HasFactory;

    protected $table = 'cliente_contatos';

    protected $fillable = [
        'id_cliente',
        'nome',
        'email',
        'telefone',
        'cargo',
        'principal',
    ];

    protected $casts = [
        'principal' => 'boolean',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }
}
