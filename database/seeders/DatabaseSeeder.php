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
        
        // 2. Crear sistema de permisos y roles
        $this->call(RoleSeeder::class);
        
        // 3. Crear usuarios (los roles se asignan en UserSeeder)
        $this->call(UserSeeder::class);
    }
}
