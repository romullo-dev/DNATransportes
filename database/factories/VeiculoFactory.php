<?php

namespace Database\Factories;

use App\Models\ModeloVeiculo;
use App\Models\Veiculo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Veiculo>
 */
class VeiculoFactory extends Factory
{
    protected $model = Veiculo::class;

    public function definition(): array
    {
        $modeloId = ModeloVeiculo::query()->inRandomOrder()->value('id_modelo_veiculo')
            ?? ModeloVeiculo::create([
                'marca' => 'Mercedes-Benz',
                'modelo' => 'Atego',
                'categoria' => 'Truck',
                'descricao' => 'Modelo base para simulações analíticas',
                'status' => 'Ativo',
            ])->id_modelo_veiculo;

        return [
            'placa' => strtoupper($this->faker->bothify('???#?##')),
            'ano' => $this->faker->numberBetween(2017, 2026),
            'cor' => $this->faker->safeColorName(),
            'status_veiculo' => 'Ativo',
            'observacoes' => null,
            'id_modelo_veiculo' => $modeloId,
            'renavam' => $this->faker->unique()->numerify('###########'),
            'chassi' => strtoupper($this->faker->unique()->bothify('#################')),
            'tara_kg' => $this->faker->numberBetween(3500, 8000),
            'pbt_kg' => $this->faker->numberBetween(9000, 23000),
        ];
    }
}
