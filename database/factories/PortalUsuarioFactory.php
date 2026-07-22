<?php

namespace Database\Factories;

use App\Models\PortalUsuario;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class PortalUsuarioFactory extends Factory
{
    protected $model = PortalUsuario::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'activo' => true,W
        ];
    }
}
