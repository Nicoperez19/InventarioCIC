<?php
namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener roles
        $roleAdmin = Role::where('name', 'Administrador')->first();
        $jefeRole = Role::where('name', 'jefe-departamento')->first();
        $auxiliarRole = Role::where('name', 'auxiliar')->first();

    
        // Asignar roles a usuarios principales
        $admin = User::where('correo', 'admin@empresa.com')->first();
        if ($admin) {
            $admin->assignRole($roleAdmin);
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
        }

        $worker = User::where('correo', 'worker@ucsc.cl')->first();
        if ($worker) {
            // Asignar rol de auxiliar al worker
            $worker->assignRole($auxiliarRole);
        }

    }
}
