<?php

namespace App\Filament\Widgets;

use App\Models\Reserva;
use Filament\Forms\Components\DatePicker;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class ReporteVentasPorTipoWidget extends BaseWidget
{
    protected static ?string $heading = 'Reporte de Ocupación por Tipo (Vendido)';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Reserva::query()
                    ->selectRaw('tipo_solicitado_id, count(*) as cantidad_vendida, sum(numero_personas) as total_personas')
                    ->whereIn('estado', ['Confirmada', 'Completada'])
                    ->groupBy('tipo_solicitado_id')
            )
            ->columns([
                Tables\Columns\TextColumn::make('tipoSolicitado.tipo_nombre')
                    ->label('Tipo Vendido'),
                Tables\Columns\TextColumn::make('cantidad_vendida')
                    ->label('Habitaciones Ocupadas')
                    ->numeric(),
                Tables\Columns\TextColumn::make('total_personas')
                    ->label('Huéspedes Totales')
                    ->numeric(),
            ])
            ->filters([
                Filter::make('fecha')
                    ->form([
                        DatePicker::make('fecha_reporte')
                            ->label('Fecha')
                            ->default(now()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $fecha = $data['fecha_reporte'] ?? now()->toDateString();

                        return $query
                            ->where('fecha_entrada', '<=', $fecha)
                            ->where('fecha_salida', '>', $fecha);
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (! $data['fecha_reporte']) {
                            return 'Hoy';
                        }

                        return 'Fecha: '.$data['fecha_reporte'];
                    }),
            ]);
    }
}
