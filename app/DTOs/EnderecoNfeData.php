<?php

namespace App\DTOs;

final readonly class EnderecoNfeData
{
    public function __construct(
        public string $cep,
        public string $logradouro,
        public ?string $numero,
        public ?string $observacao,
        public string $uf,
        public ?string $bairro,
        public string $cidade,
    ) {}
}
