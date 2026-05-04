<?php

namespace App\Services\Tenants;

use App\Data\ProvisionTenantData;
use App\Models\Tenant;
use App\Models\Tenant\User as TenantUser;
use Throwable;

class ProvisionTenantService
{
    public function handle(ProvisionTenantData $data): Tenant
    {
        $tenant = Tenant::create([
            'name' => $data->name,
            'slug' => $data->slug,
            'contact_name' => $data->contactName,
            'contact_email' => $data->contactEmail,
            'contact_phone' => $data->contactPhone,
            'status' => $data->status,
            'plan_id' => $data->planId,
            'provisioned_at' => now(),
        ]);

        try {
            $tenant->domains()->create([
                'domain' => $data->domain,
                'is_primary' => true,
            ]);

            $tenant->run(function () use ($data): void {
                TenantUser::query()->create([
                    'name' => $data->initialAdminName,
                    'email' => $data->initialAdminEmail,
                    'password' => $data->initialAdminPassword,
                    'role' => 'administrador',
                    'is_active' => true,
                ]);
            });
        } catch (Throwable $exception) {
            $tenant->delete();

            throw $exception;
        }

        return $tenant->fresh(['domains', 'plan']);
    }
}
