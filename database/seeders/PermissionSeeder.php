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

        // Crear todos los permisos
        $permissions = [
            'manage-users', 'create-users', 'edit-users', 'delete-users', 'view-users',
            'manage-inventory', 'create-products', 'edit-products', 'delete-products', 'view-products',
            'manage-requests', 'view-requests', 'create-requests', 'approve-requests', 'reject-requests', 'deliver-requests',
            'view-pending-requests',
            'manage-departments', 'create-departments', 'edit-departments', 'delete-departments', 'view-departments',
            'manage-units', 'create-units', 'edit-units', 'delete-units', 'view-units',
            'manage-tipo-insumos', 'create-tipo-insumos', 'edit-tipo-insumos', 'delete-tipo-insumos', 'view-tipo-insumos',
            'manage-insumos', 'create-insumos', 'edit-insumos', 'delete-insumos', 'view-insumos',
            'manage-bulk-upload', 'create-bulk-upload', 'view-bulk-upload',
            'manage-providers', 'create-providers', 'edit-providers', 'delete-providers', 'view-providers',
            'manage-invoices', 'create-invoices', 'edit-invoices', 'delete-invoices', 'view-invoices', 'download-invoices',
            'manage-roles', 'create-roles', 'edit-roles', 'delete-roles', 'view-roles',
            'manage-permissions', 'create-permissions', 'edit-permissions', 'delete-permissions', 'view-permissions',
            'solicitar-insumos', 'ver-solicitudes',
            'receive-notifications', 'view-notifications',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name]);
        }

    }
}


