<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Habitacione extends Model
{


    protected $table = 'habitaciones';
    protected $fillable = [
        'habitacion_numero',
        'tipo_id',
        'estado',
        'descripcion',
    ];

    public function tipo()
    {
        return $this->belongsTo(Tipo::class);
    }



    public function pasajeros()
    {
        return $this->belongsToMany(Pasajero::class, 'reservas', 'habitacion_id', 'pasajero_id')
            ->withPivot('id', 'fecha_entrada', 'fecha_salida', 'numero_personas', 'estado', 'tipo_pago', 'total_pagado')
            ->withTimestamps();
    }
}
