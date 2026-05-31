<?php

namespace App\DTOs;

final readonly class AdminRotaUpdateData
{
    /**
     * @param  array<int, int>  $pedidoIds
     */
    public function __construct(
        public int $motoristaId,
        public int $veiculoId,
        public int $origemId,
        public ?int $destinoId,
        public string $status,
        public ?string $observacoes,
        public string $motivoAlteracao,
        public array $pedidoIds,
    ) {}

    public static function fromArray(array $data): self
    {
        $observacoes = isset($data['observacoes']) ? trim((string) $data['observacoes']) : null;

        return new self(
            motoristaId: (int) $data['id_motorista'],
            veiculoId: (int) $data['id_veiculo'],
            origemId: (int) $data['id_origem'],
            destinoId: isset($data['id_destino']) ? (int) $data['id_destino'] : null,
            status: (string) $data['status'],
            observacoes: $observacoes !== '' ? $observacoes : null,
            motivoAlteracao: trim((string) $data['motivo_alteracao']),
            pedidoIds: collect($data['pedido_ids'] ?? [])
                ->map(fn ($id) => (int) $id)
                ->filter()
                ->unique()
                ->values()
                ->all(),
        );
    }
}
