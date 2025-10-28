<?php
namespace Database\Seeders;
use App\Models\UnidadMedida;
use Illuminate\Database\Seeder;
class UnidadSeeder extends Seeder
{
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
            UnidadMedida::create([
                'id_unidad' => 'U'.str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'nombre_unidad_medida' => $nombre,
            ]);
        }
    }
}
