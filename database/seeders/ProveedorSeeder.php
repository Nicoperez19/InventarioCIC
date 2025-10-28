<?php
namespace Database\Seeders;
use App\Models\Proveedor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
class ProveedorSeeder extends Seeder
{
    public function run(): void
    {
        $proveedores = [
            [
                'rut' => '12345678-9',
                'nombre_proveedor' => 'Distribuidora ABC Ltda.',
                'telefono' => '+56 2 2345 6789'
            ],
            [
                'rut' => '87654321-0',
                'nombre_proveedor' => 'Suministros Industriales S.A.',
                'telefono' => '+56 2 3456 7890'
            ],
            [
                'rut' => '11223344-5',
                'nombre_proveedor' => 'Proveedor XYZ',
                'telefono' => '+56 2 4567 8901'
            ],
            [
                'rut' => '55667788-9',
                'nombre_proveedor' => 'Comercial Delta',
                'telefono' => '+56 2 5678 9012'
            ],
            [
                'rut' => '99887766-5',
                'nombre_proveedor' => 'Empresa Omega',
                'telefono' => '+56 2 6789 0123'
            ]
        ];
        foreach ($proveedores as $proveedor) {
            Proveedor::create($proveedor);
        }
    }
}
