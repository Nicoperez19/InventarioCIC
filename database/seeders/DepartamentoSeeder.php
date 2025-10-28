<?php
namespace Database\Seeders;
use App\Models\Departamento;
use Illuminate\Database\Seeder;
class DepartamentoSeeder extends Seeder
{
    public function run(): void
    {
        $departamentos = [
            [
                'id_depto' => 'CIC_leng',
                'nombre_depto' => 'Lenguaje',
            ],
            [
                'id_depto' => 'CIC_mate',
                'nombre_depto' => 'Matemática',
            ],
            [
                'id_depto' => 'CIC_admin',
                'nombre_depto' => 'Administración',
            ],
            [
                'id_depto' => 'CIC_ingl',
                'nombre_depto' => 'Inglés',
            ],
            [
                'id_depto' => 'CIC_cien',
                'nombre_depto' => 'Ciencias',
            ],
            [
                'id_depto' => 'CIC_hist',
                'nombre_depto' => 'Historia',
            ],
            [
                'id_depto' => 'CIC_edfi',
                'nombre_depto' => 'Educación Física',
            ],
            [
                'id_depto' => 'CIC_parv',
                'nombre_depto' => 'Párvulo',
            ],
            [
                'id_depto' => 'CIC_info',
                'nombre_depto' => 'Informática',
            ],
            [
                'id_depto' => 'CIC_ofic',
                'nombre_depto' => 'Oficina',
            ],
        ];
        foreach ($departamentos as $departamento) {
            Departamento::create($departamento);
        }
    }
}
