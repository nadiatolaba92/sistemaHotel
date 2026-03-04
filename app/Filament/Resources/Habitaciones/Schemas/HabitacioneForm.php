<?php

namespace App\Filament\Resources\Habitaciones\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class HabitacioneForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Detalles Principales')
                        ->description('Información básica de la habitación')
                        ->icon('heroicon-m-home')
                        ->schema([
                            TextInput::make('habitacion_numero')
                                ->label('Número de Habitación')
                                ->required(),

                            Select::make('tipo_id')
                                ->label('Tipo de Habitación')
                                ->relationship('tipo', 'tipo_nombre')
                                ->native(false)
                                ->required(),

                            Select::make('estado')
                                ->label('Estado')
                                ->options([
                                    'Disponible' => 'Disponible',
                                    'Ocupada' => 'Ocupada',
                                    'Mantenimiento' => 'Mantenimiento',
                                ])
                                ->default('Disponible')
                                ->native(false)
                                ->required(),
                        ])->columns(3),

                    Step::make('Información Adicional')
                        ->description('Descripción y fotografías')
                        ->icon('heroicon-m-photo')
                        ->schema([
                            RichEditor::make('descripcion')
                                ->label('Descripción')
                                ->toolbarButtons([
                                    ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript', 'link'],
                                    ['h2', 'h3', 'alignStart', 'alignCenter', 'alignEnd'],
                                    ['blockquote', 'codeBlock', 'bulletList', 'orderedList'],
                                    ['table', 'attachFiles'], // The `customBlocks` and `mergeTags` tools are also added here if those features are used.
                                    ['undo', 'redo'],
                                ])

                                ->columnSpanFull(),

                            SpatieMediaLibraryFileUpload::make('imagen')
                                ->label('Imagen de la Habitación')
                                ->collection('habitaciones')

                                ->multiple()
                                ->reorderable()
                                ->columnSpanFull(),
                        ]),
                ])->columnSpanFull(),
            ]);
    }
}
