<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class CheckUsersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verificar usuarios existentes y sus permisos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('👥 Verificando usuarios en la base de datos...');
        
        try {
            $users = User::all();
            
            if ($users->isEmpty()) {
                $this->warn('⚠️  No hay usuarios en la base de datos');
                Log::warning('No hay usuarios en la base de datos', [
                    'timestamp' => now()->toISOString()
                ]);
                return Command::SUCCESS;
            }
            
            $this->info("📊 Total de usuarios: {$users->count()}");
            
            foreach ($users as $user) {
                $this->line("\n👤 Usuario ID: {$user->id}");
                $this->line("   Nombre: {$user->name}");
                $this->line("   Email: {$user->email}");
                $this->line("   RUN: {$user->run}");
                $this->line("   Creado: {$user->created_at}");
                
                // Verificar permisos
                $permissions = [
                    'manage-inventory',
                    'manage-users',
                    'manage-roles',
                    'manage-departments',
                    'manage-units'
                ];
                
                $this->line("   Permisos:");
                foreach ($permissions as $permission) {
                    $hasPermission = $user->can($permission);
                    $status = $hasPermission ? '✅' : '❌';
                    $this->line("     {$status} {$permission}");
                    
                    Log::info('Verificación de permiso de usuario', [
                        'user_id' => $user->id,
                        'user_name' => $user->name,
                        'permission' => $permission,
                        'has_permission' => $hasPermission,
                        'timestamp' => now()->toISOString()
                    ]);
                }
                
                // Verificar roles
                if (method_exists($user, 'roles')) {
                    $roles = $user->roles;
                    $this->line("   Roles: " . ($roles->count() > 0 ? $roles->pluck('name')->join(', ') : 'Sin roles'));
                }
            }
            
            $this->info("\n✅ Verificación de usuarios completada");
            $this->info('📁 Logs detallados en: storage/logs/laravel.log');
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            Log::error('Error verificando usuarios', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->toISOString()
            ]);
            
            $this->error("❌ Error verificando usuarios: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
