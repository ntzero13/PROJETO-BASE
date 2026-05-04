<?php

namespace App\Filament\Tenant\Resources\CompanySettings;

use App\Filament\Tenant\Resources\CompanySettings\Pages\ManageCompanySettings;
use App\Models\Tenant\CompanySetting;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CompanySettingResource extends Resource
{
    protected static ?string $model = CompanySetting::class;

    protected static ?string $modelLabel = 'configuração da empresa';

    protected static ?string $pluralModelLabel = 'configurações da empresa';

    protected static ?string $navigationLabel = 'Configurações';

    protected static string|\UnitEnum|null $navigationGroup = 'Administração';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('company_name')
                    ->label('Razão social')
                    ->required()
                    ->maxLength(255),
                TextInput::make('trade_name')
                    ->label('Nome fantasia')
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('E-mail')
                    ->email()
                    ->maxLength(255),
                TextInput::make('phone')
                    ->label('Telefone')
                    ->maxLength(30),
                KeyValue::make('report_preferences')
                    ->label('Preferências do relatório'),
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
                TextColumn::make('company_name')
                    ->label('Razão social')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('trade_name')
                    ->label('Nome fantasia')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Telefone'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()->label('Ver'),
                EditAction::make()->label('Editar'),
                DeleteAction::make()->label('Excluir'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Excluir selecionados'),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageCompanySettings::route('/'),
        ];
    }
}
