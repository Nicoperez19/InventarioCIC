<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure spatie pivot tables support string model_id for User primary key (run)
        try {
            DB::statement('ALTER TABLE model_has_roles MODIFY COLUMN model_id VARCHAR(255)');
            DB::statement('ALTER TABLE model_has_permissions MODIFY COLUMN model_id VARCHAR(255)');
        } catch (\Throwable $e) {
            // ignore if already altered
        }

        $roleAdmin = Role::firstOrCreate(['name' => 'Administrador']);
        $roleUsuario = Role::firstOrCreate(['name' => 'Usuario']);

        $permissions = [
            // Users
            'manage-users', 'create-users', 'edit-users', 'delete-users', 'view-users',
            // Products/Inventory
            'manage-inventory', 'create-products', 'edit-products', 'delete-products', 'view-products',
            // Requests
            'manage-requests', 'view-requests', 'create-requests', 'approve-requests', 'reject-requests', 'deliver-requests',
            'view-pending-requests',
            // Departments
            'manage-departments', 'create-departments', 'edit-departments', 'delete-departments', 'view-departments',
            // Units
            'manage-units', 'create-units', 'edit-units', 'delete-units', 'view-units',
            // Tipo Insumos
            'manage-tipo-insumos', 'create-tipo-insumos', 'edit-tipo-insumos', 'delete-tipo-insumos', 'view-tipo-insumos',
            // Insumos
            'manage-insumos', 'create-insumos', 'edit-insumos', 'delete-insumos', 'view-insumos',
            // Carga Masiva
            'manage-bulk-upload', 'create-bulk-upload', 'view-bulk-upload',
            // Providers
            'manage-providers', 'create-providers', 'edit-providers', 'delete-providers', 'view-providers',
            // Invoices
            'manage-invoices', 'create-invoices', 'edit-invoices', 'delete-invoices', 'view-invoices', 'download-invoices',
            // Roles & permissions
            'manage-roles', 'create-roles', 'edit-roles', 'delete-roles', 'view-roles',
            'manage-permissions', 'create-permissions', 'edit-permissions', 'delete-permissions', 'view-permissions',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name]);
        }

        $roleAdmin->syncPermissions(Permission::all());

        // Minimal permissions for standard user
        $roleUsuario->syncPermissions([
            Permission::firstOrCreate(['name' => 'view-products']),
            Permission::firstOrCreate(['name' => 'view-requests']),
            Permission::firstOrCreate(['name' => 'view-providers']),
            Permission::firstOrCreate(['name' => 'view-invoices']),
        ]);
    }
}
