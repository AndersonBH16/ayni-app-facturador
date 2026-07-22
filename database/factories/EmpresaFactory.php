<?php

namespace Database\Factories;

use App\Models\Empresa;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmpresaFactory extends Factory
{
    protected $model = Empresa::class;

    public function definition(): array
    {
        return [
            'ruc' => $this->faker->numerify('20#########'),
            'razon_social' => $this->faker->company().' S.A.C.',
            'nombre_comercial' => $this->faker->companySuffix(),
            'activo' => true,
        ];
    }
}
