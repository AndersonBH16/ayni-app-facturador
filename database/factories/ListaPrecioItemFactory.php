<?php

namespace Database\Factories;

use App\Models\ListaPrecioItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class ListaPrecioItemFactory extends Factory
{
    protected $model = ListaPrecioItem::class;

    public function definition(): array
    {
        return [
            'precio' => $this->faker->randomFloat(2, 10, 400),
        ];
    }
}
