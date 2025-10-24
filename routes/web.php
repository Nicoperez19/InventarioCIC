<?php

use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\CargaMasivaController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\InsumoController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\RoleController;
// use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\UnidadMedidaController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Ruta principal
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Rutas públicas
require __DIR__.'/auth.php';

// Rutas autenticadas
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::view('dashboard', 'layouts.dashboard.dashboard')->name('dashboard');
    Route::view('profile', 'layouts.profile.profile')->name('profile');

    // Logout
    Route::post('logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    })->name('logout');
});

// Rutas de gestión de usuarios
Route::middleware(['auth', 'can:manage-users'])->group(function () {
    Route::view('users', 'layouts.user.user_index')->name('users');
    Route::get('/users/create', [UsersController::class, 'create'])->name('users.create');
    Route::post('/users', [UsersController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UsersController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UsersController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UsersController::class, 'destroy'])->name('users.destroy');
});

// Rutas de departamentos
Route::middleware(['auth', 'can:manage-departments'])->group(function () {
    Route::view('departamentos', 'layouts.departamento.departamento_index')->name('departamentos');
    Route::get('/departamentos/create', [DepartamentoController::class, 'create'])->name('departamentos.create');
    Route::post('/departamentos', [DepartamentoController::class, 'store'])->name('departamentos.store');
    Route::get('/departamentos/{departamento}/edit', [DepartamentoController::class, 'edit'])->name('departamentos.edit');
    Route::put('/departamentos/{departamento}', [DepartamentoController::class, 'update'])->name('departamentos.update');
    Route::delete('/departamentos/{departamento}', [DepartamentoController::class, 'destroy'])->name('departamentos.destroy');
});

// Rutas de unidades
Route::middleware(['auth', 'can:manage-units'])->group(function () {
    Route::view('unidades', 'layouts.unidad.unidad_index')->name('unidades');
    Route::get('/unidades/create', [UnidadMedidaController::class, 'create'])->name('unidades.create');
    Route::post('/unidades', [UnidadMedidaController::class, 'store'])->name('unidades.store');
    Route::get('/unidades/{unidad}/edit', [UnidadMedidaController::class, 'edit'])->name('unidades.edit');
    Route::put('/unidades/{unidad}', [UnidadMedidaController::class, 'update'])->name('unidades.update');
    Route::delete('/unidades/{unidad}', [UnidadMedidaController::class, 'destroy'])->name('unidades.destroy');
});

// Rutas de productos (insumos)
Route::middleware(['auth', 'can:manage-inventory'])->group(function () {
    Route::view('productos', 'layouts.producto.producto_index')->name('productos');
    Route::get('/productos/create', [InsumoController::class, 'create'])->name('productos.create');
    Route::post('/productos', [InsumoController::class, 'store'])->name('productos.store');
    Route::get('/productos/{producto}/edit', [InsumoController::class, 'edit'])->name('productos.edit');
    Route::put('/productos/{producto}', [InsumoController::class, 'update'])->name('productos.update');
    Route::delete('/productos/{producto}', [InsumoController::class, 'destroy'])->name('productos.destroy');
    Route::post('/productos/{producto}/adjust-stock', [InsumoController::class, 'adjustStock'])->name('productos.adjust-stock');
});

// Rutas de inventario
Route::middleware(['auth', 'can:manage-inventory'])->group(function () {
    Route::get('/inventario', [InventarioController::class, 'index'])->name('inventario.index');
    Route::post('/inventario', [InventarioController::class, 'store'])->name('inventario.store');
    Route::put('/inventario/{inventario}', [InventarioController::class, 'update'])->name('inventario.update');
    Route::delete('/inventario/{inventario}', [InventarioController::class, 'destroy'])->name('inventario.destroy');
    Route::post('/inventario/{inventario}/apply', [InventarioController::class, 'apply'])->name('inventario.apply');
});

// Rutas de solicitudes (comentadas temporalmente)
// Route::middleware(['auth'])->group(function () {
//     Route::get('/solicitudes', [SolicitudController::class, 'index'])->name('solicitudes.index');
//     Route::get('/solicitudes/create', [SolicitudController::class, 'create'])->name('solicitudes.create');
//     Route::post('/solicitudes', [SolicitudController::class, 'store'])->name('solicitudes.store');
//     Route::get('/solicitudes/{solicitud}', [SolicitudController::class, 'show'])->name('solicitudes.show');
//     Route::post('/solicitudes/{solicitud}/approve', [SolicitudController::class, 'approve'])->name('solicitudes.approve');
//     Route::post('/solicitudes/{solicitud}/reject', [SolicitudController::class, 'reject'])->name('solicitudes.reject');
//     Route::post('/solicitudes/{solicitud}/deliver', [SolicitudController::class, 'deliver'])->name('solicitudes.deliver');
//     Route::delete('/solicitudes/{solicitud}', [SolicitudController::class, 'destroy'])->name('solicitudes.destroy');
//     Route::get('/solicitudes-pendientes', [SolicitudController::class, 'pending'])->name('solicitudes.pending');
//     Route::get('/mis-solicitudes', [SolicitudController::class, 'myRequests'])->name('solicitudes.my-requests');
// });

// Rutas de carga masiva
Route::middleware(['auth', 'can:manage-inventory'])->group(function () {
    Route::get('/carga-masiva', [CargaMasivaController::class, 'index'])->name('carga-masiva.index');
    Route::post('/carga-masiva', [CargaMasivaController::class, 'upload'])->name('carga-masiva.upload');
});

// Rutas de códigos de barras
Route::middleware(['auth'])->group(function () {
    Route::get('/barcode/{producto}', [BarcodeController::class, 'show'])->name('barcode.show');
    Route::get('/barcode/{producto}/generate', [BarcodeController::class, 'generate'])->name('barcode.generate');
    Route::get('/barcode/{producto}/small', [BarcodeController::class, 'generateSmall'])->name('barcode.small');
    Route::get('/barcode/{producto}/svg', [BarcodeController::class, 'generateSvg'])->name('barcode.svg');
    Route::post('/barcode/{producto}/regenerate', [BarcodeController::class, 'regenerate'])->name('barcode.regenerate');
    Route::post('/barcode/validate', [BarcodeController::class, 'validate'])->name('barcode.validate');
});

// Rutas de roles y permisos
Route::middleware(['auth', 'can:manage-roles'])->group(function () {
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/permissions/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::put('/permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
});

// Rutas de proveedores
Route::middleware(['auth', 'can:view-providers'])->group(function () {
    Route::view('proveedores', 'layouts.proveedor.proveedor_index')->name('proveedores.index');
    Route::get('/proveedores/create', [ProveedorController::class, 'create'])->name('proveedores.create');
    Route::post('/proveedores', [ProveedorController::class, 'store'])->name('proveedores.store');
    Route::get('/proveedores/{proveedor}', [ProveedorController::class, 'show'])->name('proveedores.show');
    Route::get('/proveedores/{proveedor}/edit', [ProveedorController::class, 'edit'])->name('proveedores.edit');
    Route::put('/proveedores/{proveedor}', [ProveedorController::class, 'update'])->name('proveedores.update');
    Route::delete('/proveedores/{proveedor}', [ProveedorController::class, 'destroy'])->name('proveedores.destroy');
    Route::get('/proveedores-api', [ProveedorController::class, 'getProveedores'])->name('proveedores.api');
});

// Rutas de facturas
Route::middleware(['auth', 'can:view-invoices'])->group(function () {
    Route::view('facturas', 'layouts.factura.factura_index')->name('facturas.index');
    Route::get('/facturas/create', [FacturaController::class, 'create'])->name('facturas.create');
    Route::post('/facturas', [FacturaController::class, 'store'])->name('facturas.store');
    Route::get('/facturas/{factura}', [FacturaController::class, 'show'])->name('facturas.show');
    Route::get('/facturas/{factura}/edit', [FacturaController::class, 'edit'])->name('facturas.edit');
    Route::put('/facturas/{factura}', [FacturaController::class, 'update'])->name('facturas.update');
    Route::delete('/facturas/{factura}', [FacturaController::class, 'destroy'])->name('facturas.destroy');
    Route::get('/facturas/{factura}/download', [FacturaController::class, 'download'])->name('facturas.download');
});
