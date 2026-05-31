<?php

namespace App\Models;

use App\Enums\StatusHistoricoPedido;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedido';

    protected $primaryKey = 'id_pedido';

    protected $fillable = [
        'codigo_rastreamento',
        'id_notaFiscal',
        'status',
        'sla_previsto_em',
        'peso',
        'volume',
        'valor',
        'foto',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'sla_previsto_em' => 'datetime',
        'peso' => 'float',
        'volume' => 'float',
        'valor' => 'float',
    ];

    public $timestamps = true;

    public function notaFiscal()
    {
        return $this->belongsTo(NotaFiscal::class, 'id_notaFiscal');
    }

    public function frete()
    {
        return $this->hasOne(Frete::class, 'id_pedido', 'id_pedido');
    }

    public function historicos()
    {
        return $this->hasMany(Historico::class, 'pedido_id_pedido', 'id_pedido')
            ->orderByDesc('data')
            ->orderByDesc('id_historico');
    }

    public function rotas()
    {
        return $this->hasManyThrough(Rota::class, Historico::class, 'pedido_id_pedido', 'id_rotas', 'id_pedido', 'rotas_id_rotas');
    }

    public function ocorrencias()
    {
        return $this->hasMany(Ocorrencia::class, 'id_pedido', 'id_pedido');
    }

    public function comprovantes()
    {
        return $this->hasMany(ComprovanteEntrega::class, 'id_pedido', 'id_pedido');
    }

    public function statusAtual(): string
    {
        $historico = $this->relationLoaded('historicos')
            ? $this->historicos->first()
            : $this->historicos()->first();

        return (string) ($historico?->status ?? $this->status ?? 'Sem histórico');
    }

    public function estaEntregue(): bool
    {
        if (StatusHistoricoPedido::representaEntregaRealizada($this->status)) {
            return true;
        }

        if ($this->relationLoaded('historicos')) {
            return $this->historicos->contains(
                fn (Historico $historico) => $historico->status === StatusHistoricoPedido::ENTREGA_REALIZADA->value
            );
        }

        return $this->historicos()
            ->where('status', StatusHistoricoPedido::ENTREGA_REALIZADA->value)
            ->exists();
    }
}
