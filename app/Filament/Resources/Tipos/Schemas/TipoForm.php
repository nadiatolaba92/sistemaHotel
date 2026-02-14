<?php

namespace App\Filament\Resources\Tipos\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TipoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('tipo_nombre')
                    ->required(),
                TextInput::make('capacidad_maxima')
                    ->required()
                    ->numeric(),
                TextInput::make('precio_noche')
                    ->required()
                    ->numeric(),
            ]);
    }
}
