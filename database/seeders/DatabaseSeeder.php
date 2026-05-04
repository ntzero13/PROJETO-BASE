<?php

namespace Database\Seeders;

use App\Models\Central\Plan;
use App\Models\Central\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Plan::query()->upsert([
            [
                'name' => 'Teste',
                'slug' => 'teste',
                'description' => 'Plano inicial para demonstração local.',
                'price_cents' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'Profissional',
                'slug' => 'profissional',
                'description' => 'Plano base para operação comercial.',
                'price_cents' => 19900,
                'is_active' => true,
            ],
        ], ['slug'], ['name', 'description', 'price_cents', 'is_active']);

        User::query()->firstOrCreate(
            ['email' => env('CENTRAL_ADMIN_EMAIL', 'admin@sistema.test')],
            [
                'name' => env('CENTRAL_ADMIN_NAME', 'Administrador Master'),
                'password' => env('CENTRAL_ADMIN_PASSWORD', 'password'),
                'is_super_admin' => true,
                'is_active' => true,
                'email_verified_at' => now(),
            ],
        );
    }
}
