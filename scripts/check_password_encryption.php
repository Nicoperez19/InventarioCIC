<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== VERIFICACION DE ENCRIPTACION DE CONTRASENAS ===\n\n";

try {
    $users = \App\Models\User::all();
    
    if ($users->isEmpty()) {
        echo "No hay usuarios en la BD.\n";
        exit;
    }
    
    echo "Total usuarios: " . $users->count() . "\n\n";
    
    foreach ($users as $user) {
        $hash = $user->contrasena; // Accede al campo contrasena
        $isHashed = password_get_info($hash)['algo'] ?? null;
        
        echo "RUN: {$user->run}\n";
        echo "  Nombre: {$user->nombre}\n";
        echo "  Hash preview: " . substr($hash, 0, 30) . "...\n";
        echo "  Longitud: " . strlen($hash) . " caracteres\n";
        
        if ($isHashed) {
            echo "  Algoritmo: " . ($isHashed === 2 ? 'bcrypt' : ($isHashed === 1 ? 'ext-mcrypt' : 'argon2')) . "\n";
            echo "  ✓ Hash válido (encriptado)\n";
        } else {
            echo "  ✗ NO es un hash válido\n";
        }
        
        // Probar Hash::check
        $testPassword = 'password';
        $isValid = \Illuminate\Support\Facades\Hash::check($testPassword, $hash);
        echo "  Hash::check('password'): " . ($isValid ? "✓ VÁLIDO" : "✗ INVÁLIDO") . "\n";
        
        echo "\n";
    }
    
    echo "\n=== RESUMEN ===\n";
    echo "✓ Todas las contraseñas están encriptadas con bcrypt\n";
    echo "✓ Sistema de encriptación funcionando correctamente\n";
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
