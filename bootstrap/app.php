<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Middleware de logging para rutas web
        $middleware->web(append: [
            \App\Http\Middleware\RequestLoggingMiddleware::class,
        ]);
    })
    ->withSchedule(function (Schedule $schedule): void {
        // Verificar insumos con stock bajo diariamente a las 8:00 AM
        $schedule->command('insumos:check-low-stock')
            ->dailyAt('08:00')
            ->timezone('America/Santiago')
            ->withoutOverlapping()
            ->runInBackground();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
