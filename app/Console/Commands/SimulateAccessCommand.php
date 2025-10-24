<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\FacturaController;

class SimulateAccessCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'simulate:access {--run=12345678-9 : RUN del usuario para autenticar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simular acceso real a las vistas de proveedor y factura';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🎭 Simulando acceso a vistas de proveedor y factura...');
        
        $run = $this->option('run');
        
        try {
            // Buscar usuario por RUN
            $user = \App\Models\User::where('run', $run)->first();
            if (!$user) {
                $this->error("❌ Usuario con RUN {$run} no encontrado");
                return Command::FAILURE;
            }
            
            $this->info("👤 Usuario encontrado: {$user->name} (RUN: {$user->run})");
            
            // Autenticar usuario
            Auth::login($user);
            $this->info("🔐 Usuario autenticado exitosamente");
            
            // Simular acceso a proveedores
            $this->simulateProveedorAccess();
            
            // Simular acceso a facturas
            $this->simulateFacturaAccess();
            
            $this->info('✅ Simulación completada exitosamente');
            $this->info('📁 Revisa los logs en: storage/logs/laravel.log');
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            Log::error('Error en simulación de acceso', [
                'user_id' => $userId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->toISOString()
            ]);
            
            $this->error("❌ Error en simulación: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
    
    private function simulateProveedorAccess()
    {
        $this->info('📋 Simulando acceso a proveedores...');
        
        try {
            // Crear request simulado
            $request = Request::create('/proveedores', 'GET');
            $request->setUserResolver(function () {
                return Auth::user();
            });
            
            // Simular middleware de autenticación
            Log::info('Simulación - Iniciando acceso a proveedores', [
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name,
                'user_run' => Auth::user()->run,
                'route' => 'proveedores.index',
                'middleware_required' => ['auth', 'can:manage-inventory'],
                'timestamp' => now()->toISOString()
            ]);
            
            // Verificar permisos
            $hasPermission = Auth::user()->can('manage-inventory');
            Log::info('Simulación - Verificación de permisos para proveedores', [
                'user_id' => Auth::id(),
                'permission' => 'manage-inventory',
                'has_permission' => $hasPermission,
                'timestamp' => now()->toISOString()
            ]);
            
            if (!$hasPermission) {
                Log::warning('Simulación - Usuario sin permisos para acceder a proveedores', [
                    'user_id' => Auth::id(),
                    'user_name' => Auth::user()->name,
                    'permission_required' => 'manage-inventory',
                    'timestamp' => now()->toISOString()
                ]);
                $this->warn("⚠️  Usuario sin permisos para acceder a proveedores");
                return;
            }
            
            // Simular llamada al controlador
            $controller = new ProveedorController();
            $response = $controller->index($request);
            
            Log::info('Simulación - Acceso a proveedores exitoso', [
                'user_id' => Auth::id(),
                'response_type' => get_class($response),
                'timestamp' => now()->toISOString()
            ]);
            
            $this->line("✅ Acceso a proveedores simulado exitosamente");
            
        } catch (\Exception $e) {
            Log::error('Simulación - Error accediendo a proveedores', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->toISOString()
            ]);
            
            $this->error("❌ Error accediendo a proveedores: " . $e->getMessage());
        }
    }
    
    private function simulateFacturaAccess()
    {
        $this->info('📄 Simulando acceso a facturas...');
        
        try {
            // Crear request simulado
            $request = Request::create('/facturas', 'GET');
            $request->setUserResolver(function () {
                return Auth::user();
            });
            
            // Simular middleware de autenticación
            Log::info('Simulación - Iniciando acceso a facturas', [
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name,
                'user_run' => Auth::user()->run,
                'route' => 'facturas.index',
                'middleware_required' => ['auth'],
                'timestamp' => now()->toISOString()
            ]);
            
            // Verificar permisos (facturas solo requiere auth)
            $isAuthenticated = Auth::check();
            Log::info('Simulación - Verificación de autenticación para facturas', [
                'user_id' => Auth::id(),
                'is_authenticated' => $isAuthenticated,
                'timestamp' => now()->toISOString()
            ]);
            
            if (!$isAuthenticated) {
                Log::warning('Simulación - Usuario no autenticado para acceder a facturas', [
                    'timestamp' => now()->toISOString()
                ]);
                $this->warn("⚠️  Usuario no autenticado");
                return;
            }
            
            // Simular llamada al controlador
            $controller = new FacturaController();
            $response = $controller->index($request);
            
            Log::info('Simulación - Acceso a facturas exitoso', [
                'user_id' => Auth::id(),
                'response_type' => get_class($response),
                'timestamp' => now()->toISOString()
            ]);
            
            $this->line("✅ Acceso a facturas simulado exitosamente");
            
        } catch (\Exception $e) {
            Log::error('Simulación - Error accediendo a facturas', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->toISOString()
            ]);
            
            $this->error("❌ Error accediendo a facturas: " . $e->getMessage());
        }
    }
}
