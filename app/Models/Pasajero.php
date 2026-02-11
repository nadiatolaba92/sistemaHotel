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

// public function reservas()
//{
//return $this->hasMany(Reserva::class);
//}
}
