<?php

namespace App\Filament\Tenant\Resources\CompanySettings\Pages;

use App\Filament\Tenant\Resources\CompanySettings\CompanySettingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCompanySettings extends ManageRecords
{
    protected static string $resource = CompanySettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Criar configuração'),
        ];
    }
}
