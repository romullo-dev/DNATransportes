<?php

namespace App\DTOs;

final readonly class ImportacaoNfeData
{
    /**
     * @param  array<int, ProdutoNfeData>  $produtos
     */
    public function __construct(
        public ClienteNfeData $emitente,
        public EnderecoNfeData $enderecoEmitente,
        public ClienteNfeData $destinatario,
        public EnderecoNfeData $enderecoDestinatario,
        public NotaFiscalNfeData $notaFiscal,
        public array $produtos,
    ) {}
}
