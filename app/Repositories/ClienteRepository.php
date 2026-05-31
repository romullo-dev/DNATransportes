<?php

namespace App\Repositories;

use App\DTOs\ClienteNfeData;
use App\Models\Cliente;

class ClienteRepository
{
    public function firstOrCreateFromNfe(ClienteNfeData $data): Cliente
    {
        return Cliente::firstOrCreate(
            ['documento' => $data->documento],
            [
                'nome' => $data->nome,
                'tipo' => $data->tipo,
            ],
        );
    }
}
