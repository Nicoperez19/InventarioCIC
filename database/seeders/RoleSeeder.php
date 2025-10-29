<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Crear roles principales
        $roleAdmin = Role::firstOrCreate(['name' => 'Administrador']);
        $jefeRole = Role::firstOrCreate(['name' => 'jefe-departamento']);
        $auxiliarRole = Role::firstOrCreate(['name' => 'auxiliar']);

        $this->command->info('Roles creados exitosamente');
        $this->command->info('Roles disponibles: Administrador, Jefe Departamento, Auxiliar');
    }
}
