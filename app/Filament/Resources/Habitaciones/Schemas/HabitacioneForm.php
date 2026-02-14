<?php

namespace App\Filament\Resources\Habitaciones\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class HabitacioneForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('habitacion_numero')
                    ->required(),
                Select::make('tipo_id')
                    ->relationship('tipo', 'tipo_nombre')
                    ->preload()
                    ->searchable()
                    ->label('Tipo de Habitación')
                    ->required(),
                TextInput::make('estado')
                    ->required()
                    ->default('disponible'),
                Textarea::make('descripcion')
                    ->columnSpanFull(),
            ]);
    }
}
