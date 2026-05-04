<?php

namespace App\Models;

use App\Enums\CompanyStatus;
use App\Models\Central\Plan;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase;
    use HasDomains;

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
            'slug',
            'company_document',
            'contact_name',
            'contact_email',
            'contact_phone',
            'status',
            'plan_id',
            'trial_ends_at',
            'provisioned_at',
            'settings',
            'created_at',
            'updated_at',
        ];
    }

    protected $fillable = [
        'id',
        'name',
        'slug',
        'company_document',
        'contact_name',
        'contact_email',
        'contact_phone',
        'status',
        'plan_id',
        'trial_ends_at',
        'provisioned_at',
        'settings',
        'data',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
            'settings' => 'array',
            'trial_ends_at' => 'datetime',
            'provisioned_at' => 'datetime',
            'status' => CompanyStatus::class,
        ];
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }
}
