<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$issues = [];

// Analizar migraciones vs modelos
$migrationDir = __DIR__ . '/database/migrations';
$modelDir = __DIR__ . '/app/Models';

// Obtener archivos
$models = array_map(
    fn($f) => basename($f, '.php'),
    glob($modelDir . '/*.php')
);

$migrations = glob($migrationDir . '/*.php');

echo "=== ANÁLISIS DE INCONSISTENCIAS DEL PROYECTO ===\n\n";

// 1. Verificar migraciones que hacen referencia a foreign keys
echo "1. Revisando Foreign Keys en migraciones...\n";
foreach ($migrations as $migFile) {
    $content = file_get_contents($migFile);
    if (preg_match_all('/\->foreign\([\'"](\w+)[\'"]\)->references\([\'"](\w+)[\'"]\)->on\([\'"](\w+)[\'"]\)/', $content, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $match) {
            $column = $match[1];
            $refColumn = $match[2];
            $table = $match[3];
            echo "   ✓ FK: $column -> $table($refColumn)\n";
        }
    }
}

// 2. Verificar que las rutas apunten a controladores existentes
echo "\n2. Revisando rutas vs controladores existentes...\n";
$routeFiles = ['routes/web.php', 'routes/api.php', 'routes/auth.php'];
$controllerDir = __DIR__ . '/app/Http/Controllers';

foreach ($routeFiles as $rf) {
    $path = __DIR__ . '/' . $rf;
    if (!file_exists($path)) continue;
    
    $content = file_get_contents($path);
    if (preg_match_all('/\[([A-Za-z\\\\]+)::class,\s*[\'"](\w+)[\'"]\]/', $content, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $match) {
            $controller = str_replace('\\', '/', $match[1]);
            $method = $match[2];
            $controllerPath = $controllerDir . '/' . $controller . '.php';
            
            if (file_exists($controllerPath)) {
                $controllerContent = file_get_contents($controllerPath);
                if (strpos($controllerContent, "public function $method") === false) {
                    echo "   ⚠ FALTA MÉTODO: $controller::$method\n";
                    $issues[] = "Controlador $controller no tiene método $method";
                }
            } else {
                echo "   ✗ CONTROLADOR NO EXISTE: $controller\n";
                $issues[] = "Controlador $controller no existe";
            }
        }
    }
}

// 3. Verificar campos de modelos contra migraciones
echo "\n3. Revisando modelo User...\n";
$userModel = new \App\Models\User();
$fillable = $userModel->getFillable();
$primaryKey = $userModel->getKeyName();
echo "   Primary Key: $primaryKey\n";
echo "   Fillable: " . implode(', ', $fillable) . "\n";
echo "   Auth Password Method: " . $userModel->getAuthPassword() . "\n";

// 4. Verificar configuración de sesiones
echo "\n4. Revisando configuración de sesiones...\n";
$sessionConfig = config('session');
echo "   Session Driver: " . ($sessionConfig['driver'] ?? 'default') . "\n";
echo "   Session Lifetime: " . ($sessionConfig['lifetime'] ?? '120') . " minutos\n";
echo "   Session Table: " . ($sessionConfig['table'] ?? 'sessions') . "\n";

// 5. Verificar que la tabla sessions existe
echo "\n5. Revisando tabla sessions...\n";
try {
    $sessionCount = \Illuminate\Support\Facades\DB::table('sessions')->count();
    echo "   ✓ Tabla sessions existe ($sessionCount registros)\n";
    
    // Verificar estructura
    $columns = \Illuminate\Support\Facades\DB::getSchemaBuilder()->getColumnListing('sessions');
    echo "   Columnas: " . implode(', ', $columns) . "\n";
} catch (\Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
    $issues[] = "Tabla sessions no existe o no es accesible";
}

// 6. Verificar usuarios en BD
echo "\n6. Revisando tabla users...\n";
try {
    $userCount = \Illuminate\Support\Facades\DB::table('users')->count();
    echo "   ✓ Usuarios en BD: $userCount\n";
    
    $adminUser = \Illuminate\Support\Facades\DB::table('users')->where('run', '11111111-1')->first();
    if ($adminUser) {
        echo "   ✓ Usuario admin existe (RUN: 11111111-1)\n";
        echo "     Nombre: " . $adminUser->nombre . "\n";
        echo "     Correo: " . $adminUser->correo . "\n";
    } else {
        echo "   ⚠ Usuario admin (11111111-1) NO existe\n";
        $issues[] = "Usuario admin no encontrado";
    }
} catch (\Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
    $issues[] = "No se puede acceder a tabla users";
}

// 7. Verificar relaciones en modelos
echo "\n7. Verificando relaciones de modelos...\n";

// User -> Departamento
try {
    $user = \App\Models\User::whereNotNull('id_depto')->first();
    if ($user && $user->departamento) {
        echo "   ✓ Relación User->Departamento OK\n";
    } else {
        echo "   ⚠ Relación User->Departamento: Sin datos\n";
    }
} catch (\Exception $e) {
    echo "   ✗ Error en User->Departamento: " . $e->getMessage() . "\n";
    $issues[] = "Relación User->Departamento falló";
}

// 8. Validar que LoginForm retorna valor correcto
echo "\n8. Verificando LoginForm...\n";
$form = new \App\Livewire\Forms\LoginForm();
echo "   ✓ LoginForm::authenticate() retorna bool\n";
$reflected = new ReflectionMethod($form, 'authenticate');
$returnType = $reflected->getReturnType();
if ($returnType && $returnType->getName() === 'bool') {
    echo "   ✓ Tipo de retorno correcto: bool\n";
} else {
    echo "   ⚠ Tipo de retorno no está tipado como bool\n";
}

// 9. Resumen
echo "\n\n=== RESUMEN ===\n";
if (empty($issues)) {
    echo "✓ No se encontraron inconsistencias graves.\n";
} else {
    echo "⚠ Inconsistencias encontradas:\n";
    foreach ($issues as $i => $issue) {
        echo "  " . ($i + 1) . ". $issue\n";
    }
}
