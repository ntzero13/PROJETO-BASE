<?php

namespace App\Filament\Tenant\Resources\InspectedLocations;

use App\Filament\Tenant\Resources\InspectedLocations\Pages\ManageInspectedLocations;
use App\Models\Tenant\InspectedLocation;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class InspectedLocationResource extends Resource
{
    protected static ?string $model = InspectedLocation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('client_id')
                    ->label('Cliente')
                    ->relationship('client', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('name')
                    ->label('Nome do local')
                    ->required()
                    ->maxLength(255),
                Select::make('type')
                    ->label('Tipo')
                    ->options([
                        'imovel' => 'Imovel',
                        'veiculo' => 'Veiculo',
                        'equipamento' => 'Equipamento',
                        'outro' => 'Outro',
                    ])
                    ->required()
                    ->default('imovel'),
                TextInput::make('address')
                    ->label('Endereco')
                    ->maxLength(255),
                TextInput::make('city')
                    ->label('Cidade')
                    ->maxLength(255),
                TextInput::make('state')
                    ->label('UF')
                    ->maxLength(2),
                TextInput::make('zip_code')
                    ->label('CEP')
                    ->maxLength(12),
                Textarea::make('notes')
                    ->label('Observacoes')
                    ->rows(3),
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
                TextColumn::make('name')
                    ->label('Local')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('client.name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->sortable(),
                TextColumn::make('city')
                    ->label('Cidade')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('state')
                    ->label('UF')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Tipo')
                    ->options([
                        'imovel' => 'Imovel',
                        'veiculo' => 'Veiculo',
                        'equipamento' => 'Equipamento',
                        'outro' => 'Outro',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageInspectedLocations::route('/'),
        ];
    }
}
