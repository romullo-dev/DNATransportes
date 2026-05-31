<?php

namespace App\DTOs;

final readonly class ClienteNfeData
{
    public function __construct(
        public string $nome,
        public string $documento,
        public string $tipo,
    ) {}
}
