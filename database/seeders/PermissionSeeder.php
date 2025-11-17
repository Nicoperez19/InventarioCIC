<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        try {
            DB::statement('ALTER TABLE model_has_roles MODIFY COLUMN model_id VARCHAR(255)');
            DB::statement('ALTER TABLE model_has_permissions MODIFY COLUMN model_id VARCHAR(255)');
        } catch (\Throwable $e) {
            // Ignorar errores si las columnas ya están modificadas
        }

        // Crear todos los permisos en español (basados en los can: de las rutas)
        $permissions = [
            // Permisos principales (los que están en los can: de las rutas)
            'administrar-usuarios',
            'administrar-departamentos',
            'administrar-unidades',
            'administrar-tipo-insumos',
            'administrar-insumos',
            'solicitar-insumos',
            'administrar-roles',
            'administrar-proveedores',
            'administrar-facturas',
            'administrar-solicitudes',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name]);
        }

    }
}


