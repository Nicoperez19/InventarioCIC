<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class FixPermissionsCommand extends Command
{
    protected $signature = 'fix:permissions';
    protected $description = 'Crear y asignar todos los permisos necesarios';
    public function handle()
    {
        $this->info('Configurando permisos del sistema...');
        try {
            $this->createPermissions();
            $this->createRoles();
            $this->assignPermissionsToRoles();
            $this->assignRolesToUsers();
            $this->info('Configuración de permisos completada');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Error configurando permisos: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
    private function createPermissions()
    {
        $this->info('Creando permisos...');
        $permissions = [
            'manage-insumos',
            'manage-users',
            'manage-roles',
            'manage-departments',
            'manage-units',
            'manage-tipo-insumos',
            'manage-providers',
            'manage-invoices',
            'manage-requests'
        ];
        foreach ($permissions as $name) {
            Permission::firstOrCreate(
                ['name' => $name],
                ['display_name' => ucfirst(str_replace('-', ' ', $name))]
            );
            $this->line("Permiso: {$name}");
        }
    }
    private function createRoles()
    {
        $this->info('Creando roles...');
        $roles = [
            'admin' => 'Administrador',
            'user' => 'Usuario'
        ];
        foreach ($roles as $name => $displayName) {
            Role::firstOrCreate(
                ['name' => $name],
                ['display_name' => $displayName]
            );
            $this->line("Rol: {$name}");
        }
    }
    private function assignPermissionsToRoles()
    {
        $this->info('Asignando permisos a roles...');
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $allPermissions = Permission::all();
            foreach ($allPermissions as $permission) {
                if (!$adminRole->hasPermissionTo($permission->name)) {
                    $adminRole->givePermissionTo($permission);
                }
            }
        }
        $userRole = Role::where('name', 'user')->first();
        if ($userRole) {
            $basicPermissions = ['manage-insumos'];
            foreach ($basicPermissions as $permissionName) {
                $permission = Permission::where('name', $permissionName)->first();
                if ($permission && !$userRole->hasPermissionTo($permissionName)) {
                    $userRole->givePermissionTo($permission);
                }
            }
        }
    }
    private function assignRolesToUsers()
    {
        $this->info('Asignando roles a usuarios...');
        $users = User::all();
        foreach ($users as $user) {
            if ($user->id == 1 || $user->correo == 'admin@ucsc.cl') {
                $adminRole = Role::where('name', 'admin')->first();
                if ($adminRole && !$user->hasRole('admin')) {
                    $user->assignRole($adminRole);
                    $this->line("Rol admin asignado a {$user->nombre}");
                }
            } else {
                $userRole = Role::where('name', 'user')->first();
                if ($userRole && !$user->hasRole('user')) {
                    $user->assignRole($userRole);
                    $this->line("Rol user asignado a {$user->nombre}");
                }
            }
        }
    }
}