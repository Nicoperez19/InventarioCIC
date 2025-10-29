<?php
namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Crear usuarios principales (asumiendo que los departamentos ya existen)
        $admin = User::firstOrCreate(
            ['run' => '11111111-1'],
            [
                'nombre' => 'Administrador',
                'correo' => 'admin@empresa.com',
                'contrasena' => Hash::make('password123'),
                'id_depto' => 'CIC_admin' // Usar departamento existente
            ]
        );

        $jefe = User::firstOrCreate(
            ['run' => '12345678-9'],
            [
                'nombre' => 'Jefe Departamento',
                'correo' => 'jefe@empresa.com',
                'contrasena' => Hash::make('password123'),
                'id_depto' => 'CIC_info' // Usar departamento existente
            ]
        );

        $auxiliar = User::firstOrCreate(
            ['run' => '87654321-0'],
            [
                'nombre' => 'Auxiliar',
                'correo' => 'auxiliar@empresa.com',
                'contrasena' => Hash::make('password123'),
                'id_depto' => 'CIC_ofic' // Usar departamento existente
            ]
        );

      
        $worker = User::firstOrCreate(
            ['run' => '98765432-1'],
            [
                'nombre' => 'Worker',
                'correo' => 'worker@ucsc.cl',
                'contrasena' => Hash::make('password'),
                'id_depto' => 'CIC_info'
            ]
        );


    }
}
