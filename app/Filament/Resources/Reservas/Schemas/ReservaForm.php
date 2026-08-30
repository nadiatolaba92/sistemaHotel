<?php

namespace App\Filament\Resources\Reservas\Schemas;

use App\Models\Habitacione;
use App\Models\Reserva;
use App\Models\Tipo;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class ReservaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Estadía')
                        ->description('Definí las fechas de la reserva y la cantidad de personas.')
                        ->icon('heroicon-m-calendar-days')
                        ->schema([
                            DatePicker::make('fecha_entrada')
                                ->label('Fecha de entrada')
                                ->native(false)
                                ->minDate(now())
                                ->live()
                                ->required(),

                            DatePicker::make('fecha_salida')
                                ->label('Fecha de salida')
                                ->native(false)
                                ->rule('after:fecha_entrada')
                                ->helperText('La salida debe ser posterior a la fecha de entrada.')
                                ->live()
                                ->required(),

                            TextInput::make('numero_personas')
                                ->label('Cantidad de personas')
                                ->numeric()
                                ->default(1)
                                ->minValue(1)
                                ->inputMode('numeric')
                                ->live()
                                ->required(),
                        ])
                        ->columns(3),

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
                                ->required()
                                ->columnSpan(2),

                            Select::make('tipo_solicitado_id')
                                ->label('Tipo de Habitación (Vendido)')
                                ->options(function (Get $get, ?Reserva $record) {
                                    $entrada = $get('fecha_entrada');
                                    $salida = $get('fecha_salida');
                                    $personas = $get('numero_personas');

                                    if (! $entrada || ! $salida || ! $personas) {
                                        return [];
                                    }

                                    $tipos = Tipo::where('capacidad_maxima', '>=', $personas)
                                        ->whereHas('habitaciones', function (Builder $query) use ($entrada, $salida, $record) {
                                            $query->disponibles($entrada, $salida, $record?->getKey());
                                        })
                                        ->orderBy('capacidad_maxima', 'asc')
                                        ->get();

                                    return $tipos->mapWithKeys(function ($tipo) {
                                        return [$tipo->id => $tipo->tipo_nombre.' (Cap. '.$tipo->capacidad_maxima.')'];
                                    });
                                })
                                ->live()
                                ->helperText('Selecciona la categoría vendida al cliente.')
                                ->required()
                                ->columnSpan(1),

                            Select::make('habitacion_id')
                                ->label('Habitación Asignada (Física)')
                                ->options(function (Get $get, ?Reserva $record) {
                                    $entrada = $get('fecha_entrada');
                                    $salida = $get('fecha_salida');
                                    $tipoSeleccionado = $get('tipo_solicitado_id');
                                    $personas = $get('numero_personas');

                                    if (! $entrada || ! $salida || ! $personas) {
                                        return [];
                                    }

                                    $habitaciones = Habitacione::disponibles($entrada, $salida, $record?->getKey())
                                        ->whereHas('tipo', function (Builder $q) use ($personas) {
                                            $q->where('capacidad_maxima', '>=', $personas);
                                        })
                                        ->with('tipo')
                                        ->get();

                                    $habitaciones = $habitaciones->sortBy(function ($hab) use ($tipoSeleccionado) {
                                        return $hab->tipo_id == $tipoSeleccionado ? 0 : $hab->tipo->capacidad_maxima;
                                    });

                                    return $habitaciones->mapWithKeys(function ($hab) use ($tipoSeleccionado) {
                                        $extra = ($hab->tipo_id == $tipoSeleccionado) ? '' : ' (Upgrade: '.$hab->tipo->tipo_nombre.')';

                                        return [$hab->id => 'Habitación Nº '.$hab->habitacion_numero.$extra];
                                    });
                                })
                                ->required()
                                ->helperText('Elige la unidad física. Puede ser un Upgrade si se agotó el tipo vendido.')
                                ->columnSpan(1),
                        ])
                        ->columns(2),

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
