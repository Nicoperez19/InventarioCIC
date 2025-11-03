<?php
use App\Http\Controllers\CargaMasivaController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\InsumoController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\TipoInsumoController;
use App\Http\Controllers\UnidadMedidaController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});
require __DIR__.'/auth.php';
Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'layouts.dashboard.dashboard')->name('dashboard');
    Route::view('profile', 'layouts.profile.profile')->name('profile');
    Route::post('logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');
});
Route::middleware(['auth', 'can:manage-users'])->group(function () {
    Route::view('users', 'layouts.user.user_index')->name('users');
    Route::post('/users', [UsersController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [UsersController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UsersController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UsersController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UsersController::class, 'destroy'])->name('users.destroy');
});
Route::middleware(['auth', 'can:manage-departments'])->group(function () {
    Route::view('departamentos', 'layouts.departamento.departamento_index')->name('departamentos');
    Route::get('/departamentos/create', [DepartamentoController::class, 'create'])->name('departamentos.create');
    Route::post('/departamentos', [DepartamentoController::class, 'store'])->name('departamentos.store');
    Route::get('/departamentos/{departamento}', [DepartamentoController::class, 'show'])->name('departamentos.show');
    Route::get('/departamentos/{departamento}/edit', [DepartamentoController::class, 'edit'])->name('departamentos.edit');
    Route::put('/departamentos/{departamento}', [DepartamentoController::class, 'update'])->name('departamentos.update');
    Route::delete('/departamentos/{departamento}', [DepartamentoController::class, 'destroy'])->name('departamentos.destroy');
});
Route::middleware(['auth', 'can:manage-units'])->group(function () {
    Route::view('unidades', 'layouts.unidad.unidad_index')->name('unidades');
    Route::get('/unidades/create', [UnidadMedidaController::class, 'create'])->name('unidades.create');
    Route::post('/unidades', [UnidadMedidaController::class, 'store'])->name('unidades.store');
    Route::get('/unidades/{unidad}', [UnidadMedidaController::class, 'show'])->name('unidades.show');
    Route::get('/unidades/{unidad}/edit', [UnidadMedidaController::class, 'edit'])->name('unidades.edit');
    Route::put('/unidades/{unidad}', [UnidadMedidaController::class, 'update'])->name('unidades.update');
    Route::delete('/unidades/{unidad}', [UnidadMedidaController::class, 'destroy'])->name('unidades.destroy');
});
Route::middleware(['auth', 'can:manage-tipo-insumos'])->group(function () {
    Route::view('tipo-insumos', 'layouts.tipo_insumo.tipo_insumo_index')->name('tipo-insumos.index');
    Route::get('/tipo-insumos/create', [TipoInsumoController::class, 'create'])->name('tipo-insumos.create');
    Route::post('/tipo-insumos', [TipoInsumoController::class, 'store'])->name('tipo-insumos.store');
    Route::get('/tipo-insumos/{tipoInsumo}', [TipoInsumoController::class, 'show'])->name('tipo-insumos.show');
    Route::get('/tipo-insumos/{tipoInsumo}/edit', [TipoInsumoController::class, 'edit'])->name('tipo-insumos.edit');
    Route::put('/tipo-insumos/{tipoInsumo}', [TipoInsumoController::class, 'update'])->name('tipo-insumos.update');
    Route::delete('/tipo-insumos/{tipoInsumo}', [TipoInsumoController::class, 'destroy'])->name('tipo-insumos.destroy');
    Route::get('/tipo-insumos/{tipoInsumo}/pdf', [TipoInsumoController::class, 'generatePdf'])->name('tipo-insumos.pdf');
});
Route::middleware(['auth', 'can:manage-insumos'])->group(function () {
    Route::view('insumos', 'layouts.insumo.insumo_index')->name('insumos.index');
    Route::get('/insumos/create', [InsumoController::class, 'create'])->name('insumos.create');
    Route::post('/insumos', [InsumoController::class, 'store'])->name('insumos.store');
    Route::get('/insumos/{insumo}', [InsumoController::class, 'show'])->name('insumos.show');
    Route::get('/insumos/{insumo}/edit', [InsumoController::class, 'edit'])->name('insumos.edit');
    Route::put('/insumos/{insumo}', [InsumoController::class, 'update'])->name('insumos.update');
    Route::delete('/insumos/{insumo}', [InsumoController::class, 'destroy'])->name('insumos.destroy');
    Route::get('/barcode/{insumo}/generate', [InsumoController::class, 'generateBarcode'])->name('barcode.generate');
    Route::get('/barcode/{insumo}/svg', [InsumoController::class, 'generateBarcodeSvg'])->name('barcode.svg');
    Route::get('/carga-masiva', [CargaMasivaController::class, 'index'])->name('carga-masiva.index');
    Route::post('/carga-masiva/upload', [CargaMasivaController::class, 'upload'])->name('carga-masiva.upload');
    Route::get('/carga-masiva/template', [CargaMasivaController::class, 'downloadTemplate'])->name('carga-masiva.template');
});

Route::middleware(['auth', 'can:solicitar-insumos'])->group(function () {
    Route::view('solicitudes', 'layouts.solicitud.solicitud_index')->name('solicitudes');
});

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
Route::middleware(['auth', 'can:manage-providers'])->group(function () {
    Route::view('proveedores', 'layouts.proveedor.proveedor_index')->name('proveedores.index');
    Route::get('/proveedores/create', [ProveedorController::class, 'create'])->name('proveedores.create');
    Route::post('/proveedores', [ProveedorController::class, 'store'])->name('proveedores.store');
    Route::get('/proveedores/{proveedor}', [ProveedorController::class, 'show'])->name('proveedores.show');
    Route::get('/proveedores/{proveedor}/edit', [ProveedorController::class, 'edit'])->name('proveedores.edit');
    Route::put('/proveedores/{proveedor}', [ProveedorController::class, 'update'])->name('proveedores.update');
    Route::delete('/proveedores/{proveedor}', [ProveedorController::class, 'destroy'])->name('proveedores.destroy');
});
Route::middleware(['auth', 'can:manage-invoices'])->group(function () {
    Route::view('facturas', 'layouts.factura.factura_index')->name('facturas.index');
    Route::get('/facturas/create', [FacturaController::class, 'create'])->name('facturas.create');
    Route::post('/facturas', [FacturaController::class, 'store'])->name('facturas.store');
    Route::get('/facturas/{factura}', [FacturaController::class, 'show'])->name('facturas.show');
    Route::get('/facturas/{factura}/edit', [FacturaController::class, 'edit'])->name('facturas.edit');
    Route::put('/facturas/{factura}', [FacturaController::class, 'update'])->name('facturas.update');
    Route::delete('/facturas/{factura}', [FacturaController::class, 'destroy'])->name('facturas.destroy');
    Route::get('/facturas/{factura}/download', [FacturaController::class, 'download'])->name('facturas.download');
});
Route::middleware(['auth', 'can:manage-requests'])->group(function () {
    Route::view('admin-solicitudes', 'layouts.admin_solicitudes.admin_solicitudes_index')->name('admin-solicitudes');
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
