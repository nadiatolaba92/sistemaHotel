<?php

namespace Database\Factories;

use App\Models\Habitacione;
use App\Models\Tipo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reserva>
 */
class ReservaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pasajero_id' => PasajeroFactory::new(),
            'habitacion_id' => Habitacione::factory(),
            'tipo_solicitado_id' => Tipo::factory(),
            'fecha_entrada' => now()->addDay()->toDateString(),
            'fecha_salida' => now()->addDays(2)->toDateString(),
            'numero_personas' => 1,
            'estado' => 'Pendiente',
            'tipo_pago' => 'Efectivo',
            'total_pagado' => 0,
        ];
    }

    public function cancelada(): static
    {
        return $this->state(fn (): array => [
            'estado' => 'Cancelada',
        ]);
    }
}
