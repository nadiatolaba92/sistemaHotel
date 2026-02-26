<?php

namespace App\Filament\Resources\Reservas\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ReservaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('pasajero_id')
                    ->relationship('pasajero', 'nombre')
                    ->required(),
                Select::make('habitacion_id')
                    ->relationship('habitacion', 'habitacion_numero')
                    ->required(),
                DatePicker::make('fecha_entrada')
                    ->required(),
                DatePicker::make('fecha_salida')
                    ->required(),
                TextInput::make('numero_personas')
                    ->required()
                    ->numeric(),
                Select::make('estado')
                    ->options([
                        'Pendiente' => 'Pendiente',
                        'Confirmada' => 'Confirmada',
                        'Cancelada' => 'Cancelada',
                        'Completada' => 'Completada',
                    ])
                    ->required(),
                Select::make('tipo_pago')
                    ->options(['Efectivo' => 'Efectivo', 'Tarjeta' => 'Tarjeta', 'Transferencia' => 'Transferencia'])
                    ->required(),
                TextInput::make('total_pagado')
                    ->required()
                    ->numeric(),
            ]);
    }
}
