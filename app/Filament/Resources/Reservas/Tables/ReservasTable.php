<?php

namespace App\Filament\Resources\Reservas\Tables;






use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ReservasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('pasajero.nombre')
                    ->label('Pasajero')
                    ->description(fn ($record): ?string => $record->pasajero ? "{$record->pasajero->apellido} - DNI {$record->pasajero->dni}" : null)
                    ->searchable(['pasajero.nombre', 'pasajero.apellido', 'pasajero.dni'])
                    ->sortable(),
                TextColumn::make('habitacion.habitacion_numero')
                    ->label('Habitación')
                    ->badge()
                    ->description(fn ($record): ?string => $record->habitacion?->estado)
                    ->sortable(),
                TextColumn::make('fecha_entrada')
                    ->label('Check-in')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('fecha_salida')
                    ->label('Check-out')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('numero_personas')
                    ->label('Huéspedes')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pendiente' => 'warning',
                        'Confirmada' => 'success',
                        'Cancelada' => 'danger',
                        'Completada' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('tipo_pago')
                    ->label('Pago')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Efectivo' => 'success',
                        'Tarjeta' => 'info',
                        'Transferencia' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('total_pagado')
                    ->label('Importe')
                    ->money('ARS', locale: 'es_AR')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
