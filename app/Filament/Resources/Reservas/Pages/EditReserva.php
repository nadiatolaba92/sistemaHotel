<?php

namespace App\Filament\Resources\Reservas\Pages;

use App\Actions\Reservas\GuardarReservaAction;
use App\Filament\Resources\Reservas\ReservaResource;
use App\Models\Reserva;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use LogicException;

class EditReserva extends EditRecord
{
    protected static string $resource = ReservaResource::class;

    private GuardarReservaAction $guardarReservaAction;

    public function boot(GuardarReservaAction $guardarReservaAction): void
    {
        $this->guardarReservaAction = $guardarReservaAction;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (! $record instanceof Reserva) {
            throw new LogicException('El registro debe ser una reserva.');
        }

        return $this->guardarReservaAction->execute($data, $record);
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
