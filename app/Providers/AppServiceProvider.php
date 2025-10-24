<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Registrar servicios
        $this->app->singleton(\App\Services\InventoryService::class);
        $this->app->singleton(\App\Services\RequestService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configurar longitud de string por defecto para MySQL
        Schema::defaultStringLength(191);

        // Habilitar lazy loading estricto en desarrollo
        if (app()->environment('local')) {
            Model::preventLazyLoading();
        }

        // Configurar paginación con Tailwind CSS
        Paginator::defaultView('vendor.livewire.tailwind');
        Paginator::defaultSimpleView('vendor.livewire.simple-tailwind');

        // Configurar timezone
        date_default_timezone_set('America/Santiago');
    }
}
