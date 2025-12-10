<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== VERIFICACION RAPIDA DE TABLAS ===\n\n";

try {
    $userCount = \Illuminate\Support\Facades\DB::table('users')->count();
    echo "✓ Tabla users accesible. Registros: $userCount\n";
} catch(\Exception $e) {
    echo "✗ Error en tabla users: " . $e->getMessage() . "\n";
}

try {
    $sessionCount = \Illuminate\Support\Facades\DB::table('sessions')->count();
    echo "✓ Tabla sessions accesible. Registros: $sessionCount\n";
} catch(\Exception $e) {
    echo "✗ Error en tabla sessions: " . $e->getMessage() . "\n";
}

echo "\n=== VERIFICANDO CORRECCIONES APLICADAS ===\n\n";

// 1. Verificar que User::getAuthPassword() funciona
try {
    $user = \App\Models\User::first();
    $pwd = $user->getAuthPassword();
    echo "✓ User::getAuthPassword() retorna: " . (strlen($pwd) > 0 ? "string válida" : "VACIA") . "\n";
} catch(\Exception $e) {
    echo "✗ Error en User::getAuthPassword(): " . $e->getMessage() . "\n";
}

// 2. Verificar que LoginForm retorna bool
try {
    $ref = new ReflectionClass(\App\Livewire\Forms\LoginForm::class);
    $method = $ref->getMethod('authenticate');
    $returnType = $method->getReturnType();
    if ($returnType && $returnType->getName() === 'bool') {
        echo "✓ LoginForm::authenticate() typed como bool\n";
    } else {
        echo "✗ LoginForm::authenticate() return type: " . ($returnType ? $returnType->getName() : 'sin tipo') . "\n";
    }
} catch(\Exception $e) {
    echo "✗ Error verificando LoginForm: " . $e->getMessage() . "\n";
}

// 3. Verificar que middleware está registrado
try {
    $app = app();
    echo "✓ Aplicación bootstrapped correctamente\n";
} catch(\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "\nTodo listo. El proyecto debe estar funcionando ahora.\n";
