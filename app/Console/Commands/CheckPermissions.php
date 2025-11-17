<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;

class CheckPermissions extends Command
{
    protected $signature = 'permissions:check';
    protected $description = 'Verificar permisos en la base de datos y usuarios';

    public function handle()
    {
        $this->info('=== PERMISOS EN LA BASE DE DATOS ===');
        $permissions = Permission::all();
        foreach ($permissions as $perm) {
            $this->line("  - {$perm->name}");
        }
        
        $this->info("\n=== PERMISOS ESPERADOS (de las rutas) ===");
        $expected = [
            'solicitar-insumos',
            'administrar-usuarios',
            'administrar-departamentos',
            'administrar-unidades',
            'administrar-tipo-insumos',
            'administrar-insumos',
            'administrar-roles',
            'administrar-proveedores',
            'administrar-facturas',
            'administrar-solicitudes',
        ];
        foreach ($expected as $perm) {
            $exists = Permission::where('name', $perm)->exists();
            $status = $exists ? '✓' : '✗';
            $this->line("  {$status} {$perm}");
        }
        
        $this->info("\n=== PERMISOS DEL ADMINISTRADOR ===");
        $admin = User::where('run', '11111111-1')->first();
        if ($admin) {
            $adminPerms = $admin->getAllPermissions();
            $this->info("Total: " . $adminPerms->count());
            foreach ($adminPerms as $perm) {
                $this->line("  - {$perm->name}");
            }
        } else {
            $this->error('Usuario administrador no encontrado');
        }
        
        return Command::SUCCESS;
    }
}

