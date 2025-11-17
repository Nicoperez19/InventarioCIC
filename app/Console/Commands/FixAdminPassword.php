<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class FixAdminPassword extends Command
{
    protected $signature = 'user:fix-admin-password';
    protected $description = 'Verificar y corregir la contraseña del usuario administrador';

    public function handle()
    {
        $admin = User::where('run', '11111111-1')->first();

        if (!$admin) {
            $this->error('Usuario administrador no encontrado');
            return Command::FAILURE;
        }

        $this->info("Usuario encontrado: {$admin->nombre} ({$admin->run})");
        
        // Verificar si la contraseña actual funciona
        $password = 'password123';
        $isValid = Hash::check($password, $admin->contrasena);
        
        $this->info("Verificando contraseña 'password123'...");
        
        if ($isValid) {
            $this->info("✓ La contraseña es válida");
        } else {
            $this->warn("✗ La contraseña NO es válida. Actualizando...");
            
            // Actualizar la contraseña directamente en la base de datos sin pasar por el mutator
            \DB::table('users')
                ->where('run', $admin->run)
                ->update(['contrasena' => Hash::make($password)]);
            
            $this->info("✓ Contraseña actualizada correctamente");
            
            // Verificar nuevamente
            $admin->refresh();
            $isValid = Hash::check($password, $admin->contrasena);
            
            if ($isValid) {
                $this->info("✓ Verificación exitosa después de actualizar");
            } else {
                $this->error("✗ Error: La contraseña aún no es válida después de actualizar");
                return Command::FAILURE;
            }
        }

        return Command::SUCCESS;
    }
}

