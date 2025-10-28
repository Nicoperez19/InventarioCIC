<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class AssignPermissionsCommand extends Command
{
    protected $signature = 'permissions:assign {--user-id=1 : ID del usuario} {--role=admin : Rol a asignar}';
    protected $description = 'Asignar permisos a usuarios';
    public function handle()
    {
        $userId = $this->option('user-id');
        $roleName = $this->option('role');
        $this->info("Asignando permisos al usuario ID {$userId}...");
        try {
            $user = User::find($userId);
            if (!$user) {
                $this->error("Usuario ID {$userId} no encontrado");
                return Command::FAILURE;
            }
            $this->info("Usuario encontrado: {$user->nombre}");
            $role = Role::firstOrCreate(
                ['name' => $roleName],
                ['display_name' => ucfirst($roleName)]
            );
            if (!$user->hasRole($role->name)) {
                $user->assignRole($role);
                $this->line("Rol {$role->name} asignado al usuario");
            } else {
                $this->line("Usuario ya tiene el rol {$role->name}");
            }
            $permissions = [
                'manage-insumos',
                'manage-users',
                'manage-roles',
                'manage-departments',
                'manage-units'
            ];
            foreach ($permissions as $permissionName) {
                $permission = Permission::firstOrCreate(
                    ['name' => $permissionName],
                    ['display_name' => ucfirst(str_replace('-', ' ', $permissionName))]
                );
                if (!$role->hasPermissionTo($permissionName)) {
                    $role->givePermissionTo($permission);
                    $this->line("Permiso {$permissionName} asignado al rol");
                }
            }
            $this->info("Permisos asignados exitosamente");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Error asignando permisos: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}