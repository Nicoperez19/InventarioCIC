<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Helpers\RunFormatter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class TestLoginCommand extends Command
{
    protected $signature = 'test:login-full {run} {password=password}';
    protected $description = 'Probar el proceso completo de login';

    public function handle()
    {
        $run = $this->argument('run');
        $password = $this->argument('password');

        $this->info("=== Prueba de Login Completo ===");
        $this->newLine();

        // Paso 1: Formatear RUN
        $formattedRun = RunFormatter::format($run);
        $this->info("1. RUN original: {$run}");
        $this->info("   RUN formateado: {$formattedRun}");
        $this->newLine();

        // Paso 2: Buscar usuario
        $user = User::where('run', $formattedRun)->first();
        
        if (!$user) {
            $this->error("2. ✗ Usuario NO encontrado con RUN: {$formattedRun}");
            $this->newLine();
            $this->warn("Usuarios disponibles en la BD:");
            User::all()->each(function($u) {
                $this->line("  - {$u->run} ({$u->nombre})");
            });
            return Command::FAILURE;
        }

        $this->info("2. ✓ Usuario encontrado: {$user->nombre}");
        $this->info("   RUN en BD: {$user->run}");
        $this->info("   Correo: {$user->correo}");
        $this->newLine();

        // Paso 3: Verificar contraseña
        $cleanPassword = trim($password);
        $passwordValid = Hash::check($cleanPassword, $user->contrasena);
        
        $this->info("3. Verificando contraseña:");
        $this->info("   Contraseña proporcionada: '{$password}'");
        $this->info("   Contraseña limpia: '{$cleanPassword}'");
        $this->info("   Hash preview: " . substr($user->contrasena, 0, 30) . "...");
        $this->info("   Resultado: " . ($passwordValid ? "✓ VÁLIDA" : "✗ INVÁLIDA"));
        $this->newLine();

        if (!$passwordValid) {
            $this->error("✗ La contraseña no es válida");
            $this->newLine();
            $this->warn("Intentando verificar con diferentes variantes:");
            
            // Probar diferentes variantes
            $variants = [
                $password,
                trim($password),
                $password . ' ',
                ' ' . $password,
                strtolower($password),
                strtoupper($password),
            ];
            
            foreach ($variants as $variant) {
                $result = Hash::check($variant, $user->contrasena);
                $this->line("  - '{$variant}': " . ($result ? "✓ VÁLIDA" : "✗ inválida"));
            }
            
            return Command::FAILURE;
        }

        // Paso 4: Intentar autenticar
        $this->info("4. Intentando autenticar...");
        try {
            Auth::login($user, false);
            $this->info("   ✓ Autenticación exitosa");
            $this->info("   Usuario autenticado: " . Auth::user()->nombre);
            Auth::logout();
        } catch (\Exception $e) {
            $this->error("   ✗ Error al autenticar: " . $e->getMessage());
            return Command::FAILURE;
        }

        $this->newLine();
        $this->info("=== ✓ Login completo exitoso ===");
        
        return Command::SUCCESS;
    }
}

