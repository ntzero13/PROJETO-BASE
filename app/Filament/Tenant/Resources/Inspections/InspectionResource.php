<?php

namespace App\Filament\Tenant\Resources\Inspections;

use App\Filament\Tenant\Resources\Inspections\Pages\ManageInspections;
use App\Models\Tenant\Inspection;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class InspectionResource extends Resource
{
    protected static ?string $model = Inspection::class;

    protected static ?string $modelLabel = 'vistoria';

    protected static ?string $pluralModelLabel = 'vistorias';

    protected static ?string $navigationLabel = 'Vistorias';

    protected static string|\UnitEnum|null $navigationGroup = 'Vistorias';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('created_by')
                    ->default(fn (): ?int => auth('tenant')->id()),
                TextInput::make('title')
                    ->label('Título')
                    ->required()
                    ->maxLength(255),
                Select::make('client_id')
                    ->label('Cliente')
                    ->relationship('client', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('inspected_location_id')
                    ->label('Local vistoriado')
                    ->relationship('inspectedLocation', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'rascunho' => 'Rascunho',
                        'em_andamento' => 'Em andamento',
                        'finalizada' => 'Finalizada',
                        'cancelada' => 'Cancelada',
                    ])
                    ->required()
                    ->default('rascunho'),
                DateTimePicker::make('performed_at')
                    ->label('Data da vistoria'),
                Textarea::make('summary')
                    ->label('Resumo')
                    ->rows(3),
                Textarea::make('observations')
                    ->label('Observações')
                    ->rows(4),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Vistoria')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('client.name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('inspectedLocation.name')
                    ->label('Local')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'finalizada' => 'success',
                        'cancelada' => 'danger',
                        'em_andamento' => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('performed_at')
                    ->label('Data')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Criada em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'rascunho' => 'Rascunho',
                        'em_andamento' => 'Em andamento',
                        'finalizada' => 'Finalizada',
                        'cancelada' => 'Cancelada',
                    ]),
            ])
            ->recordActions([
                ViewAction::make()->label('Ver'),
                EditAction::make()
                    ->label('Editar')
                    ->hidden(fn (Inspection $record): bool => $record->status === 'finalizada'),
                DeleteAction::make()
                    ->label('Excluir')
                    ->hidden(fn (Inspection $record): bool => $record->status === 'finalizada'),
            ])
            ->toolbarActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageInspections::route('/'),
        ];
    }
}
