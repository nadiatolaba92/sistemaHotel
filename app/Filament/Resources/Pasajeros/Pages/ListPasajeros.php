<?php

namespace App\Filament\Resources\Pasajeros\Pages;

use App\Filament\Resources\Pasajeros\PasajeroResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPasajeros extends ListRecords
{
    protected static string $resource = PasajeroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
