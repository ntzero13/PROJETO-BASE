<?php

namespace App\Enums;

enum CompanyStatus: string
{
    case Active = 'ativa';
    case Suspended = 'suspensa';
    case Trial = 'teste';
    case Cancelled = 'cancelada';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
