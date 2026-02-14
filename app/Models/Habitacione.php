<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Habitacione extends Model
{
    protected $fillable=[
        'habitacion_numero',
        'tipo_id',
        'estado',
        'descripcion',
    ];

    public function tipo()
    {
        return $this->belongsTo(Tipo::class);
    }

}
