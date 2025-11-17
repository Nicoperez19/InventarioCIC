<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\RateLimiter;

class ClearLoginThrottle extends Command
{
    protected $signature = 'auth:clear-throttle {run?}';
    protected $description = 'Limpiar el rate limiter de login para un RUN específico o todos';

    public function handle()
    {
        $run = $this->argument('run');
        
        if ($run) {
            // Limpiar para un RUN específico
            $formattedRun = \App\Helpers\RunFormatter::format($run);
            $key = \Illuminate\Support\Str::transliterate(\Illuminate\Support\Str::lower($formattedRun) . '|' . request()->ip());
            
            RateLimiter::clear($key);
            $this->info("✓ Rate limiter limpiado para RUN: {$formattedRun}");
        } else {
            // Limpiar todos los rate limiters (esto es más agresivo)
            $this->warn('Limpiando todos los rate limiters de login...');
            // Nota: Laravel no tiene un método directo para limpiar todos, 
            // pero podemos limpiar la caché que usa RateLimiter
            \Illuminate\Support\Facades\Cache::flush();
            $this->info("✓ Todos los rate limiters han sido limpiados");
        }
        
        return Command::SUCCESS;
    }
}

