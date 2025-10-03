<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $roleAdmin = Role::firstOrCreate(['name' => 'Administrador']);
        $roleUsuario = Role::firstOrCreate(['name' => 'Usuario']);
        
        $permission1 = Permission::firstOrCreate(['name' => 'dashboard']);
        $permission2 = Permission::firstOrCreate(['name' => 'mantenedor de roles']);
        $permission3 = Permission::firstOrCreate(['name' => 'mantenedor de permisos']);
        
        $roleAdmin->syncPermissions([
            $permission1, $permission2, $permission3,
        ]);

        $roleUsuario->syncPermissions([
            $permission1, $permission2, $permission3,
        ]);
    }
}
