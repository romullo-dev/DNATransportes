<?php

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cliente>
 */
class ClienteFactory extends Factory
{
    protected $model = Cliente::class;

    public function definition(): array
    {
        return [
            'nome' => $this->faker->company(),
            'documento' => $this->faker->unique()->numerify('##############'),
            'tipo' => $this->faker->randomElement(['emitente', 'destinatário']),
        ];
    }
}
