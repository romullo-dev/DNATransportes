<?php

namespace Database\Factories;

use App\Models\Motorista;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<Motorista>
 */
class MotoristaFactory extends Factory
{
    protected $model = Motorista::class;

    public function definition(): array
    {
        $usuario = Usuario::create([
            'nome' => $this->faker->name(),
            'user' => $this->faker->unique()->userName(),
            'password' => Hash::make('password'),
            'tipo_usuario' => 'motorista',
            'cpf' => $this->faker->unique()->numerify('###########'),
            'status_funcionario' => 'Ativo',
            'email' => $this->faker->unique()->safeEmail(),
            'telefone' => $this->faker->numerify('########'),
        ]);

        return [
            'cnh' => $this->faker->unique()->numerify('###########'),
            'categoria' => $this->faker->randomElement(['B', 'C', 'D', 'E']),
            'validade_cnh' => $this->faker->dateTimeBetween('+1 year', '+5 years'),
            'id_Usuario' => $usuario->id_usuario,
        ];
    }
}
