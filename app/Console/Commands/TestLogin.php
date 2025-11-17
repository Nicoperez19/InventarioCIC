<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class TestLogin extends Command
{
    protected $signature = 'test:login {run} {password}';
    protected $description = 'Probar login con RUN y contraseña';

    public function handle()
    {
        $run = $this->argument('run');
        $password = $this->argument('password');
        
        $this->info("Probando login con RUN: {$run}");
        
        // Formatear RUN
        $formattedRun = \App\Helpers\RunFormatter::format($run);
        $this->info("RUN formateado: {$formattedRun}");
        
        // Buscar usuario
        $user = User::where('run', $formattedRun)->first();
        
        if (!$user) {
            $this->error("Usuario no encontrado con RUN: {$formattedRun}");
            return Command::FAILURE;
        }
        
        $this->info("Usuario encontrado: {$user->nombre} ({$user->correo})");
        $this->info("Contraseña en BD (primeros 20 chars): " . substr($user->contrasena, 0, 20) . "...");
        
        // Verificar contraseña
        $isValid = Hash::check($password, $user->contrasena);
        
        if ($isValid) {
            $this->info("✓ Contraseña válida");
            return Command::SUCCESS;
        } else {
            $this->error("✗ Contraseña inválida");
            $this->warn("Intentando con diferentes variaciones...");
            
            // Probar con diferentes variaciones
            $variations = [
                $password,
                trim($password),
                $password . "\n",
                "\n" . $password,
            ];
            
            foreach ($variations as $i => $variation) {
                if (Hash::check($variation, $user->contrasena)) {
                    $this->info("✓ Contraseña válida con variación #{$i}");
                    return Command::SUCCESS;
                }
            }
            
            return Command::FAILURE;
        }
    }
}

