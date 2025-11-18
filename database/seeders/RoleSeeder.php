<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear roles
        $roleAdmin = Role::firstOrCreate(['name' => 'Administrador']);
        $roleSupervisor = Role::firstOrCreate(['name' => 'Supervisor']);
        $roleUsuario = Role::firstOrCreate(['name' => 'Usuario']);

        // Crear permisos
        $permission1 = Permission::firstOrCreate(['name' => 'dashboard']);
        $permission2 = Permission::firstOrCreate(['name' => 'solicitudes']);
        $permission3 = Permission::firstOrCreate(['name' => 'mantenedor de usuarios']);
        $permission4 = Permission::firstOrCreate(['name' => 'mantenedor de departamentos']);
        $permission5 = Permission::firstOrCreate(['name' => 'mantenedor de unidades']);
        $permission6 = Permission::firstOrCreate(['name' => 'insumos']);
        $permission7 = Permission::firstOrCreate(['name' => 'mantenedor de tipos de insumo']);
        $permission8 = Permission::firstOrCreate(['name' => 'carga masiva']);
        $permission9 = Permission::firstOrCreate(['name' => 'mantenedor de proveedores']);
        $permission10 = Permission::firstOrCreate(['name' => 'mantenedor de facturas']);
        $permission11 = Permission::firstOrCreate(['name' => 'admin solicitudes']);
        $permission12 = Permission::firstOrCreate(['name' => 'reportes']);
        $permission13 = Permission::firstOrCreate(['name' => 'reportes insumos']);
        $permission14 = Permission::firstOrCreate(['name' => 'reportes stock']);
        $permission15 = Permission::firstOrCreate(['name' => 'reportes consumo departamento']);
        $permission16 = Permission::firstOrCreate(['name' => 'reportes rotacion']);
        $permission17 = Permission::firstOrCreate(['name' => 'notificaciones']);

        // Asignar todos los permisos al Administrador
        $roleAdmin->syncPermissions([
            $permission1, $permission2, $permission3, $permission4, $permission5,
            $permission6, $permission7, $permission8, $permission9, $permission10,
            $permission11, $permission12, $permission13, $permission14, $permission15,
            $permission16, $permission17
        ]);

        // Asignar permisos al Supervisor
        $roleSupervisor->syncPermissions([
            $permission1, $permission2, $permission6, $permission11, $permission12,
            $permission13, $permission14, $permission15, $permission16, $permission17
        ]);

        // Asignar permisos al Usuario
        $roleUsuario->syncPermissions([
            $permission1, $permission2, $permission17
        ]);
    }
}
