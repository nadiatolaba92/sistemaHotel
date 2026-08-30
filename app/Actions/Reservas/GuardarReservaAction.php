<?php

namespace App\Actions\Reservas;

use App\Models\Habitacione;
use App\Models\Reserva;
use App\Models\Tipo;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class GuardarReservaAction
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function execute(array $attributes, ?Reserva $reserva = null): Reserva
    {
        $validated = Validator::make($attributes, [
            'pasajero_id' => ['required', 'integer', 'exists:pasajeros,id'],
            'habitacion_id' => ['required', 'integer', 'exists:habitaciones,id'],
            'tipo_solicitado_id' => ['required', 'integer', 'exists:tipos,id'],
            'fecha_entrada' => ['required', 'date'],
            'fecha_salida' => ['required', 'date', 'after:fecha_entrada'],
            'numero_personas' => ['required', 'integer', 'min:1'],
            'estado' => ['required', Rule::in(['Pendiente', 'Confirmada', 'Cancelada', 'Completada'])],
            'tipo_pago' => ['required', Rule::in(['Efectivo', 'Tarjeta', 'Transferencia'])],
            'total_pagado' => ['required', 'numeric', 'min:0'],
        ], [
            'fecha_salida.after' => 'La fecha de salida debe ser posterior a la fecha de entrada.',
        ])->validate();

        try {
            return Cache::lock('reservas:habitacion:'.$validated['habitacion_id'], 15)
                ->block(5, fn (): Reserva => $this->persistir($validated, $reserva));
        } catch (LockTimeoutException) {
            throw ValidationException::withMessages([
                'habitacion_id' => 'La habitación está siendo reservada por otro usuario. Intentá nuevamente.',
            ]);
        }
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function persistir(array $attributes, ?Reserva $reserva): Reserva
    {
        return DB::transaction(function () use ($attributes, $reserva): Reserva {
            $habitacionIds = collect([
                $reserva?->habitacion_id,
                $attributes['habitacion_id'],
            ])->filter()->unique()->sort()->values();

            $habitaciones = Habitacione::query()
                ->whereKey($habitacionIds)
                ->with('tipo')
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            /** @var Habitacione|null $habitacion */
            $habitacion = $habitaciones->get((int) $attributes['habitacion_id']);

            if (! $habitacion) {
                throw ValidationException::withMessages([
                    'habitacion_id' => 'La habitación seleccionada ya no existe.',
                ]);
            }

            $tipoSolicitado = Tipo::query()
                ->lockForUpdate()
                ->find($attributes['tipo_solicitado_id']);

            if (! $tipoSolicitado) {
                throw ValidationException::withMessages([
                    'tipo_solicitado_id' => 'El tipo de habitación seleccionado ya no existe.',
                ]);
            }

            if ($this->requiereValidarInventario($attributes, $reserva)) {
                $this->validarInventario($attributes, $habitacion, $tipoSolicitado, $reserva);
            }

            if ($reserva) {
                $reserva->fill($attributes);
                $reserva->save();

                return $reserva->refresh();
            }

            return Reserva::query()->create($attributes);
        }, attempts: 3);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function requiereValidarInventario(array $attributes, ?Reserva $reserva): bool
    {
        if ($attributes['estado'] === 'Cancelada') {
            return false;
        }

        if (! $reserva || $reserva->estado === 'Cancelada') {
            return true;
        }

        return (int) $reserva->habitacion_id !== (int) $attributes['habitacion_id']
            || (int) $reserva->tipo_solicitado_id !== (int) $attributes['tipo_solicitado_id']
            || $reserva->fecha_entrada->toDateString() !== $attributes['fecha_entrada']
            || $reserva->fecha_salida->toDateString() !== $attributes['fecha_salida']
            || (int) $reserva->numero_personas !== (int) $attributes['numero_personas'];
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function validarInventario(
        array $attributes,
        Habitacione $habitacion,
        Tipo $tipoSolicitado,
        ?Reserva $reserva,
    ): void {
        if ($habitacion->estado === 'Mantenimiento') {
            throw ValidationException::withMessages([
                'habitacion_id' => 'La habitación seleccionada está en mantenimiento.',
            ]);
        }

        if ($tipoSolicitado->capacidad_maxima < $attributes['numero_personas']) {
            throw ValidationException::withMessages([
                'tipo_solicitado_id' => 'El tipo vendido no admite la cantidad de huéspedes indicada.',
            ]);
        }

        if ($habitacion->tipo->capacidad_maxima < $attributes['numero_personas']) {
            throw ValidationException::withMessages([
                'habitacion_id' => 'La habitación seleccionada no admite la cantidad de huéspedes indicada.',
            ]);
        }

        $existeSolapamiento = Reserva::query()
            ->whereBelongsTo($habitacion, 'habitacion')
            ->bloqueantes()
            ->solapadasCon($attributes['fecha_entrada'], $attributes['fecha_salida'])
            ->when($reserva, fn ($query) => $query->whereKeyNot($reserva->getKey()))
            ->exists();

        if ($existeSolapamiento) {
            throw ValidationException::withMessages([
                'habitacion_id' => 'La habitación ya posee una reserva que se superpone con las fechas indicadas.',
            ]);
        }
    }
}
