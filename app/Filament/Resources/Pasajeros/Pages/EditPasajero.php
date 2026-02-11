<?php

namespace App\Filament\Resources\Pasajeros\Pages;

use App\Filament\Resources\Pasajeros\PasajeroResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPasajero extends EditRecord
{
    protected static string $resource = PasajeroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
