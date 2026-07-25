<?php

namespace Database\Factories;

use App\Models\MarketUsuario;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class MarketUsuarioFactory extends Factory
{
    protected $model = MarketUsuario::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'activo' => true,
        ];
    }
}
