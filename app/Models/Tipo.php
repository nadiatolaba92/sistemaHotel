<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tipo extends Model
{
    protected $fillable = [
        'tipo_nombre',
        'capacidad_maxima',
        'precio_noche',
    ];

    // Aca van las relaciones
    // Esto me dice que un tipo_habitacion contiene o tiene muchas habitaciones
    public function habitaciones()
    {
        return $this->hasMany(Habitacione::class);
    }
}
