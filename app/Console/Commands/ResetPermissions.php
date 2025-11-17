<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ResetPermissions extends Command
{
    protected $signature = 'permissions:reset {--force : Forzar eliminación sin confirmación}';
    protected $description = 'Eliminar TODOS los permisos y roles de la base de datos';

    public function handle()
    {
        if (!$this->option('force')) {
            $this->warn('⚠️  ADVERTENCIA: Esto eliminará TODOS los permisos y roles');
            
            if (!$this->confirm('¿Estás seguro de que quieres continuar?')) {
                $this->info('Operación cancelada');
                return Command::SUCCESS;
            }
        }

        $this->info('Eliminando permisos y roles...');

        try {
            // Eliminar todas las relaciones primero
            $this->info('Eliminando relaciones de permisos y roles...');
            DB::table('model_has_permissions')->truncate();
            DB::table('model_has_roles')->truncate();
            DB::table('role_has_permissions')->truncate();

            // Eliminar todos los permisos
            $this->info('Eliminando permisos...');
            $permissionsCount = Permission::count();
            Permission::query()->delete();
            $this->info("✓ Eliminados {$permissionsCount} permisos");

            // Eliminar todos los roles
            $this->info('Eliminando roles...');
            $rolesCount = Role::count();
            Role::query()->delete();
            $this->info("✓ Eliminados {$rolesCount} roles");

            // Limpiar todas las cachés
            $this->info('Limpiando cachés...');
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
            \Illuminate\Support\Facades\Cache::flush();
            $this->info('✓ Cachés limpiadas');

            $this->info("\n✅ ¡Todos los permisos y roles han sido eliminados!");
            $this->info('Ahora puedes ejecutar: php artisan db:seed --class=PermissionSeeder');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error al eliminar permisos y roles: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
