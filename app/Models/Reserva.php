<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reserva extends Model
{
    use HasFactory;

    protected $fillable = [
        'pasajero_id',
        'habitacion_id',
        'tipo_solicitado_id',
        'fecha_entrada',
        'fecha_salida',
        'numero_personas',
        'estado',
        'tipo_pago',
        'total_pagado',
    ];

    public function pasajero(): BelongsTo
    {
        return $this->belongsTo(Pasajero::class);
    }

    public function habitacion(): BelongsTo
    {
        return $this->belongsTo(Habitacione::class, 'habitacion_id');
    }

    public function tipoSolicitado(): BelongsTo
    {
        return $this->belongsTo(Tipo::class, 'tipo_solicitado_id');
    }

    public function scopeBloqueantes(Builder $query): Builder
    {
        return $query->where('estado', '!=', 'Cancelada');
    }

    public function scopeSolapadasCon(
        Builder $query,
        DateTimeInterface|string $fechaEntrada,
        DateTimeInterface|string $fechaSalida,
    ): Builder {
        $fechaEntrada = CarbonImmutable::parse($fechaEntrada)->startOfDay();
        $fechaSalida = CarbonImmutable::parse($fechaSalida)->startOfDay();

        return $query
            ->where('fecha_entrada', '<', $fechaSalida)
            ->where('fecha_salida', '>', $fechaEntrada);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'fecha_entrada' => 'date',
            'fecha_salida' => 'date',
            'numero_personas' => 'integer',
            'total_pagado' => 'decimal:2',
        ];
    }
}
