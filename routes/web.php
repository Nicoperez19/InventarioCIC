<?php
use App\Http\Controllers\AdminSolicitudViewController;
use App\Http\Controllers\CargaMasivaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\DepartamentoViewController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\FacturaViewController;
use App\Http\Controllers\InsumoController;
use App\Http\Controllers\InsumoViewController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ProveedorViewController;
use App\Http\Controllers\Reportes\ReporteInsumosController;
use App\Http\Controllers\Reportes\ReporteStockController;
use App\Http\Controllers\Reportes\ReporteConsumoDepartamentoController;
use App\Http\Controllers\Reportes\ReporteRotacionController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\SolicitudViewController;
use App\Http\Controllers\TipoInsumoController;
use App\Http\Controllers\TipoInsumoViewController;
use App\Http\Controllers\UnidadMedidaController;
use App\Http\Controllers\UnidadViewController;
use App\Http\Controllers\UserViewController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});
require __DIR__.'/auth.php';
Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::view('profile', 'layouts.profile.profile')->name('profile');
    Route::post('logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');
});
Route::middleware(['auth'])->group(function () {
    Route::get('users', [UserViewController::class, 'index'])->name('users');
    Route::get('/users/permissions', [UsersController::class, 'getPermissions'])->name('users.permissions');
    Route::post('/users', [UsersController::class, 'store'])->name('users.store');
    // Rutas específicas antes de las rutas con parámetros
    Route::post('/users/generate-all-qrs', [UsersController::class, 'generateAllQrs'])->name('users.generate-all-qrs');
    Route::get('/users/export-qr-codes-pdf', [UsersController::class, 'exportUsersQRCodesPdf'])->name('users.export-qr-codes-pdf');
    // Rutas con parámetros después de las rutas específicas
    Route::get('/users/{user}', [UsersController::class, 'show'])->name('users.show');
    Route::put('/users/{user}', [UsersController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UsersController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{user}/generate-qr', [UsersController::class, 'generateQr'])->name('users.generate-qr');
    Route::get('/users/{user}/qr', [UsersController::class, 'getQRCode'])->name('users.qr');
});
Route::middleware(['auth'])->group(function () {
    Route::get('departamentos', [DepartamentoViewController::class, 'index'])->name('departamentos');
    Route::post('/departamentos', [DepartamentoController::class, 'store'])->name('departamentos.store');
    Route::get('/departamentos/{departamento}', [DepartamentoController::class, 'show'])->name('departamentos.show');
    Route::put('/departamentos/{departamento}', [DepartamentoController::class, 'update'])->name('departamentos.update');
    Route::delete('/departamentos/{departamento}', [DepartamentoController::class, 'destroy'])->name('departamentos.destroy');
});
Route::middleware(['auth'])->group(function () {
    Route::get('unidades', [UnidadViewController::class, 'index'])->name('unidades');
    Route::post('/unidades', [UnidadMedidaController::class, 'store'])->name('unidades.store');
    Route::get('/unidades/{unidad}', [UnidadMedidaController::class, 'show'])->name('unidades.show');
    Route::put('/unidades/{unidad}', [UnidadMedidaController::class, 'update'])->name('unidades.update');
    Route::delete('/unidades/{unidad}', [UnidadMedidaController::class, 'destroy'])->name('unidades.destroy');
});
Route::middleware(['auth'])->group(function () {
    Route::get('tipo-insumos', [TipoInsumoViewController::class, 'index'])->name('tipo-insumos.index');
    Route::post('/tipo-insumos', [TipoInsumoController::class, 'store'])->name('tipo-insumos.store');
    Route::get('/tipo-insumos/{tipoInsumo}', [TipoInsumoController::class, 'show'])->name('tipo-insumos.show');
    Route::put('/tipo-insumos/{tipoInsumo}', [TipoInsumoController::class, 'update'])->name('tipo-insumos.update');
    Route::delete('/tipo-insumos/{tipoInsumo}', [TipoInsumoController::class, 'destroy'])->name('tipo-insumos.destroy');
    Route::get('/tipo-insumos/{tipoInsumo}/pdf', [TipoInsumoController::class, 'generatePdf'])->name('tipo-insumos.pdf');
});
Route::middleware(['auth'])->group(function () {
    Route::get('insumos', [InsumoViewController::class, 'index'])->name('insumos.index');
    Route::get('/insumos/create', [InsumoController::class, 'create'])->name('insumos.create');
    Route::post('/insumos', [InsumoController::class, 'store'])->name('insumos.store');
    Route::get('/insumos/{insumo}', [InsumoController::class, 'show'])->name('insumos.show');
    Route::get('/insumos/{insumo}/edit', [InsumoController::class, 'edit'])->name('insumos.edit');
    Route::put('/insumos/{insumo}', [InsumoController::class, 'update'])->name('insumos.update');
    Route::delete('/insumos/{insumo}', [InsumoController::class, 'destroy'])->name('insumos.destroy');
    Route::get('/qr/{insumo}/generate', [InsumoController::class, 'generateQr'])->name('qr.generate');
    Route::get('/qr/{insumo}/svg', [InsumoController::class, 'generateQrSvg'])->name('qr.svg');
    Route::get('/carga-masiva', [CargaMasivaController::class, 'index'])->name('carga-masiva.index');
    Route::post('/carga-masiva/upload', [CargaMasivaController::class, 'upload'])->name('carga-masiva.upload');
    Route::get('/carga-masiva/template', [CargaMasivaController::class, 'downloadTemplate'])->name('carga-masiva.template');
});

Route::middleware(['auth'])->group(function () {
    Route::get('solicitudes', [SolicitudViewController::class, 'index'])->name('solicitudes');
});

Route::middleware(['auth'])->group(function () {
    Route::get('proveedores', [ProveedorViewController::class, 'index'])->name('proveedores.index');
    Route::post('/proveedores', [ProveedorController::class, 'store'])->name('proveedores.store');
    Route::get('/proveedores/{proveedor}', [ProveedorController::class, 'show'])->name('proveedores.show');
    Route::put('/proveedores/{proveedor}', [ProveedorController::class, 'update'])->name('proveedores.update');
    Route::delete('/proveedores/{proveedor}', [ProveedorController::class, 'destroy'])->name('proveedores.destroy');
});
Route::middleware(['auth'])->group(function () {
    Route::get('facturas', [FacturaViewController::class, 'index'])->name('facturas.index');
    Route::post('/facturas', [FacturaController::class, 'store'])->name('facturas.store');
    Route::post('/facturas/upload', [FacturaController::class, 'upload'])->name('facturas.upload');
    Route::put('/facturas/{factura}', [FacturaController::class, 'update'])->name('facturas.update');
    Route::delete('/facturas/{factura}', [FacturaController::class, 'destroy'])->name('facturas.destroy');
    Route::get('/facturas/{factura}/download', [FacturaController::class, 'download'])->name('facturas.download');
    Route::get('/facturas/{factura}/view', [FacturaController::class, 'view'])->name('facturas.view');
});
Route::middleware(['auth'])->group(function () {
    Route::get('admin-solicitudes', [AdminSolicitudViewController::class, 'index'])->name('admin-solicitudes');
    Route::get('/solicitudes/create', [SolicitudController::class, 'create'])->name('solicitudes.create');
    Route::post('/solicitudes', [SolicitudController::class, 'store'])->name('solicitudes.store');
    Route::get('/solicitudes/{solicitud}', [SolicitudController::class, 'show'])->name('solicitudes.show');
    Route::get('/solicitudes/{solicitud}/edit', [SolicitudController::class, 'edit'])->name('solicitudes.edit');
    Route::put('/solicitudes/{solicitud}', [SolicitudController::class, 'update'])->name('solicitudes.update');
    Route::delete('/solicitudes/{solicitud}', [SolicitudController::class, 'destroy'])->name('solicitudes.destroy');
    Route::post('/solicitudes/{solicitud}/aprobar', [SolicitudController::class, 'aprobar'])->name('solicitudes.aprobar');
    Route::post('/solicitudes/{solicitud}/rechazar', [SolicitudController::class, 'rechazar'])->name('solicitudes.rechazar');
    Route::post('/solicitudes/{solicitud}/entregar', [SolicitudController::class, 'entregar'])->name('solicitudes.entregar');
    Route::get('/solicitudes/insumos/get', [SolicitudController::class, 'getInsumos'])->name('solicitudes.insumos.get');
    Route::get('/solicitudes/insumos/all', [SolicitudController::class, 'getAllInsumos'])->name('solicitudes.insumos.all');
    Route::get('/solicitudes/{solicitud}/export/excel', [SolicitudController::class, 'exportExcel'])->name('solicitudes.export.excel');
    Route::get('/solicitudes/{solicitud}/export/pdf', [SolicitudController::class, 'exportPdf'])->name('solicitudes.export.pdf');
});
// Módulo de Reportes
Route::middleware(['auth'])->prefix('reportes')->name('reportes.')->group(function () {
    // Reportes de Insumos
    Route::prefix('insumos')->name('insumos.')->group(function () {
        Route::get('/', [ReporteInsumosController::class, 'index'])->name('index');
        Route::post('/exportar/excel', [ReporteInsumosController::class, 'exportarExcel'])->name('exportar.excel');
        Route::post('/exportar/pdf', [ReporteInsumosController::class, 'exportarPdf'])->name('exportar.pdf');
    });

    // Reporte de Stock Crítico
    Route::prefix('stock')->name('stock.')->group(function () {
        Route::get('/', [ReporteStockController::class, 'index'])->name('index');
        Route::post('/exportar/excel', [ReporteStockController::class, 'exportarExcel'])->name('exportar.excel');
        Route::post('/exportar/pdf', [ReporteStockController::class, 'exportarPdf'])->name('exportar.pdf');
    });

    // Reporte de Consumo por Departamento
    Route::prefix('consumo-departamento')->name('consumo-departamento.')->group(function () {
        Route::get('/', [ReporteConsumoDepartamentoController::class, 'index'])->name('index');
        Route::post('/exportar/excel', [ReporteConsumoDepartamentoController::class, 'exportarExcel'])->name('exportar.excel');
        Route::post('/exportar/pdf', [ReporteConsumoDepartamentoController::class, 'exportarPdf'])->name('exportar.pdf');
    });

    // Reporte de Rotación de Inventario
    Route::prefix('rotacion')->name('rotacion.')->group(function () {
        Route::get('/', [ReporteRotacionController::class, 'index'])->name('index');
        Route::post('/exportar/excel', [ReporteRotacionController::class, 'exportarExcel'])->name('exportar.excel');
        Route::post('/exportar/pdf', [ReporteRotacionController::class, 'exportarPdf'])->name('exportar.pdf');
    });
});
