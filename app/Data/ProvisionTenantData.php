<?php

namespace App\Data;

use App\Enums\CompanyStatus;

readonly class ProvisionTenantData
{
    public function __construct(
        public string $name,
        public string $slug,
        public string $domain,
        public string $contactName,
        public string $contactEmail,
        public ?string $contactPhone,
        public ?int $planId,
        public string $initialAdminName,
        public string $initialAdminEmail,
        public string $initialAdminPassword,
        public CompanyStatus $status = CompanyStatus::Trial,
    ) {}
}
