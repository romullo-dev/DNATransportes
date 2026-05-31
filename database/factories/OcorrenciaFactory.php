<?php

namespace Database\Factories;

use App\Models\Ocorrencia;
use App\Models\Pedido;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ocorrencia>
 */
class OcorrenciaFactory extends Factory
{
    protected $model = Ocorrencia::class;

    public function definition(): array
    {
        return [
            'id_pedido' => Pedido::factory(),
            'id_rotas' => null,
            'id_historico' => null,
            'tipo' => $this->faker->randomElement(['atraso', 'avaria', 'extravio', 'reentrega']),
            'status' => $this->faker->randomElement(['Aberta', 'Em tratativa', 'Resolvida']),
            'descricao' => $this->faker->sentence(),
            'resolvida_em' => null,
        ];
    }
}
