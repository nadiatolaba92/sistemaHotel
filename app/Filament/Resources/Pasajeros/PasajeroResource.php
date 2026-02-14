<?php

namespace App\Filament\Resources\Pasajeros;

use App\Filament\Resources\Pasajeros\Pages\CreatePasajero;
use App\Filament\Resources\Pasajeros\Pages\EditPasajero;
use App\Filament\Resources\Pasajeros\Pages\ListPasajeros;
use App\Filament\Resources\Pasajeros\Schemas\PasajeroForm;
use App\Filament\Resources\Pasajeros\Tables\PasajerosTable;
use App\Models\Pasajero;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PasajeroResource extends Resource
{
    protected static ?string $model = Pasajero::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nombre';

    public static function form(Schema $schema): Schema
    {
        return PasajeroForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PasajerosTable::configure($table);
    }
     
    //esto es para colocar los relationManager
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPasajeros::route('/'),
            'create' => CreatePasajero::route('/create'),
            'edit' => EditPasajero::route('/{record}/edit'),
        ];
    }
}
