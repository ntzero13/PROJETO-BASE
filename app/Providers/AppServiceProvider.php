<?php

namespace App\Providers;

use App\Http\Middleware\InitializeTenancyForLivewire;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        CarbonImmutable::setLocale(config('app.locale'));

        app(Kernel::class)
            ->prependToMiddlewarePriority(InitializeTenancyForLivewire::class);

        Livewire::setUpdateRoute(function ($handle, string $path) {
            return Route::post($path, $handle)
                ->middleware([
                    'web',
                    InitializeTenancyForLivewire::class,
                ]);
        });
    }
}
