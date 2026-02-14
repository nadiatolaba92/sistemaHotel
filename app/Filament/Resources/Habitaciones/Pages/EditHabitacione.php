<?php

namespace App\Filament\Resources\Habitaciones\Pages;

use App\Filament\Resources\Habitaciones\HabitacioneResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHabitacione extends EditRecord
{
    protected static string $resource = HabitacioneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
