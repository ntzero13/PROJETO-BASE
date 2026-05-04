<?php

namespace App\Filament\Tenant\Resources\InspectedLocations\Pages;

use App\Filament\Tenant\Resources\InspectedLocations\InspectedLocationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageInspectedLocations extends ManageRecords
{
    protected static string $resource = InspectedLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Criar local'),
        ];
    }
}
