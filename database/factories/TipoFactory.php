<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tipo>
 */
class TipoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tipo_nombre' => fake()->unique()->words(2, true),
            'capacidad_maxima' => fake()->numberBetween(1, 6),
            'precio_noche' => fake()->randomFloat(2, 20000, 250000),
        ];
    }
}
