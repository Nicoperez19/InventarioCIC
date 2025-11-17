<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;

class ManageAdminPermissions extends Command
{
    protected $signature = 'permissions:admin {action} {permission?}';
    protected $description = 'Gestionar permisos del administrador (add|remove|list|all|none)';

    public function handle()
    {
        $admin = User::where('run', '11111111-1')
            ->orWhere('correo', 'admin@empresa.com')
            ->first();

        if (!$admin) {
            $this->error('Usuario administrador no encontrado');
            return Command::FAILURE;
        }

        $action = $this->argument('action');
        $permissionName = $this->argument('permission');

        switch ($action) {
            case 'add':
                if (!$permissionName) {
                    $this->error('Debes especificar el nombre del permiso');
                    return Command::FAILURE;
                }
                $permission = Permission::where('name', $permissionName)->first();
                if (!$permission) {
                    $this->error("Permiso '{$permissionName}' no encontrado");
                    return Command::FAILURE;
                }
                $admin->givePermissionTo($permission);
                $this->info("✓ Permiso '{$permissionName}' agregado al administrador");
                break;

            case 'remove':
                if (!$permissionName) {
                    $this->error('Debes especificar el nombre del permiso');
                    return Command::FAILURE;
                }
                $permission = Permission::where('name', $permissionName)->first();
                if (!$permission) {
                    $this->error("Permiso '{$permissionName}' no encontrado");
                    return Command::FAILURE;
                }
                $admin->revokePermissionTo($permission);
                $this->info("✓ Permiso '{$permissionName}' removido del administrador");
                break;

            case 'list':
                $permissions = $admin->permissions;
                $this->info("Permisos del administrador ({$admin->nombre}):");
                if ($permissions->count() === 0) {
                    $this->warn('  No tiene permisos asignados');
                } else {
                    foreach ($permissions as $permission) {
                        $this->line("  ✓ {$permission->name}");
                    }
                }
                break;

            case 'all':
                $allPermissions = Permission::all();
                $admin->syncPermissions($allPermissions);
                $this->info("✓ Todos los permisos asignados al administrador");
                $this->info("Total: {$allPermissions->count()} permisos");
                break;

            case 'none':
                $admin->syncPermissions([]);
                $this->info("✓ Todos los permisos removidos del administrador");
                break;

            default:
                $this->error("Acción '{$action}' no válida. Usa: add, remove, list, all, none");
                return Command::FAILURE;
        }

        // Limpiar caché de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $admin->forgetCachedPermissions();
        \Illuminate\Support\Facades\Cache::forget("spatie.permission.cache.user.{$admin->run}");

        return Command::SUCCESS;
    }
}

