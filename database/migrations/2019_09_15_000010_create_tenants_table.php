<?php

declare(strict_types=1);

use App\Enums\CompanyStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('company_document', 32)->nullable();
            $table->string('contact_name');
            $table->string('contact_email');
            $table->string('contact_phone', 30)->nullable();
            $table->string('status', 20)->default(CompanyStatus::Trial->value);
            $table->foreignId('plan_id')->nullable()->constrained('plans')->nullOnDelete();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('provisioned_at')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->json('data')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
