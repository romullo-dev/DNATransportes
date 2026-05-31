<?php

namespace App\Repositories;

use App\DTOs\ProdutoNfeData;
use App\Models\Produto;
use Illuminate\Support\Collection;

class ProdutoRepository
{
    /**
     * @param  array<int, ProdutoNfeData>  $produtos
     */
    public function firstOrCreateMany(array $produtos): Collection
    {
        return collect($produtos)
            ->filter(fn (ProdutoNfeData $produto) => $produto->nome !== '')
            ->map(fn (ProdutoNfeData $produto) => Produto::firstOrCreate([
                'nome' => $produto->nome,
            ]))
            ->values();
    }
}
