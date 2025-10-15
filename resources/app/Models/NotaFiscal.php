<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaFiscal extends Model
{
    use HasFactory;

    protected $table = 'notafiscal';
    protected $primaryKey = 'id_notaFiscal';

    protected $fillable = [
        'chave_acesso',
        'numero_nfe',
        'serie',
        'emissao',
        'valor_total',
        'peso',
        'quantidade_volumes',
        //'pdf',
        'cliente_remetente',
        'cliente_destinatario',
        'endereco_remetente',
        'endereco_destinatario',
        //'id_produto'
    ];

    public $timestamps = false;

    public function remetente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_remetente');
    }

    public function destinatario()
    {
        return $this->belongsTo(Cliente::class, 'cliente_destinatario');
    }

    public function enderecoRemetente()
    {
        return $this->belongsTo(Endereco::class, 'endereco_remetente');
    }

    public function enderecoDestinatario()
    {
        return $this->belongsTo(Endereco::class, 'endereco_destinatario');
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'id_produto');
    }

    public function pedido()
    {
        return $this->hasOne(Pedido::class, 'id_notaFiscal', 'id_notaFiscal');
    }
}
