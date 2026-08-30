<?php

namespace App\Filament\Resources\Reservas\Pages;

use App\Actions\Reservas\GuardarReservaAction;
use App\Filament\Resources\Reservas\ReservaResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateReserva extends CreateRecord
{
    protected static string $resource = ReservaResource::class;

    private GuardarReservaAction $guardarReservaAction;

    public function boot(GuardarReservaAction $guardarReservaAction): void
    {
        $this->guardarReservaAction = $guardarReservaAction;
    }

    protected function handleRecordCreation(array $data): Model
    {
        return $this->guardarReservaAction->execute($data);
    }
}
