<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;

class AssignAdminPermissions extends Command
{
    protected $signature = 'permissions:assign-admin';
    protected $description = 'Asignar todos los permisos en español al usuario administrador';

    public function handle()
    {
        $admin = User::where('run', '11111111-1')
            ->orWhere('correo', 'admin@empresa.com')
            ->first();

        if (!$admin) {
            $this->error('Usuario administrador no encontrado');
            return Command::FAILURE;
        }

        $allPermissions = Permission::all();
        $admin->syncPermissions($allPermissions);

        $this->info("Permisos asignados al administrador: {$admin->nombre} ({$admin->run})");
        $this->info("Total de permisos: " . $allPermissions->count());
        
        foreach ($allPermissions as $permission) {
            $this->line("  - {$permission->name}");
        }

        return Command::SUCCESS;
    }
}

