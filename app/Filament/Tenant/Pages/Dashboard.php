<?php

namespace App\Filament\Tenant\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationLabel = 'Visao geral';

    protected static ?string $title = 'Painel da empresa';
}
