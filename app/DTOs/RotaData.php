<?php

namespace App\DTOs;

use App\Enums\TipoRota;
use Carbon\CarbonImmutable;

final readonly class RotaData
{
    /**
     * @param  array<int, string>  $chavesNota
     */
    public function __construct(
        public TipoRota $tipo,
        public int $origemId,
        public ?int $destinoId,
        public float $distancia,
        public CarbonImmutable $previsao,
        public CarbonImmutable $dataInicio,
        public int $motoristaId,
        public int $veiculoId,
        public ?string $observacoes,
        public array $chavesNota,
    ) {}

    public static function fromArray(array $data): self
    {
        $observacoes = isset($data['observacoes']) ? trim((string) $data['observacoes']) : null;

        return new self(
            tipo: TipoRota::fromRequest($data['tipo'] ?? null),
            origemId: (int) $data['id_origem'],
            destinoId: isset($data['id_destino']) ? (int) $data['id_destino'] : null,
            distancia: (float) $data['distancia'],
            previsao: CarbonImmutable::parse($data['previsao']),
            dataInicio: CarbonImmutable::parse($data['data_inicio']),
            motoristaId: (int) $data['id_motorista'],
            veiculoId: (int) $data['id_veiculo'],
            observacoes: $observacoes !== '' ? $observacoes : null,
            chavesNota: self::normalizarChavesNota($data['chave_nota'] ?? null),
        );
    }

    public function destinoFinalId(): int
    {
        return $this->destinoId ?? $this->origemId;
    }

    public function tipoParaBanco(): string
    {
        return match ($this->tipo) {
            TipoRota::COLETA => 'coleta',
            TipoRota::TRANSFERENCIA => 'transferencia',
            TipoRota::ENTREGA => 'entrega',
        };
    }

    /**
     * @return array<int, string>
     */
    private static function normalizarChavesNota(?string $chaves): array
    {
        if (! $chaves) {
            return [];
        }

        return collect(explode(',', $chaves))
            ->map(fn (string $chave) => trim($chave))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }
}
