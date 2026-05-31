<?php

namespace App\DTOs;

use App\Enums\AcaoHistoricoRota;
use App\Enums\TipoRota;
use Carbon\CarbonImmutable;

final readonly class RegistrarHistoricoRotaData
{
    public function __construct(
        public int $rotaId,
        public ?int $pedidoId,
        public TipoRota $tipo,
        public AcaoHistoricoRota $acao,
        public CarbonImmutable $data,
        public ?string $foto,
        public ?string $observacao,
    ) {}

    public static function fromArray(array $data, ?string $foto = null): self
    {
        $observacao = isset($data['observacao']) ? trim((string) $data['observacao']) : null;

        return new self(
            rotaId: (int) $data['rotas_id_rotas'],
            pedidoId: isset($data['pedido_id_pedido']) ? (int) $data['pedido_id_pedido'] : null,
            tipo: TipoRota::fromRequest($data['tipo'] ?? null),
            acao: AcaoHistoricoRota::fromRequest($data['status'] ?? null),
            data: CarbonImmutable::parse($data['data'] ?? now()),
            foto: $foto,
            observacao: $observacao !== '' ? $observacao : null,
        );
    }
}
