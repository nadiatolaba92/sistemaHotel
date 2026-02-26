<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $fillable = [
        'pasajero_id',
        'habitacion_id',
        'fecha_entrada',
        'fecha_salida',
        'numero_personas',
        'estado',
        'tipo_pago',
        'total_pagado',
    ];

    public function pasajero()
    {
        return $this->belongsTo(Pasajero::class);
    }

    public function habitacion()
    {
        return $this->belongsTo(Habitacione::class, 'habitacion_id');
    }
}
