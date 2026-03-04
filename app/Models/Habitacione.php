<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Habitacione extends Model implements HasMedia
{

    use InteractsWithMedia;
    protected $table = 'habitaciones';
    //campos que se pueden llenar, propios del  modelo
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
