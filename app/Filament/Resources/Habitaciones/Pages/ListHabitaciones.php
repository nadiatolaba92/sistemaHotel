<?php

namespace App\Filament\Resources\Habitaciones\Pages;

use App\Filament\Resources\Habitaciones\HabitacioneResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHabitaciones extends ListRecords
{
    protected static string $resource = HabitacioneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
