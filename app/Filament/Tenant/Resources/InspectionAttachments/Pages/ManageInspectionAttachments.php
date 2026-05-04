<?php

namespace App\Filament\Tenant\Resources\InspectionAttachments\Pages;

use App\Filament\Tenant\Resources\InspectionAttachments\InspectionAttachmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageInspectionAttachments extends ManageRecords
{
    protected static string $resource = InspectionAttachmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Criar anexo'),
        ];
    }
}
