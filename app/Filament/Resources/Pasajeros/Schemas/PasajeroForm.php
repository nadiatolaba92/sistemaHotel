<?php

namespace App\Filament\Resources\Pasajeros\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PasajeroForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                        ->label('Nombre')
                    ->required(),
                TextInput::make('apellido')
                        ->label('Apellido')
                    ->required(),
                TextInput::make('dni')
                        ->label('DNI')
                    ->required(),
                TextInput::make('telefono')
                    ->tel()
                    ->required(),
                TextInput::make('email')
                    ->label('Direccion de Correo')
                    ->email()
                    ->required(),
            ]);
    }
}
