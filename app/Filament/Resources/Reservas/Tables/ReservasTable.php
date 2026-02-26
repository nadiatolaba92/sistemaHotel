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
                    ->sortable(),
                TextColumn::make('habitacion.habitacion_numero')
                    ->label('Habitación')
                    ->sortable(),
                TextColumn::make('fecha_entrada')
                    ->date()
                    ->sortable(),
                TextColumn::make('fecha_salida')
                    ->date()
                    ->sortable(),
                TextColumn::make('numero_personas')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('estado'),
                TextColumn::make('tipo_pago'),
                TextColumn::make('total_pagado')
                    ->numeric()
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
