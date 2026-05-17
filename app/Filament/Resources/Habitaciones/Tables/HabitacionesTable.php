<?php

namespace App\Filament\Resources\Habitaciones\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;

class HabitacionesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('imagen')
                    ->label('Imagen')
                    ->collection('habitaciones')
                    ->circular(),

                TextColumn::make('habitacion_numero')
                    ->label('Número')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('tipo.tipo_nombre')
                    ->label('Tipo de Habitación')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Disponible' => 'success',
                        'Ocupada' => 'danger',
                        'Mantenimiento' => 'warning',
                        default => 'gray',
                    }),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
