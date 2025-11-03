<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener roles
        $roleAdmin = Role::where('name', 'Administrador')->first();
        $jefeRole = Role::where('name', 'jefe-departamento')->first();
        $auxiliarRole = Role::where('name', 'auxiliar')->first();

        if (!$roleAdmin || !$jefeRole || !$auxiliarRole) {
            $this->command->error('Los roles no existen. Ejecuta primero RoleSeeder.');
            return;
        }

        // Asignar TODOS los permisos al Administrador
        $roleAdmin->syncPermissions(Permission::all());

        // Permisos específicos para Jefe Departamento
        $jefeRole->syncPermissions([
            Permission::where('name', 'solicitar-insumos')->first(),
            Permission::where('name', 'ver-solicitudes')->first(),
        ]);

        // Permisos específicos para Auxiliar
        $auxiliarRole->syncPermissions([
            Permission::where('name', 'solicitar-insumos')->first(),
        ]);

    }
}

