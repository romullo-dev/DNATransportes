<?php

namespace Database\Factories;

use App\Models\CentroDistribuicao;
use App\Models\Motorista;
use App\Models\Rota;
use App\Models\Veiculo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Rota>
 */
class RotaFactory extends Factory
{
    protected $model = Rota::class;

    public function definition(): array
    {
        $origem = CentroDistribuicao::query()->inRandomOrder()->value('id_centro_distribuicao');
        $destino = CentroDistribuicao::query()->where('id_centro_distribuicao', '<>', $origem)->inRandomOrder()->value('id_centro_distribuicao') ?? $origem;

        return [
            'id_motorista' => Motorista::query()->inRandomOrder()->value('id_motorista') ?? Motorista::factory(),
            'id_veiculo' => Veiculo::query()->inRandomOrder()->value('id_Veiculo') ?? Veiculo::factory(),
            'tipo' => $this->faker->randomElement(['coleta', 'transferencia', 'entrega']),
            'distancia' => $this->faker->randomFloat(2, 8, 900),
            'previsao' => $this->faker->dateTimeBetween('-2 months', '+10 days'),
            'data_rota' => $this->faker->dateTimeBetween('-4 months', 'now'),
            'data_inicio' => $this->faker->dateTimeBetween('-4 months', 'now'),
            'data_criacao' => now(),
            'status' => $this->faker->randomElement(['Planejada', 'Em andamento', 'Finalizada']),
            'observacoes' => null,
            'id_origem' => $origem,
            'id_destino' => $destino,
        ];
    }
}
