<?php

namespace Database\Factories;

use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductoFactory extends Factory
{
    protected $model = Producto::class;

    public function definition(): array
    {
        return [
            'sku' => $this->faker->unique()->bothify('SKU-####'),
            'nombre' => $this->faker->words(3, true),
            'descripcion' => $this->faker->sentence(),
            'unidad_medida' => 'NIU',
            'activo' => true,
        ];
    }
}
