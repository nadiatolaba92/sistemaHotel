<?php

namespace App\Filament\Resources\Reservas\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;

class ReservaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Huésped y habitación')
                        ->description('Seleccioná quién se hospeda y en qué habitación se registrará la reserva.')
                        ->icon('heroicon-m-user-group')
                        ->schema([
                            Select::make('pasajero_id')
                                ->label('Pasajero')
                                ->relationship('pasajero', 'nombre')
                                ->getOptionLabelFromRecordUsing(fn ($record): string => trim("{$record->apellido}, {$record->nombre} - DNI {$record->dni}"))
                                ->searchable(['nombre', 'apellido', 'dni'])
                                ->preload()
                                ->native(false)
                                ->placeholder('Buscá un pasajero por nombre, apellido o DNI')
                                ->createOptionForm([
                                    TextInput::make('nombre')
                                        ->required()
                                        ->maxLength(255),
                                    TextInput::make('apellido')
                                        ->required()
                                        ->maxLength(255),
                                    TextInput::make('dni')
                                        ->required()
                                        ->maxLength(255),
                                    TextInput::make('telefono')
                                        ->tel()
                                        ->required()
                                        ->maxLength(255),
                                    TextInput::make('email')
                                        ->email()
                                        ->required()
                                        ->maxLength(255),
                                ])
                                ->required(),

                            Select::make('habitacion_id')
                                ->label('Habitación')
                                ->relationship('habitacion', 'habitacion_numero')
                                ->searchable(['habitacion_numero', 'estado'])
                                ->preload()
                                ->native(false)
                                ->helperText('Elegí una habitación disponible o lista para asignar.')
                                ->required(),
                        ])
                        ->columns(2),

                    Step::make('Estadía')
                        ->description('Definí las fechas de la reserva y la cantidad de personas.')
                        ->icon('heroicon-m-calendar-days')
                        ->schema([
                            DatePicker::make('fecha_entrada')
                                ->label('Fecha de entrada')
                                ->native(false)
                                ->minDate(now())
                                ->required(),

                            DatePicker::make('fecha_salida')
                                ->label('Fecha de salida')
                                ->native(false)
                                ->rule('after_or_equal:fecha_entrada')
                                ->helperText('La salida no puede ser anterior a la fecha de entrada.')
                                ->required(),

                            TextInput::make('numero_personas')
                                ->label('Cantidad de personas')
                                ->numeric()
                                ->default(1)
                                ->minValue(1)
                                ->inputMode('numeric')
                                ->required(),
                        ])
                        ->columns(3),

                    Step::make('Pago y estado')
                        ->description('Completá la situación de la reserva y la información de cobro.')
                        ->icon('heroicon-m-credit-card')
                        ->schema([
                            Select::make('estado')
                                ->label('Estado de la reserva')
                                ->options([
                                    'Pendiente' => 'Pendiente',
                                    'Confirmada' => 'Confirmada',
                                    'Cancelada' => 'Cancelada',
                                    'Completada' => 'Completada',
                                ])
                                ->default('Pendiente')
                                ->native(false)
                                ->required(),

                            Select::make('tipo_pago')
                                ->label('Tipo de pago')
                                ->options([
                                    'Efectivo' => 'Efectivo',
                                    'Tarjeta' => 'Tarjeta',
                                    'Transferencia' => 'Transferencia',
                                ])
                                ->native(false)
                                ->required(),

                            TextInput::make('total_pagado')
                                ->label('Total pagado')
                                ->numeric()
                                ->prefix('$')
                                ->minValue(0)
                                ->inputMode('decimal')
                                ->placeholder('0.00')
                                ->required(),
                        ])
                        ->columns(3),
                ])
                    ->columnSpanFull(),
            ]);
    }
}
