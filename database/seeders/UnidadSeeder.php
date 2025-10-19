<?php

namespace Database\Seeders;

use App\Models\Unidad;
use Illuminate\Database\Seeder;

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
            'Kilogramo',
        ];

        foreach ($unidades as $index => $nombre) {
            Unidad::create([
                'id_unidad' => 'U'.str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'nombre_unidad' => $nombre,
            ]);
        }
    }
}
