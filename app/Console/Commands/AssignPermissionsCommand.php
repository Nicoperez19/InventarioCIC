<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

class AssignPermissionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:assign {--user-id=1 : ID del usuario} {--role=admin : Rol a asignar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Asignar permisos a usuarios';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->option('user-id');
        $roleName = $this->option('role');
        
        $this->info("🔐 Asignando permisos al usuario ID {$userId}...");
        
        try {
            // Buscar usuario
            $user = User::find($userId);
            if (!$user) {
                $this->error("❌ Usuario ID {$userId} no encontrado");
                return Command::FAILURE;
            }
            
            $this->info("👤 Usuario encontrado: {$user->name}");
            
            // Buscar o crear rol
            $role = Role::firstOrCreate(
                ['name' => $roleName],
                ['display_name' => ucfirst($roleName)]
            );
            
            $this->info("🎭 Rol: {$role->name}");
            
            // Asignar rol al usuario
            if (!$user->hasRole($role->name)) {
                $user->assignRole($role);
                $this->line("✅ Rol {$role->name} asignado al usuario");
                
                Log::info('Rol asignado a usuario', [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'role_name' => $role->name,
                    'timestamp' => now()->toISOString()
                ]);
            } else {
                $this->line("ℹ️  Usuario ya tiene el rol {$role->name}");
            }
            
            // Crear permisos si no existen
            $permissions = [
                'manage-inventory' => 'Gestionar inventario',
                'manage-users' => 'Gestionar usuarios',
                'manage-roles' => 'Gestionar roles',
                'manage-departments' => 'Gestionar departamentos',
                'manage-units' => 'Gestionar unidades'
            ];
            
            foreach ($permissions as $permissionName => $displayName) {
                $permission = Permission::firstOrCreate(
                    ['name' => $permissionName],
                    ['display_name' => $displayName]
                );
                
                // Asignar permiso al rol
                if (!$role->hasPermissionTo($permissionName)) {
                    $role->givePermissionTo($permission);
                    $this->line("✅ Permiso {$permissionName} asignado al rol");
                    
                    Log::info('Permiso asignado a rol', [
                        'role_name' => $role->name,
                        'permission_name' => $permissionName,
                        'timestamp' => now()->toISOString()
                    ]);
                }
            }
            
            // Verificar permisos finales
            $this->info("\n🔍 Verificando permisos finales:");
            foreach ($permissions as $permissionName => $displayName) {
                $hasPermission = $user->can($permissionName);
                $status = $hasPermission ? '✅' : '❌';
                $this->line("{$status} {$permissionName}");
            }
            
            $this->info("\n✅ Permisos asignados exitosamente");
            $this->info('📁 Logs detallados en: storage/logs/laravel.log');
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            Log::error('Error asignando permisos', [
                'user_id' => $userId,
                'role_name' => $roleName,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->toISOString()
            ]);
            
            $this->error("❌ Error asignando permisos: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
