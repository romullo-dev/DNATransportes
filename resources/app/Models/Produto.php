<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    protected $table = 'produtos';
    protected $primaryKey = 'id_produto';

    protected $fillable = [
        'nome'
    ];

    public $timestamps = false;

    public function notaFiscal()
    {
        return $this->hasMany(NotaFiscal::class, 'id_produto');
    }
}

