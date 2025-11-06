<?php
use App\Http\Controllers\AuthController;
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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UsersController::class)->names('api.users');
    Route::get('users/departamentos', [UsersController::class, 'getDepartamentos'])->name('api.users.departamentos');
    Route::get('users/permissions', [UsersController::class, 'getPermissions'])->name('api.users.permissions');
    Route::apiResource('departamentos', DepartamentoController::class)->names('api.departamentos');
    Route::apiResource('unidades-medida', UnidadMedidaController::class)->names('api.unidades-medida');
    Route::apiResource('insumos', InsumoController::class)->names('api.insumos');
    Route::post('insumos/{insumo}/adjust-stock', [InsumoController::class, 'adjustStock'])->name('api.insumos.adjust-stock');
    Route::get('insumos/unidades-medida', [InsumoController::class, 'getUnidadesMedida'])->name('api.insumos.unidades-medida');
    Route::get('insumos/low-stock', [InsumoController::class, 'getLowStock'])->name('api.insumos.low-stock');
    Route::apiResource('proveedores', ProveedorController::class)->names('api.proveedores');
    Route::get('proveedores/select', [ProveedorController::class, 'getProveedores'])->name('api.proveedores.select');
    Route::apiResource('facturas', FacturaController::class)->names('api.facturas');
    Route::get('facturas/{factura}/download', [FacturaController::class, 'download'])->name('api.facturas.download');
    Route::get('facturas/proveedores', [FacturaController::class, 'getProveedores'])->name('api.facturas.proveedores');

    // Rutas de Solicitudes
    Route::get('solicitudes', [SolicitudController::class, 'apiIndex'])->name('api.solicitudes.index');
    Route::get('solicitudes/{solicitud}', [SolicitudController::class, 'apiShow'])->name('api.solicitudes.show');
    Route::post('solicitudes', [SolicitudController::class, 'apiStore'])->name('api.solicitudes.store');
    Route::put('solicitudes/{solicitud}', [SolicitudController::class, 'apiUpdate'])->name('api.solicitudes.update');
    Route::delete('solicitudes/{solicitud}', [SolicitudController::class, 'apiDestroy'])->name('api.solicitudes.destroy');
    Route::post('solicitudes/{solicitud}/aprobar', [SolicitudController::class, 'apiAprobar'])->name('api.solicitudes.aprobar');
    Route::post('solicitudes/{solicitud}/rechazar', [SolicitudController::class, 'apiRechazar'])->name('api.solicitudes.rechazar');
    Route::post('solicitudes/{solicitud}/entregar', [SolicitudController::class, 'apiEntregar'])->name('api.solicitudes.entregar');
    Route::get('solicitudes/insumos/get', [SolicitudController::class, 'apiGetInsumos'])->name('api.solicitudes.insumos.get');

    // Rutas de Tipos de Insumo
    Route::apiResource('tipo-insumos', TipoInsumoController::class)->names('api.tipo-insumos');
    Route::get('tipo-insumos/get/all', [TipoInsumoController::class, 'getAll'])->name('api.tipo-insumos.all');

    // Rutas de Carga Masiva
    Route::post('carga-masiva/upload', [CargaMasivaController::class, 'apiUpload'])->name('api.carga-masiva.upload');
    Route::get('carga-masiva/template', [CargaMasivaController::class, 'apiDownloadTemplate'])->name('api.carga-masiva.template');

    // Rutas de Roles y Permisos (solo lectura para móvil)
    Route::get('roles', [RoleController::class, 'apiIndex'])->name('api.roles.index');
    Route::get('roles/{role}', [RoleController::class, 'apiShow'])->name('api.roles.show');
    Route::get('permissions', [PermissionController::class, 'apiIndex'])->name('api.permissions.index');
    Route::get('permissions/{permission}', [PermissionController::class, 'apiShow'])->name('api.permissions.show');
});
