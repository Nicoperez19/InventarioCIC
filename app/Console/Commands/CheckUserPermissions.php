<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CheckUserPermissions extends Command
{
    protected $signature = 'user:check-permissions {run=11111111-1}';
    protected $description = 'Verificar permisos de un usuario';

    public function handle()
    {
        $run = $this->argument('run');
        $user = User::where('run', $run)->first();
        
        if (!$user) {
            $this->error("Usuario no encontrado: {$run}");
            return Command::FAILURE;
        }
        
        $this->info("Usuario: {$user->nombre} ({$user->run})");
        $this->newLine();
        
        // Roles
        $roles = $user->roles->pluck('name')->toArray();
        $this->info("Roles: " . (empty($roles) ? 'Ninguno' : implode(', ', $roles)));
        
        // Permisos directos
        $directPermissions = $user->permissions->pluck('name')->toArray();
        $this->info("Permisos directos: " . (empty($directPermissions) ? 'Ninguno' : implode(', ', $directPermissions)));
        
        // Todos los permisos (directos + de roles)
        $allPermissions = $user->getAllPermissions()->pluck('name')->toArray();
        $this->info("Todos los permisos (directos + roles): " . (empty($allPermissions) ? 'Ninguno' : implode(', ', $allPermissions)));
        
        $this->newLine();
        $this->info("Total permisos: " . count($allPermissions));
        
        // Verificar permisos específicos
        $testPermissions = ['dashboard', 'solicitudes', 'mantenedor de usuarios'];
        $this->newLine();
        $this->info("Verificando permisos específicos:");
        foreach ($testPermissions as $perm) {
            $has = $user->can($perm);
            $this->line("  - {$perm}: " . ($has ? "✓ SÍ" : "✗ NO"));
        }
        
        return Command::SUCCESS;
    }
}

