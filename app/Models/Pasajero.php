<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasajero extends Model
{
    protected $fillable = [
        'nombre',
        'apellido',
        'dni',
        'telefono',
        'email',
    ];

    public function habitaciones()
    {
        return $this->belongsToMany(Habitacione::class, 'reservas', 'pasajero_id', 'habitacion_id')
            ->withPivot('id', 'fecha_entrada', 'fecha_salida', 'numero_personas', 'estado', 'tipo_pago', 'total_pagado')
            ->withTimestamps();
    }
}
