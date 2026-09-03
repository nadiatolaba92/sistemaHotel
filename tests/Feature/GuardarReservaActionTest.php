<?php

use App\Actions\Reservas\GuardarReservaAction;
use App\Filament\Resources\Reservas\Pages\CreateReserva;
use App\Filament\Resources\Reservas\Pages\EditReserva;
use App\Models\Habitacione;
use App\Models\Reserva;
use App\Models\Tipo;
use App\Models\User;
use Database\Factories\PasajeroFactory;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;

uses(LazilyRefreshDatabase::class);

/**
 * @return array{tipo: Tipo, habitacion: Habitacione, data: array<string, mixed>}
 */
function escenarioReserva(array $habitacion = [], array $data = []): array
{
    $tipo = Tipo::factory()->create([
        'capacidad_maxima' => 2,
        'precio_noche' => 50000,
    ]);
    $habitacionCreada = Habitacione::factory()->for($tipo, 'tipo')->create($habitacion);
    $pasajero = PasajeroFactory::new()->create();

    return [
        'tipo' => $tipo,
        'habitacion' => $habitacionCreada,
        'data' => array_merge([
            'pasajero_id' => $pasajero->id,
            'habitacion_id' => $habitacionCreada->id,
            'tipo_solicitado_id' => $tipo->id,
            'fecha_entrada' => '2030-01-10',
            'fecha_salida' => '2030-01-12',
            'numero_personas' => 2,
            'estado' => 'Confirmada',
            'tipo_pago' => 'Efectivo',
            'total_pagado' => 100000,
        ], $data),
    ];
}

test('crea una reserva válida mediante la action', function () {
    $escenario = escenarioReserva();

    $reserva = (new GuardarReservaAction)->execute($escenario['data']);

    $this->assertModelExists($reserva);
    expect($reserva)
        ->habitacion_id->toBe($escenario['habitacion']->id)
        ->estado->toBe('Confirmada');
});

test('la página de Filament delega la creación en la action', function () {
    Filament::setCurrentPanel(Filament::getPanel('admin'));
    $this->actingAs(User::factory()->create());
    $escenario = escenarioReserva();

    Livewire::test(CreateReserva::class)
        ->fillForm($escenario['data'])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Reserva::query()->count())->toBe(1);
});

test('la página de Filament delega la edición en la action', function () {
    Filament::setCurrentPanel(Filament::getPanel('admin'));
    $this->actingAs(User::factory()->create());
    $escenario = escenarioReserva();
    $reserva = (new GuardarReservaAction)->execute($escenario['data']);

    Livewire::test(EditReserva::class, ['record' => $reserva->getRouteKey()])
        ->fillForm(array_merge($escenario['data'], [
            'fecha_salida' => '2030-01-13',
            'total_pagado' => 125000,
        ]))
        ->call('save')
        ->assertHasNoFormErrors();

    expect($reserva->fresh())
        ->fecha_salida->toDateString()->toBe('2030-01-13')
        ->total_pagado->toBe('125000.00');
});

test('rechaza reservas superpuestas para la misma habitación', function () {
    $escenario = escenarioReserva();
    (new GuardarReservaAction)->execute($escenario['data']);

    $dataSuperpuesta = array_merge($escenario['data'], [
        'fecha_entrada' => '2030-01-11',
        'fecha_salida' => '2030-01-13',
    ]);

    try {
        (new GuardarReservaAction)->execute($dataSuperpuesta);
        $this->fail('La reserva superpuesta debía ser rechazada.');
    } catch (ValidationException $exception) {
        expect($exception->errors())->toHaveKey('habitacion_id');
    }

    expect(Reserva::query()->count())->toBe(1);
});

test('permite reservas consecutivas sin solapamiento', function () {
    $escenario = escenarioReserva();
    (new GuardarReservaAction)->execute($escenario['data']);

    $reservaSiguiente = (new GuardarReservaAction)->execute(array_merge($escenario['data'], [
        'fecha_entrada' => '2030-01-12',
        'fecha_salida' => '2030-01-14',
    ]));

    $this->assertModelExists($reservaSiguiente);
    expect(Reserva::query()->count())->toBe(2);
});

test('una reserva cancelada no bloquea la disponibilidad', function () {
    $escenario = escenarioReserva(data: ['estado' => 'Cancelada']);
    (new GuardarReservaAction)->execute($escenario['data']);

    $reservaActiva = (new GuardarReservaAction)->execute(array_merge($escenario['data'], [
        'estado' => 'Confirmada',
    ]));

    $this->assertModelExists($reservaActiva);
    expect(Reserva::query()->count())->toBe(2);
});

test('rechaza habitaciones en mantenimiento', function () {
    $escenario = escenarioReserva(habitacion: ['estado' => 'Mantenimiento']);

    try {
        (new GuardarReservaAction)->execute($escenario['data']);
        $this->fail('La habitación en mantenimiento debía ser rechazada.');
    } catch (ValidationException $exception) {
        expect($exception->errors())->toHaveKey('habitacion_id');
    }

    expect(Reserva::query()->count())->toBe(0);
});

test('rechaza una cantidad de huéspedes superior a la capacidad física', function () {
    $escenario = escenarioReserva(data: ['numero_personas' => 3]);

    expect(fn () => (new GuardarReservaAction)->execute($escenario['data']))
        ->toThrow(ValidationException::class);

    expect(Reserva::query()->count())->toBe(0);
});

test('rechaza fechas de salida iguales o anteriores a la entrada', function (string $salida) {
    $escenario = escenarioReserva(data: ['fecha_salida' => $salida]);

    expect(fn () => (new GuardarReservaAction)->execute($escenario['data']))
        ->toThrow(ValidationException::class);
})->with([
    'misma fecha' => '2030-01-10',
    'fecha anterior' => '2030-01-09',
]);

test('actualiza una reserva sin considerarla un solapamiento consigo misma', function () {
    $escenario = escenarioReserva();
    $action = new GuardarReservaAction;
    $reserva = $action->execute($escenario['data']);

    $actualizada = $action->execute(array_merge($escenario['data'], [
        'fecha_salida' => '2030-01-13',
        'total_pagado' => 125000,
    ]), $reserva);

    expect($actualizada)
        ->id->toBe($reserva->id)
        ->fecha_salida->toDateString()->toBe('2030-01-13')
        ->total_pagado->toBe('125000.00');

    expect(Reserva::query()->count())->toBe(1);
});

test('el scope de disponibilidad excluye habitaciones en mantenimiento', function () {
    $disponible = escenarioReserva()['habitacion'];
    $mantenimiento = escenarioReserva(habitacion: ['estado' => 'Mantenimiento'])['habitacion'];

    $habitaciones = Habitacione::query()
        ->disponibles('2030-01-10', '2030-01-12')
        ->pluck('id');

    expect($habitaciones)
        ->toContain($disponible->id)
        ->not->toContain($mantenimiento->id);
});
