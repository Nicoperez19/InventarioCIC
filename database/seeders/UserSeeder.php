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
        User::create([
            'name' => 'Admin',
            'email' => 'admin@ucsc.cl',
            'password' => bcrypt('password'),
        ])->assignRole('Administrador');

        User::create([
            'name' => 'Worker',
            'email' => 'worker@ucsc.cl',
            'password' => bcrypt('password'),
        ])->assignRole('Usuario');
    }
}
