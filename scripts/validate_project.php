<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$issues = [];

// 1. Validar que rutas apunten a métodos existentes
echo "=== VALIDANDO RUTAS VS CONTROLADORES ===\n\n";

$routes = app('router')->getRoutes();
$controllerMissing = [];
$methodMissing = [];

foreach ($routes as $route) {
    $action = $route->getAction();
    if (isset($action['controller'])) {
        $controller = $action['controller'];
        if (strpos($controller, '@') !== false) {
            [$controllerClass, $method] = explode('@', $controller);
            
            try {
                $class = app($controllerClass);
                if (!method_exists($class, $method)) {
                    $methodMissing[] = "$controllerClass::$method (ruta: " . $route->getPath() . ")";
                    echo "✗ MÉTODO FALTA: $controllerClass::$method\n";
                }
            } catch (\Exception $e) {
                $controllerMissing[] = $controllerClass;
                echo "✗ CONTROLADOR NO ENCONTRADO: $controllerClass\n";
            }
        }
    }
}

// 2. Validar migraciones vs tablas existentes
echo "\n=== VALIDANDO MIGRACIONES VS TABLAS ===\n\n";

$tables = \Illuminate\Support\Facades\Schema::getTableListing();
$expectedTables = [
    'users',
    'departamentos',
    'tipos_insumo',
    'unidades_medida',
    'insumos',
    'facturas',
    'solicitudes',
    'solicitud_items',
    'proveedores',
    'notificacions',
    'sessions',
];

foreach ($expectedTables as $table) {
    if (in_array($table, $tables)) {
        echo "✓ Tabla existe: $table\n";
    } else {
        echo "✗ Tabla NO existe: $table\n";
        $issues[] = "Tabla $table no existe";
    }
}

// 3. Validar relaciones y foreign keys
echo "\n=== VALIDANDO FOREIGN KEYS ===\n\n";

$foreignKeyErrors = [];

// User -> Departamento
try {
    $user = \App\Models\User::whereNotNull('id_depto')->first();
    if ($user && method_exists($user, 'departamento')) {
        $dept = $user->departamento;
        if ($dept) {
            echo "✓ FK User->Departamento: OK\n";
        } else {
            echo "⚠ FK User->Departamento: Sin datos\n";
        }
    }
} catch (\Exception $e) {
    echo "✗ FK User->Departamento Error: " . $e->getMessage() . "\n";
    $foreignKeyErrors[] = "User->Departamento";
}

// Solicitud -> User
try {
    $solicitud = \App\Models\Solicitud::whereNotNull('run')->first();
    if ($solicitud && method_exists($solicitud, 'usuario')) {
        $user = $solicitud->usuario;
        if ($user) {
            echo "✓ FK Solicitud->User: OK\n";
        } else {
            echo "⚠ FK Solicitud->User: Sin datos\n";
        }
    }
} catch (\Exception $e) {
    echo "✗ FK Solicitud->User Error: " . $e->getMessage() . "\n";
    $foreignKeyErrors[] = "Solicitud->User";
}

// 4. Validar que campos tipo enum sean correctos
echo "\n=== VALIDANDO CAMPOS ENUM Y TIPOS ===\n\n";

// Checar que la tabla users tiene los campos correctos
$userColumns = \Illuminate\Support\Facades\DB::getSchemaBuilder()->getColumnListing('users');
$userExpected = ['run', 'nombre', 'correo', 'contrasena', 'id_depto', 'codigo_barra'];

foreach ($userExpected as $col) {
    if (in_array($col, $userColumns)) {
        echo "✓ users.$col existe\n";
    } else {
        echo "✗ users.$col NO existe\n";
        $issues[] = "users.$col column missing";
    }
}

// 5. Validar valores NULL en columnas críticas
echo "\n=== VALIDANDO INTEGRIDAD DE DATOS ===\n\n";

$userCount = \App\Models\User::count();
$userNull = \App\Models\User::whereNull('run')->count();

echo "Total usuarios: $userCount\n";
echo "Usuarios con run NULL: $userNull\n";

if ($userNull > 0) {
    echo "✗ ERROR: Existen usuarios sin RUN\n";
    $issues[] = "Usuarios con run NULL encontrados";
} else {
    echo "✓ Todos los usuarios tienen RUN\n";
}

// 6. Validar que los helpers existen
echo "\n=== VALIDANDO HELPERS Y SERVICIOS ===\n\n";

$helpers = [
    'App\Helpers\RunFormatter' => 'format',
    'App\Services\QrService' => 'generate',
];

foreach ($helpers as $class => $method) {
    try {
        $reflection = new ReflectionClass($class);
        if ($reflection->hasMethod($method)) {
            echo "✓ $class::$method existe\n";
        } else {
            echo "✗ $class::$method NO existe\n";
            $issues[] = "$class::$method no existe";
        }
    } catch (\Exception $e) {
        echo "✗ $class no existe\n";
        $issues[] = "$class no existe";
    }
}

// 7. Resumen final
echo "\n=== RESUMEN FINAL ===\n";
echo "Controladores faltantes: " . count($controllerMissing) . "\n";
echo "Métodos faltantes: " . count($methodMissing) . "\n";
echo "FK errores: " . count($foreignKeyErrors) . "\n";
echo "Otros issues: " . count($issues) . "\n\n";

if (!empty($methodMissing)) {
    echo "Métodos de controlador faltantes:\n";
    foreach ($methodMissing as $m) {
        echo "  - $m\n";
    }
}

if (!empty($foreignKeyErrors)) {
    echo "\nFK con errores:\n";
    foreach ($foreignKeyErrors as $fk) {
        echo "  - $fk\n";
    }
}

if (!empty($issues)) {
    echo "\nOtros issues:\n";
    foreach ($issues as $issue) {
        echo "  - $issue\n";
    }
}
