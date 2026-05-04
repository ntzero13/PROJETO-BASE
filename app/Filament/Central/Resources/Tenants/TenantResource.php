<?php

namespace App\Filament\Central\Resources\Tenants;

use App\Filament\Central\Resources\Tenants\Pages\ManageTenants;
use App\Enums\CompanyStatus;
use App\Models\Tenant;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id')
                    ->label('ID')
                    ->disabled()
                    ->dehydrated(false),
                TextInput::make('name')
                    ->label('Empresa')
                    ->required()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                TextInput::make('company_document')
                    ->label('Documento')
                    ->maxLength(32),
                TextInput::make('contact_name')
                    ->label('Responsavel')
                    ->required()
                    ->maxLength(255),
                TextInput::make('contact_email')
                    ->label('E-mail')
                    ->email()
                    ->required()
                    ->maxLength(255),
                TextInput::make('contact_phone')
                    ->label('Telefone')
                    ->maxLength(30),
                Select::make('status')
                    ->label('Status')
                    ->options(collect(CompanyStatus::cases())->mapWithKeys(
                        fn (CompanyStatus $status): array => [$status->value => $status->name],
                    )->all())
                    ->required(),
                Select::make('plan_id')
                    ->label('Plano')
                    ->relationship('plan', 'name')
                    ->searchable()
                    ->preload(),
                DateTimePicker::make('trial_ends_at')
                    ->label('Fim do teste'),
                DateTimePicker::make('provisioned_at')
                    ->label('Provisionado em')
                    ->disabled()
                    ->dehydrated(false),
                KeyValue::make('settings')
                    ->label('Configuracoes')
                    ->disabled()
                    ->dehydrated(false)
                    ->columnSpanFull(),
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
                    ->label('Empresa')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('contact_email')
                    ->label('E-mail')
                    ->searchable(),
                TextColumn::make('plan.name')
                    ->label('Plano')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable(),
                TextColumn::make('trial_ends_at')
                    ->label('Fim do teste')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(collect(CompanyStatus::cases())->mapWithKeys(
                        fn (CompanyStatus $status): array => [$status->value => $status->name],
                    )->all()),
                SelectFilter::make('plan_id')
                    ->label('Plano')
                    ->relationship('plan', 'name'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageTenants::route('/'),
        ];
    }
}
