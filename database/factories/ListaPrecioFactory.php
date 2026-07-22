<?php

namespace Database\Factories;

use App\Models\ListaPrecio;
use Illuminate\Database\Eloquent\Factories\Factory;

class ListaPrecioFactory extends Factory
{
    protected $model = ListaPrecio::class;

    public function definition(): array
    {
        return [
            'nombre' => $this->faker->unique()->words(2, true),
            'activo' => true,
        ];
    }
}
