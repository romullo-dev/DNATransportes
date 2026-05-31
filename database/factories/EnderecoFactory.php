<?php

namespace Database\Factories;

use App\Models\Endereco;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Endereco>
 */
class EnderecoFactory extends Factory
{
    protected $model = Endereco::class;

    public function definition(): array
    {
        return [
            'cep' => $this->faker->numerify('########'),
            'logradouro' => $this->faker->streetName(),
            'casa' => (string) $this->faker->buildingNumber(),
            'observacao' => null,
            'uf' => $this->faker->randomElement(['SP', 'RJ', 'MG', 'PR', 'SC']),
            'bairro' => $this->faker->citySuffix(),
            'cidade' => $this->faker->city(),
        ];
    }
}
