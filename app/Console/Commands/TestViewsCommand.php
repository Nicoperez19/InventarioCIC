<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;

class TestViewsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:views {--log : Generar logs detallados}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Probar la existencia y accesibilidad de las vistas de proveedor y factura';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Verificando vistas de proveedor y factura...');
        
        $views = [
            'layouts.proveedor.proveedor_index',
            'layouts.proveedor.proveedor_create',
            'layouts.proveedor.proveedor_show',
            'layouts.proveedor.proveedor_update',
            'layouts.factura.factura_index',
            'layouts.factura.factura_create',
            'layouts.factura.factura_show',
            'layouts.factura.factura_update'
        ];
        
        $results = [];
        
        foreach ($views as $view) {
            $exists = View::exists($view);
            $filePath = resource_path('views/' . str_replace('.', '/', $view) . '.blade.php');
            $fileExists = File::exists($filePath);
            
            $results[$view] = [
                'exists' => $exists,
                'file_exists' => $fileExists,
                'path' => $filePath
            ];
            
            $status = $exists && $fileExists ? '✅' : '❌';
            $this->line("{$status} {$view}");
            
            if (!$exists || !$fileExists) {
                $this->error("   - Vista: " . ($exists ? 'OK' : 'NO EXISTE'));
                $this->error("   - Archivo: " . ($fileExists ? 'OK' : 'NO EXISTE'));
                $this->error("   - Ruta: {$filePath}");
            }
        }
        
        // Log detallado si se solicita
        if ($this->option('log')) {
            Log::info('TestViewsCommand - Verificación de vistas completada', [
                'results' => $results,
                'timestamp' => now()->toISOString()
            ]);
            
            $this->info('📝 Logs detallados generados en storage/logs/laravel.log');
        }
        
        // Verificar rutas
        $this->info("\n🔗 Verificando rutas...");
        $routes = [
            'proveedores.index' => route('proveedores.index'),
            'facturas.index' => route('facturas.index')
        ];
        
        foreach ($routes as $name => $url) {
            $this->line("✅ {$name}: {$url}");
        }
        
        // Verificar permisos de usuario
        $this->info("\n👤 Verificando permisos...");
        $user = auth()->user();
        if ($user) {
            $this->line("Usuario autenticado: {$user->name} (ID: {$user->id})");
            $this->line("RUN: {$user->run}");
            
            // Verificar permisos específicos
            $permissions = [
                'manage-inventory' => $user->can('manage-inventory'),
                'manage-users' => $user->can('manage-users'),
                'manage-roles' => $user->can('manage-roles')
            ];
            
            foreach ($permissions as $permission => $hasPermission) {
                $status = $hasPermission ? '✅' : '❌';
                $this->line("{$status} {$permission}");
            }
        } else {
            $this->warn('⚠️  No hay usuario autenticado');
        }
        
        $this->info("\n🎯 Para generar logs detallados, ejecuta: php artisan test:views --log");
        
        return Command::SUCCESS;
    }
}
