<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'run' => '11111111-1',
            'nombre' => 'Administrador',
            'correo' => 'admin@ucsc.cl',
            'contrasena' => 'password',
            'id_depto' => 'CIC_admin',
            'correo_verificado_at' => now(),
        ])->assignRole('Administrador');

        User::create([
            'run' => '12345678-9',
            'nombre' => 'Supervisor',
            'correo' => 'supervisor@ucsc.cl',
            'contrasena' => 'password',
            'id_depto' => 'CIC_info',
            'correo_verificado_at' => now(),
        ])->assignRole('Supervisor');

        User::create([
            'run' => '98765432-1',
            'nombre' => 'Usuario',
            'correo' => 'usuario@ucsc.cl',
            'contrasena' => 'password',
            'id_depto' => 'CIC_info',
            'correo_verificado_at' => now(),
        ])->assignRole('Usuario');
    }
}
