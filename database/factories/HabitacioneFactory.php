<?php

namespace Database\Factories;

use App\Models\Tipo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Habitacione>
 */
class HabitacioneFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'habitacion_numero' => fake()->unique()->numerify('###'),
            'tipo_id' => Tipo::factory(),
            'estado' => 'Disponible',
            'descripcion' => fake()->sentence(),
        ];
    }

    public function mantenimiento(): static
    {
        return $this->state(fn (): array => [
            'estado' => 'Mantenimiento',
        ]);
    }
}
