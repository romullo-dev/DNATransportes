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
        // 'pdf',
        'cliente_remetente',
        'cliente_destinatario',
        'endereco_remetente',
        'endereco_destinatario',
        'id_produto',
    ];

    protected $casts = [
        'numero_nfe' => 'integer',
        'serie' => 'integer',
        'emissao' => 'datetime',
        'valor_total' => 'float',
        'peso' => 'float',
        'quantidade_volumes' => 'integer',
    ];

    public $timestamps = false;

    public function remetente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_remetente', 'id_cliente');
    }

    public function destinatario()
    {
        return $this->belongsTo(Cliente::class, 'cliente_destinatario', 'id_cliente');
    }

    public function enderecoRemetente()
    {
        return $this->belongsTo(Endereco::class, 'endereco_remetente', 'id_endereco');
    }

    public function enderecoDestinatario()
    {
        return $this->belongsTo(Endereco::class, 'endereco_destinatario', 'id_endereco');
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'id_produto', 'id_produto');
    }

    public function pedido()
    {
        return $this->hasOne(Pedido::class, 'id_notaFiscal', 'id_notaFiscal');
    }
}
