<?php

namespace Database\Factories;

use App\Models\OfertaProducto;
use Illuminate\Database\Eloquent\Factories\Factory;

class OfertaProductoFactory extends Factory
{
    protected $model = OfertaProducto::class;

    public function definition(): array
    {
        return [
            'precio_base' => $this->faker->randomFloat(2, 10, 500),
            'controla_stock' => true,
            'stock_disponible' => $this->faker->numberBetween(0, 200),
        ];
    }
}
