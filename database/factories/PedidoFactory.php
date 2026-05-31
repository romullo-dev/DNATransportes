<?php

namespace Database\Factories;

use App\Models\NotaFiscal;
use App\Models\Pedido;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pedido>
 */
class PedidoFactory extends Factory
{
    protected $model = Pedido::class;

    public function definition(): array
    {
        return [
            'codigo_rastreamento' => uniqid('dna_'),
            'id_notaFiscal' => NotaFiscal::factory(),
            'status' => $this->faker->randomElement(['Aguardando coleta', 'Em rota de entrega', 'Entrega realizada']),
            'sla_previsto_em' => $this->faker->dateTimeBetween('-2 months', '+10 days'),
            'peso' => $this->faker->randomFloat(3, 10, 2500),
            'volume' => $this->faker->randomFloat(3, 1, 80),
            'valor' => $this->faker->randomFloat(2, 120, 12000),
        ];
    }
}
