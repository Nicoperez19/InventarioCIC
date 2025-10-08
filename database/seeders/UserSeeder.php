<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
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

        // Asignar roles después de crear los usuarios
        try {
            $admin->assignRole('Administrador');
            $worker->assignRole('Usuario');
        } catch (\Exception $e) {
            // Si hay error con los roles, continuar sin ellos
            \Log::warning('Error asignando roles: ' . $e->getMessage());
        }
    }
}
