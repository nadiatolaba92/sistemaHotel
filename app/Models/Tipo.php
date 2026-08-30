<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tipo extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo_nombre',
        'capacidad_maxima',
        'precio_noche',
    ];

    // Aca van las relaciones
    // Esto me dice que un tipo_habitacion contiene o tiene muchas habitaciones
    public function habitaciones(): HasMany
    {
        return $this->hasMany(Habitacione::class);
    }
}
