<?php

namespace Database\Factories;

use App\Models\Rubro;
use Illuminate\Database\Eloquent\Factories\Factory;

class RubroFactory extends Factory
{
    protected $model = Rubro::class;

    public function definition(): array
    {
        return [
            'nombre' => $this->faker->unique()->word(),
        ];
    }
}
