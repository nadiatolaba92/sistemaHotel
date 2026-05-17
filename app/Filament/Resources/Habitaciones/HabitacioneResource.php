<?php

namespace App\Filament\Resources\Habitaciones;

use App\Filament\Resources\Habitaciones\Pages\CreateHabitacione;
use App\Filament\Resources\Habitaciones\Pages\EditHabitacione;
use App\Filament\Resources\Habitaciones\Pages\ListHabitaciones;
use App\Filament\Resources\Habitaciones\Schemas\HabitacioneForm;
use App\Filament\Resources\Habitaciones\Tables\HabitacionesTable;
use App\Models\Habitacione;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class HabitacioneResource extends Resource
{
    protected static ?string $model = Habitacione::class;
    protected static string|UnitEnum|null $navigationGroup = 'Gestion hotelera';
    protected static ?string $navigationLabel = 'Habitaciones';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'habitacion_numero';

    public static function form(Schema $schema): Schema
    {
        return HabitacioneForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HabitacionesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHabitaciones::route('/'),
            'create' => CreateHabitacione::route('/create'),
            'edit' => EditHabitacione::route('/{record}/edit'),
        ];
    }
}
