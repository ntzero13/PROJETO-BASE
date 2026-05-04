<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    protected $fillable = [
        'company_name',
        'trade_name',
        'email',
        'phone',
        'report_preferences',
    ];

    protected function casts(): array
    {
        return [
            'report_preferences' => 'array',
        ];
    }
}
