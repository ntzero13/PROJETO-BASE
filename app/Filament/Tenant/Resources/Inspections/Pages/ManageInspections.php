<?php

namespace App\Filament\Tenant\Resources\Inspections\Pages;

use App\Filament\Tenant\Resources\Inspections\InspectionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageInspections extends ManageRecords
{
    protected static string $resource = InspectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Criar vistoria'),
        ];
    }
}
