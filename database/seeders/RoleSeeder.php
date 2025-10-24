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
            'create-users', 'edit-users', 'delete-users', 'view-users',
            // Inventory / Products
            'create-products', 'edit-products', 'delete-products', 'view-products',
            'view-inventory', 'create-inventory', 'edit-inventory', 'delete-inventory', 'apply-inventory',
            'apply-all-inventory', 'view-inventory-discrepancies',
            // Requests
            'view-requests', 'create-requests', 'approve-requests', 'reject-requests', 'deliver-requests',
            'view-pending-requests',
            // Departments
            'create-departments', 'edit-departments', 'delete-departments', 'view-departments',
            // Units
            'create-units', 'edit-units', 'delete-units', 'view-units',
            // Providers
            'create-providers', 'edit-providers', 'delete-providers', 'view-providers',
            // Invoices
            'create-invoices', 'edit-invoices', 'delete-invoices', 'view-invoices', 'download-invoices',
            // Roles & permissions
            'create-roles', 'edit-roles', 'delete-roles', 'view-roles',
            'create-permissions', 'edit-permissions', 'delete-permissions', 'view-permissions',
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
