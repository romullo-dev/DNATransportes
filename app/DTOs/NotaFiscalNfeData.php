<?php

namespace App\DTOs;

use Carbon\CarbonImmutable;

final readonly class NotaFiscalNfeData
{
    public function __construct(
        public string $chaveAcesso,
        public int $numero,
        public ?int $serie,
        public CarbonImmutable $emissao,
        public float $valorTotal,
        public ?float $peso,
        public int $quantidadeVolumes,
    ) {}
}
