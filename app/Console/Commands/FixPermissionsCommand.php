<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class FixPermissionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crear y asignar todos los permisos necesarios';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Configurando permisos del sistema...');
        
        try {
            // 1. Crear permisos
            $this->createPermissions();
            
            // 2. Crear roles
            $this->createRoles();
            
            // 3. Asignar permisos a roles
            $this->assignPermissionsToRoles();
            
            // 4. Asignar roles a usuarios
            $this->assignRolesToUsers();
            
            $this->info('✅ Configuración de permisos completada');
            $this->info('📁 Logs detallados en: storage/logs/laravel.log');
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            Log::error('Error configurando permisos', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->toISOString()
            ]);
            
            $this->error("❌ Error configurando permisos: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
    
    private function createPermissions()
    {
        $this->info('📝 Creando permisos...');
        
        $permissions = [
            'manage-inventory' => 'Gestionar inventario',
            'manage-users' => 'Gestionar usuarios',
            'manage-roles' => 'Gestionar roles',
            'manage-departments' => 'Gestionar departamentos',
            'manage-units' => 'Gestionar unidades'
        ];
        
        foreach ($permissions as $name => $displayName) {
            $permission = Permission::firstOrCreate(
                ['name' => $name],
                ['display_name' => $displayName]
            );
            
            $this->line("✅ Permiso: {$name}");
            
            Log::info('Permiso creado/verificado', [
                'permission_name' => $name,
                'display_name' => $displayName,
                'timestamp' => now()->toISOString()
            ]);
        }
    }
    
    private function createRoles()
    {
        $this->info('🎭 Creando roles...');
        
        $roles = [
            'admin' => 'Administrador',
            'user' => 'Usuario'
        ];
        
        foreach ($roles as $name => $displayName) {
            $role = Role::firstOrCreate(
                ['name' => $name],
                ['display_name' => $displayName]
            );
            
            $this->line("✅ Rol: {$name}");
            
            Log::info('Rol creado/verificado', [
                'role_name' => $name,
                'display_name' => $displayName,
                'timestamp' => now()->toISOString()
            ]);
        }
    }
    
    private function assignPermissionsToRoles()
    {
        $this->info('🔗 Asignando permisos a roles...');
        
        // Admin tiene todos los permisos
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $allPermissions = Permission::all();
            foreach ($allPermissions as $permission) {
                if (!$adminRole->hasPermissionTo($permission->name)) {
                    $adminRole->givePermissionTo($permission);
                    $this->line("✅ Permiso {$permission->name} asignado a admin");
                }
            }
        }
        
        // User tiene permisos básicos
        $userRole = Role::where('name', 'user')->first();
        if ($userRole) {
            $basicPermissions = ['manage-inventory'];
            foreach ($basicPermissions as $permissionName) {
                $permission = Permission::where('name', $permissionName)->first();
                if ($permission && !$userRole->hasPermissionTo($permissionName)) {
                    $userRole->givePermissionTo($permission);
                    $this->line("✅ Permiso {$permissionName} asignado a user");
                }
            }
        }
    }
    
    private function assignRolesToUsers()
    {
        $this->info('👥 Asignando roles a usuarios...');
        
        $users = User::all();
        
        foreach ($users as $user) {
            $this->line("👤 Usuario: {$user->name} (ID: {$user->id})");
            
            // Asignar rol admin al primer usuario
            if ($user->id == 1 || $user->email == 'admin@ucsc.cl') {
                $adminRole = Role::where('name', 'admin')->first();
                if ($adminRole && !$user->hasRole('admin')) {
                    $user->assignRole($adminRole);
                    $this->line("✅ Rol admin asignado a {$user->name}");
                    
                    Log::info('Rol asignado a usuario', [
                        'user_id' => $user->id,
                        'user_name' => $user->name,
                        'role_name' => 'admin',
                        'timestamp' => now()->toISOString()
                    ]);
                }
            } else {
                // Asignar rol user a otros usuarios
                $userRole = Role::where('name', 'user')->first();
                if ($userRole && !$user->hasRole('user')) {
                    $user->assignRole($userRole);
                    $this->line("✅ Rol user asignado a {$user->name}");
                }
            }
            
            // Verificar permisos finales
            $this->line("   Permisos:");
            $permissions = ['manage-inventory', 'manage-users', 'manage-roles'];
            foreach ($permissions as $permission) {
                $hasPermission = $user->can($permission);
                $status = $hasPermission ? '✅' : '❌';
                $this->line("     {$status} {$permission}");
            }
        }
    }
}
