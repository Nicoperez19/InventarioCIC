<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Unidad;

class UnidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unidades = [
            'Caja',
            'Unidad', 
            'Bolsa',
            'Display',
            'Bidon',
            'Botella',
            'Kilogramo'
        ];

        foreach ($unidades as $index => $nombre) {
            Unidad::create([
                'id_unidad' => 'U' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'nombre_unidad' => $nombre,
            ]);
        }
    }
}