<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear datos maestros básicos
        $this->call(DepartamentoSeeder::class);
        $this->call(UnidadSeeder::class);
        $this->call(ProveedorSeeder::class);
        
        // 2. Crear sistema de permisos y roles
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(RolePermissionSeeder::class);
        
        // 3. Crear usuarios
        $this->call(UserSeeder::class);
        
        // 4. Asignar roles a usuarios
        $this->call(UserRoleSeeder::class);
    }
}
