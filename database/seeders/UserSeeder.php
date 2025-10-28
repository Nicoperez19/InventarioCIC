<?php
namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'run' => '12345678-9',
            'nombre' => 'Admin',
            'correo' => 'admin@ucsc.cl',
            'contrasena' => bcrypt('password'),
            'id_depto' => 'CIC_admin', // Departamento de Administración
        ]);
        $worker = User::create([
            'run' => '98765432-1',
            'nombre' => 'Worker',
            'correo' => 'worker@ucsc.cl',
            'contrasena' => bcrypt('password'),
            'id_depto' => 'CIC_info', // Departamento de Informática
        ]);
        $adminRole = Role::firstOrCreate(['name' => 'Administrador']);
        $userRole = Role::firstOrCreate(['name' => 'Usuario']);
        $admin->assignRole($adminRole);
        $worker->assignRole($userRole);
    }
}
