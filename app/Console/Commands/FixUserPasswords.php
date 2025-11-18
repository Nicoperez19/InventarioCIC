<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class FixUserPasswords extends Command
{
    protected $signature = 'user:fix-passwords {--password=password : Contraseña a usar para todos los usuarios}';
    protected $description = 'Verificar y corregir las contraseñas de todos los usuarios';

    public function handle()
    {
        $password = $this->option('password');
        $this->info("Verificando y corrigiendo contraseñas con: '{$password}'");
        $this->newLine();

        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->warn('No hay usuarios en la base de datos');
            return Command::FAILURE;
        }

        $fixed = 0;
        $valid = 0;

        foreach ($users as $user) {
            $this->line("Verificando usuario: {$user->nombre} ({$user->run})");
            
            // Verificar si la contraseña funciona
            $isValid = Hash::check($password, $user->contrasena);
            
            if ($isValid) {
                $this->info("  ✓ Contraseña válida");
                $valid++;
            } else {
                $this->warn("  ✗ Contraseña inválida. Corrigiendo...");
                
                // Actualizar directamente en la BD sin pasar por el mutator
                DB::table('users')
                    ->where('run', $user->run)
                    ->update(['contrasena' => Hash::make($password)]);
                
                // Verificar nuevamente
                $user->refresh();
                $isValid = Hash::check($password, $user->contrasena);
                
                if ($isValid) {
                    $this->info("  ✓ Contraseña corregida exitosamente");
                    $fixed++;
                } else {
                    $this->error("  ✗ Error: No se pudo corregir la contraseña");
                }
            }
            $this->newLine();
        }

        $this->newLine();
        $this->info("Resumen:");
        $this->info("  - Contraseñas válidas: {$valid}");
        $this->info("  - Contraseñas corregidas: {$fixed}");
        $this->info("  - Total usuarios: " . $users->count());

        return Command::SUCCESS;
    }
}

