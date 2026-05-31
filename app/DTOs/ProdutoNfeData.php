<?php

namespace App\DTOs;

final readonly class ProdutoNfeData
{
    public function __construct(
        public string $nome,
        public ?string $codigo = null,
    ) {}
}
