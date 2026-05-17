<?php

namespace Database\Seeders;

use App\Models\Tipo;
use Illuminate\Database\Seeder;

class TipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usamos updateOrCreate para evitar duplicados si el seeder se ejecuta varias veces.
        $tipos = [
            ['tipo_nombre' => 'Single (Individual)', 'capacidad_maxima' => 1, 'precio_noche' => 50.00],
            ['tipo_nombre' => 'Doble Twin', 'capacidad_maxima' => 2, 'precio_noche' => 75.00],
            ['tipo_nombre' => 'Doble Matrimonial', 'capacidad_maxima' => 2, 'precio_noche' => 80.00],
            ['tipo_nombre' => 'Triple', 'capacidad_maxima' => 3, 'precio_noche' => 100.00],
            ['tipo_nombre' => 'Cuádruple', 'capacidad_maxima' => 4, 'precio_noche' => 120.00],
            ['tipo_nombre' => 'Quíntuple', 'capacidad_maxima' => 5, 'precio_noche' => 140.00],
        ];

        foreach ($tipos as $tipo) {
            Tipo::updateOrCreate(['tipo_nombre' => $tipo['tipo_nombre']], $tipo);
        }
    }
}