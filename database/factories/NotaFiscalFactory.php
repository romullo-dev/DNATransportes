<?php

namespace Database\Factories;

use App\Models\Cliente;
use App\Models\Endereco;
use App\Models\NotaFiscal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<NotaFiscal>
 */
class NotaFiscalFactory extends Factory
{
    protected $model = NotaFiscal::class;

    public function definition(): array
    {
        return [
            'chave_acesso' => $this->faker->unique()->numerify(str_repeat('#', 44)),
            'numero_nfe' => $this->faker->unique()->numberBetween(1000, 999999),
            'serie' => $this->faker->numberBetween(1, 5),
            'emissao' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'valor_total' => $this->faker->randomFloat(2, 400, 18000),
            'peso' => $this->faker->randomFloat(3, 10, 2500),
            'quantidade_volumes' => $this->faker->numberBetween(1, 30),
            'cliente_remetente' => Cliente::factory(),
            'cliente_destinatario' => Cliente::factory(),
            'endereco_remetente' => Endereco::factory(),
            'endereco_destinatario' => Endereco::factory(),
        ];
    }
}
