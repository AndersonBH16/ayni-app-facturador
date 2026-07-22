<?php

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClienteFactory extends Factory
{
    protected $model = Cliente::class;

    public function definition(): array
    {
        return [
            'tipo_cliente' => 'general',
            'tipo_documento' => '6',
            'ruc_dni' => $this->faker->unique()->numerify('20#########'),
            'razon_social' => $this->faker->company(),
            'email' => $this->faker->unique()->companyEmail(),
            'activo' => true,
        ];
    }
}
