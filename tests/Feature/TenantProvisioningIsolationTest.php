<?php

namespace Tests\Feature;

use App\Data\ProvisionTenantData;
use App\Models\Tenant;
use App\Models\Tenant\Client;
use App\Models\Tenant\User as TenantUser;
use App\Services\Tenants\ProvisionTenantService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Stancl\Tenancy\TenantDatabaseManagers\SQLiteDatabaseManager;
use Tests\TestCase;

class TenantProvisioningIsolationTest extends TestCase
{
    private string $testingDatabasePath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->testingDatabasePath = storage_path('framework/testing/tenancy-'.Str::random(12));

        File::ensureDirectoryExists($this->testingDatabasePath);
        $this->app->useDatabasePath($this->testingDatabasePath);

        $centralDatabase = $this->testingDatabasePath.DIRECTORY_SEPARATOR.'central.sqlite';

        File::put($centralDatabase, '');

        config([
            'database.default' => 'central',
            'database.connections.central' => [
                'driver' => 'sqlite',
                'database' => $centralDatabase,
                'prefix' => '',
                'foreign_key_constraints' => true,
            ],
            'database.connections.tenant_template' => [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
                'foreign_key_constraints' => true,
            ],
            'tenancy.database.central_connection' => 'central',
            'tenancy.database.template_tenant_connection' => 'tenant_template',
            'tenancy.database.managers.sqlite' => SQLiteDatabaseManager::class,
            'tenancy.migration_parameters' => [
                '--force' => true,
                '--path' => [base_path('database/migrations/tenant')],
                '--realpath' => true,
            ],
        ]);

        DB::purge('central');
        DB::purge('tenant');

        Artisan::call('migrate:fresh', [
            '--database' => 'central',
            '--path' => base_path('database/migrations'),
            '--realpath' => true,
            '--force' => true,
        ]);
    }

    protected function tearDown(): void
    {
        tenancy()->end();

        DB::disconnect('tenant');
        DB::purge('tenant');
        DB::disconnect('central');
        DB::purge('central');

        if (isset($this->testingDatabasePath)) {
            try {
                File::deleteDirectory($this->testingDatabasePath);
            } catch (\Throwable) {
                //
            }
        }

        parent::tearDown();
    }

    public function test_provisiona_banco_da_empresa_com_nome_baseado_no_slug(): void
    {
        $empresa = $this->provisionarEmpresa('Empresa Teste', 'empresa-teste');

        $this->assertSame('tenant_empresa_teste', $empresa->database()->getName());
        $this->assertFileExists(database_path('tenant_empresa_teste'));

        $this->assertDatabaseHas('tenants', [
            'id' => $empresa->id,
            'slug' => 'empresa-teste',
            'name' => 'Empresa Teste',
        ], 'central');

        $this->assertDatabaseHas('domains', [
            'tenant_id' => $empresa->id,
            'domain' => 'empresa-teste.sistema.test',
            'is_primary' => true,
        ], 'central');

        $empresa->run(function (): void {
            $this->assertTrue(TenantUser::query()->where('email', 'admin@empresa-teste.test')->exists());
        });
    }

    public function test_dados_das_empresas_ficam_isolados_entre_bancos(): void
    {
        $empresaA = $this->provisionarEmpresa('Imobiliária Alpha', 'imobiliaria-alpha');
        $empresaB = $this->provisionarEmpresa('Imobiliária Beta', 'imobiliaria-beta');

        $empresaA->run(function (): void {
            Client::query()->create([
                'name' => 'Cliente Alpha',
                'document' => '111',
            ]);
        });

        $empresaB->run(function (): void {
            Client::query()->create([
                'name' => 'Cliente Beta',
                'document' => '222',
            ]);
        });

        $empresaA->run(function (): void {
            $this->assertSame(1, Client::query()->where('name', 'Cliente Alpha')->count());
            $this->assertSame(0, Client::query()->where('name', 'Cliente Beta')->count());
        });

        $empresaB->run(function (): void {
            $this->assertSame(1, Client::query()->where('name', 'Cliente Beta')->count());
            $this->assertSame(0, Client::query()->where('name', 'Cliente Alpha')->count());
        });

        $this->assertFileExists(database_path('tenant_imobiliaria_alpha'));
        $this->assertFileExists(database_path('tenant_imobiliaria_beta'));
        $this->assertNotSame($empresaA->database()->getName(), $empresaB->database()->getName());
    }

    private function provisionarEmpresa(string $name, string $slug): Tenant
    {
        return app(ProvisionTenantService::class)->handle(new ProvisionTenantData(
            name: $name,
            slug: $slug,
            domain: "{$slug}.sistema.test",
            contactName: 'Responsavel',
            contactEmail: "contato@{$slug}.test",
            contactPhone: null,
            planId: null,
            initialAdminName: 'Administrador',
            initialAdminEmail: "admin@{$slug}.test",
            initialAdminPassword: 'password',
        ));
    }
}
