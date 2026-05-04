<?php

namespace App\Filament\Tenant\Resources\InspectionAttachments;

use App\Filament\Tenant\Resources\InspectionAttachments\Pages\ManageInspectionAttachments;
use App\Models\Tenant\InspectionAttachment;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class InspectionAttachmentResource extends Resource
{
    protected static ?string $model = InspectionAttachment::class;

    protected static ?string $modelLabel = 'anexo';

    protected static ?string $pluralModelLabel = 'anexos';

    protected static ?string $navigationLabel = 'Anexos';

    protected static string|\UnitEnum|null $navigationGroup = 'Vistorias';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('inspection_id')
                    ->label('Vistoria')
                    ->relationship('inspection', 'title')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('type')
                    ->label('Tipo')
                    ->options([
                        'foto' => 'Foto',
                        'documento' => 'Documento',
                        'relatorio' => 'Relatório',
                    ])
                    ->default('foto')
                    ->required(),
                Select::make('disk')
                    ->label('Armazenamento')
                    ->options(['local' => 'Privado'])
                    ->default('local')
                    ->disabled()
                    ->dehydrated(),
                FileUpload::make('path')
                    ->label('Arquivo')
                    ->disk('local')
                    ->directory('inspection-attachments')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'application/pdf'])
                    ->maxSize(10240)
                    ->required()
                    ->downloadable(),
                TextInput::make('caption')
                    ->label('Legenda')
                    ->maxLength(255),
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
                TextColumn::make('inspection.title')
                    ->label('Vistoria')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->sortable(),
                TextColumn::make('caption')
                    ->label('Legenda')
                    ->searchable(),
                TextColumn::make('disk')
                    ->label('Armazenamento')
                    ->badge()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Enviado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Tipo')
                    ->options([
                        'foto' => 'Foto',
                        'documento' => 'Documento',
                        'relatorio' => 'Relatório',
                    ]),
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
            'index' => ManageInspectionAttachments::route('/'),
        ];
    }
}
