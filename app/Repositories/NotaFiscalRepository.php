<?php

namespace App\Repositories;

use App\DTOs\NotaFiscalNfeData;
use App\Models\Cliente;
use App\Models\Endereco;
use App\Models\NotaFiscal;

class NotaFiscalRepository
{
    public function firstOrCreateFromNfe(
        NotaFiscalNfeData $data,
        Cliente $remetente,
        Cliente $destinatario,
        Endereco $enderecoRemetente,
        Endereco $enderecoDestinatario,
    ): NotaFiscal {
        return NotaFiscal::firstOrCreate(
            ['chave_acesso' => $data->chaveAcesso],
            [
                'numero_nfe' => $data->numero,
                'serie' => $data->serie,
                'emissao' => $data->emissao,
                'valor_total' => $data->valorTotal,
                'peso' => $data->peso,
                'quantidade_volumes' => $data->quantidadeVolumes,
                'cliente_remetente' => $remetente->id_cliente,
                'cliente_destinatario' => $destinatario->id_cliente,
                'endereco_remetente' => $enderecoRemetente->id_endereco,
                'endereco_destinatario' => $enderecoDestinatario->id_endereco,
            ],
        );
    }
}
