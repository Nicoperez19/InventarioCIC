<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

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
        
        // Configurar paginación
        Paginator::useBootstrapFive();
        
        // Configurar timezone
        date_default_timezone_set('America/Santiago');
    }
}