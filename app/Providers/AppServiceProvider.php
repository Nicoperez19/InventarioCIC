<?php
namespace App\Providers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(\App\Services\InventoryService::class);
        $this->app->singleton(\App\Services\RequestService::class);
    }
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        if (app()->environment('local')) {
            Model::preventLazyLoading();
        }
        Paginator::defaultView('vendor.livewire.tailwind');
        Paginator::defaultSimpleView('vendor.livewire.simple-tailwind');
        date_default_timezone_set('America/Santiago');

        if (config('app.env') == 'production') {
            \URL::forceScheme('https');
        }
    }
}
