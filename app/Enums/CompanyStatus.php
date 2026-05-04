<?php

namespace App\Enums;

enum CompanyStatus: string
{
    case Active = 'ativa';
    case Suspended = 'suspensa';
    case Trial = 'teste';
    case Cancelled = 'cancelada';

    public function rotulo(): string
    {
        return match ($this) {
            self::Active => 'Ativa',
            self::Suspended => 'Suspensa',
            self::Trial => 'Em teste',
            self::Cancelled => 'Cancelada',
        };
    }

    public static function opcoes(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $status): array => [$status->value => $status->rotulo()])
            ->all();
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
