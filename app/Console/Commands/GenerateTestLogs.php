<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;

class GenerateTestLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:generate-test {--user-id=1 : ID del usuario para las pruebas}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generar logs de prueba para diagnosticar problemas de acceso a vistas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Generando logs de prueba para diagnóstico...');
        
        $userId = $this->option('user-id');
        
        // 1. Log de verificación de vistas
        $this->generateViewLogs();
        
        // 2. Log de verificación de rutas
        $this->generateRouteLogs();
        
        // 3. Log de verificación de permisos
        $this->generatePermissionLogs($userId);
        
        // 4. Log de simulación de acceso
        $this->generateAccessSimulationLogs($userId);
        
        $this->info('✅ Logs de prueba generados exitosamente');
        $this->info('📁 Revisa el archivo: storage/logs/laravel.log');
        
        return Command::SUCCESS;
    }
    
    private function generateViewLogs()
    {
        $this->info('📄 Verificando vistas...');
        
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
        
        foreach ($views as $view) {
            $exists = View::exists($view);
            $filePath = resource_path('views/' . str_replace('.', '/', $view) . '.blade.php');
            $fileExists = File::exists($filePath);
            
            Log::info('Verificación de vista', [
                'view_name' => $view,
                'view_exists' => $exists,
                'file_exists' => $fileExists,
                'file_path' => $filePath,
                'timestamp' => now()->toISOString()
            ]);
            
            if ($exists && $fileExists) {
                $this->line("✅ {$view}");
            } else {
                $this->error("❌ {$view} - Vista: " . ($exists ? 'OK' : 'NO') . ", Archivo: " . ($fileExists ? 'OK' : 'NO'));
            }
        }
    }
    
    private function generateRouteLogs()
    {
        $this->info('🛣️  Verificando rutas...');
        
        $routes = [
            'proveedores.index',
            'proveedores.create',
            'proveedores.show',
            'proveedores.edit',
            'facturas.index',
            'facturas.create',
            'facturas.show',
            'facturas.edit'
        ];
        
        foreach ($routes as $routeName) {
            try {
                $route = Route::getRoutes()->getByName($routeName);
                $url = $route ? $route->uri() : null;
                $middleware = $route ? $route->gatherMiddleware() : [];
                
                Log::info('Verificación de ruta', [
                    'route_name' => $routeName,
                    'route_exists' => $route !== null,
                    'url' => $url,
                    'middleware' => $middleware,
                    'timestamp' => now()->toISOString()
                ]);
                
                if ($route) {
                    $this->line("✅ {$routeName} -> {$url}");
                } else {
                    $this->error("❌ {$routeName} - NO ENCONTRADA");
                }
            } catch (\Exception $e) {
                Log::error('Error verificando ruta', [
                    'route_name' => $routeName,
                    'error' => $e->getMessage(),
                    'timestamp' => now()->toISOString()
                ]);
                $this->error("❌ {$routeName} - ERROR: " . $e->getMessage());
            }
        }
    }
    
    private function generatePermissionLogs($userId)
    {
        $this->info('🔐 Verificando permisos...');
        
        try {
            $user = \App\Models\User::find($userId);
            
            if (!$user) {
                Log::warning('Usuario no encontrado para verificación de permisos', [
                    'user_id' => $userId,
                    'timestamp' => now()->toISOString()
                ]);
                $this->warn("⚠️  Usuario ID {$userId} no encontrado");
                return;
            }
            
            $permissions = [
                'manage-inventory',
                'manage-users', 
                'manage-roles',
                'manage-departments',
                'manage-units'
            ];
            
            foreach ($permissions as $permission) {
                $hasPermission = $user->can($permission);
                
                Log::info('Verificación de permiso', [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'user_run' => $user->run,
                    'permission' => $permission,
                    'has_permission' => $hasPermission,
                    'timestamp' => now()->toISOString()
                ]);
                
                $status = $hasPermission ? '✅' : '❌';
                $this->line("{$status} {$permission} - {$user->name}");
            }
            
        } catch (\Exception $e) {
            Log::error('Error verificando permisos', [
                'user_id' => $userId,
                'error' => $e->getMessage(),
                'timestamp' => now()->toISOString()
            ]);
            $this->error("❌ Error verificando permisos: " . $e->getMessage());
        }
    }
    
    private function generateAccessSimulationLogs($userId)
    {
        $this->info('🎭 Simulando acceso a vistas...');
        
        try {
            $user = \App\Models\User::find($userId);
            
            if (!$user) {
                $this->warn("⚠️  Usuario ID {$userId} no encontrado, saltando simulación");
                return;
            }
            
            // Simular acceso a proveedores
            Log::info('Simulación de acceso - Proveedores', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_run' => $user->run,
                'action' => 'acceso_proveedores',
                'route' => 'proveedores.index',
                'middleware_required' => ['auth', 'can:manage-inventory'],
                'timestamp' => now()->toISOString()
            ]);
            
            // Simular acceso a facturas
            Log::info('Simulación de acceso - Facturas', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_run' => $user->run,
                'action' => 'acceso_facturas',
                'route' => 'facturas.index',
                'middleware_required' => ['auth'],
                'timestamp' => now()->toISOString()
            ]);
            
            $this->line("✅ Simulación de acceso completada para {$user->name}");
            
        } catch (\Exception $e) {
            Log::error('Error en simulación de acceso', [
                'user_id' => $userId,
                'error' => $e->getMessage(),
                'timestamp' => now()->toISOString()
            ]);
            $this->error("❌ Error en simulación: " . $e->getMessage());
        }
    }
}