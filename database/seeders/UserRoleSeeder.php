<?php
namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener roles
        $roleAdmin = Role::where('name', 'Administrador')->first();
        $jefeRole = Role::where('name', 'jefe-departamento')->first();
        $auxiliarRole = Role::where('name', 'auxiliar')->first();

        // Obtener todos los permisos en español
        $allPermissions = Permission::all();
    
        // Asignar roles a usuarios principales
        $admin = User::where('correo', 'admin@empresa.com')->orWhere('run', '11111111-1')->first();
        if ($admin) {
            $admin->assignRole($roleAdmin);
            // Asignar directamente todos los permisos en español al administrador
            $admin->syncPermissions($allPermissions);
        }

        $jefe = User::where('correo', 'jefe@empresa.com')->first();
        if ($jefe) {
            $jefe->assignRole($jefeRole);
        }

        $auxiliar = User::where('correo', 'auxiliar@empresa.com')->first();
        if ($auxiliar) {
            $auxiliar->assignRole($auxiliarRole);
        }

        // Asignar roles a usuarios del sistema anterior
        $adminOld = User::where('correo', 'admin@ucsc.cl')->first();
        if ($adminOld) {
            $adminOld->assignRole($roleAdmin);
            // Asignar directamente todos los permisos en español
            $adminOld->syncPermissions($allPermissions);
        }

        $worker = User::where('correo', 'worker@ucsc.cl')->first();
        if ($worker) {
            // Asignar rol de auxiliar al worker
            $worker->assignRole($auxiliarRole);
        }

    }
}



