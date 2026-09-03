<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Habitacione extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'habitaciones';

    // campos que se pueden llenar, propios del  modelo
    protected $fillable = [
        'habitacion_numero',
        'tipo_id',
        'estado',
        'descripcion',
    ];

    public function tipo(): BelongsTo
    {
        return $this->belongsTo(Tipo::class);
    }

    public function pasajeros(): BelongsToMany
    {
        return $this->belongsToMany(Pasajero::class, 'reservas', 'habitacion_id', 'pasajero_id')
            ->withPivot('id', 'fecha_entrada', 'fecha_salida', 'numero_personas', 'estado', 'tipo_pago', 'total_pagado')
            ->withTimestamps();
    }

    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class, 'habitacion_id');
    }

    public function scopeDisponibles(
        Builder $query,
        DateTimeInterface|string|null $fechaEntrada,
        DateTimeInterface|string|null $fechaSalida,
        ?int $reservaExceptuadaId = null,
    ): Builder {
        $query->where('estado', '!=', 'Mantenimiento');

        if (! $fechaEntrada || ! $fechaSalida) {
            return $query;
        }

        return $query->whereDoesntHave('reservas', function (Builder $query) use ($fechaEntrada, $fechaSalida, $reservaExceptuadaId) {
            $query
                ->bloqueantes()
                ->solapadasCon($fechaEntrada, $fechaSalida)
                ->when($reservaExceptuadaId, fn (Builder $query) => $query->whereKeyNot($reservaExceptuadaId));
        });
    }
}
