<?php

namespace App\Repositories;

use App\DTOs\EnderecoNfeData;
use App\Models\Endereco;

class EnderecoRepository
{
    public function firstOrCreateFromNfe(EnderecoNfeData $data): Endereco
    {
        return Endereco::firstOrCreate(
            [
                'cep' => $data->cep,
                'logradouro' => $data->logradouro,
                'casa' => $data->numero,
                'cidade' => $data->cidade,
                'uf' => $data->uf,
            ],
            [
                'observacao' => $data->observacao,
                'bairro' => $data->bairro,
            ],
        );
    }
}
