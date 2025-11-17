<?php

use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ============================================
// API DE AUTENTICACIÓN Y USUARIOS
// ============================================
Route::prefix('auth')->group(function () {
    // Rutas públicas
    Route::post('/login', [ApiAuthController::class, 'login'])->name('api.auth.login');
    
    // Rutas protegidas
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [ApiAuthController::class, 'logout'])->name('api.auth.logout');
        Route::get('/me', [ApiAuthController::class, 'me'])->name('api.auth.me');
        
        // CRUD de usuarios
        Route::get('/users', [ApiAuthController::class, 'users'])->name('api.auth.users.index');
        Route::get('/users/{run}', [ApiAuthController::class, 'showUser'])->name('api.auth.users.show');
        Route::post('/users', [ApiAuthController::class, 'createUser'])->name('api.auth.users.store');
        Route::put('/users/{run}', [ApiAuthController::class, 'updateUser'])->name('api.auth.users.update');
        Route::delete('/users/{run}', [ApiAuthController::class, 'deleteUser'])->name('api.auth.users.destroy');
        
        // Helpers para formularios de usuarios
        Route::get('/departamentos', [ApiAuthController::class, 'getDepartamentos'])->name('api.auth.departamentos');
        Route::get('/permissions', [ApiAuthController::class, 'getPermissions'])->name('api.auth.permissions');
    });
});

// ============================================
// API GENERAL (todos los demás recursos)
// ============================================
Route::middleware('auth:sanctum')->group(function () {
    // Departamentos
    Route::get('/departamentos', [ApiController::class, 'departamentos'])->name('api.departamentos.index');
    Route::get('/departamentos/{id}', [ApiController::class, 'showDepartamento'])->name('api.departamentos.show');
    Route::post('/departamentos', [ApiController::class, 'createDepartamento'])->name('api.departamentos.store');
    Route::put('/departamentos/{id}', [ApiController::class, 'updateDepartamento'])->name('api.departamentos.update');
    Route::delete('/departamentos/{id}', [ApiController::class, 'deleteDepartamento'])->name('api.departamentos.destroy');
    
    // Unidades de Medida
    Route::get('/unidades-medida', [ApiController::class, 'unidades'])->name('api.unidades-medida.index');
    Route::get('/unidades-medida/{id}', [ApiController::class, 'showUnidad'])->name('api.unidades-medida.show');
    Route::post('/unidades-medida', [ApiController::class, 'createUnidad'])->name('api.unidades-medida.store');
    Route::put('/unidades-medida/{id}', [ApiController::class, 'updateUnidad'])->name('api.unidades-medida.update');
    Route::delete('/unidades-medida/{id}', [ApiController::class, 'deleteUnidad'])->name('api.unidades-medida.destroy');
    
    // Tipos de Insumo
    Route::get('/tipo-insumos', [ApiController::class, 'tipoInsumos'])->name('api.tipo-insumos.index');
    Route::get('/tipo-insumos/all', [ApiController::class, 'getAllTipoInsumos'])->name('api.tipo-insumos.all');
    Route::get('/tipo-insumos/{id}', [ApiController::class, 'showTipoInsumo'])->name('api.tipo-insumos.show');
    Route::post('/tipo-insumos', [ApiController::class, 'createTipoInsumo'])->name('api.tipo-insumos.store');
    Route::put('/tipo-insumos/{id}', [ApiController::class, 'updateTipoInsumo'])->name('api.tipo-insumos.update');
    Route::delete('/tipo-insumos/{id}', [ApiController::class, 'deleteTipoInsumo'])->name('api.tipo-insumos.destroy');
    
    // Insumos
    Route::get('/insumos', [ApiController::class, 'insumos'])->name('api.insumos.index');
    Route::get('/insumos/{id}', [ApiController::class, 'showInsumo'])->name('api.insumos.show');
    Route::post('/insumos', [ApiController::class, 'createInsumo'])->name('api.insumos.store');
    Route::put('/insumos/{id}', [ApiController::class, 'updateInsumo'])->name('api.insumos.update');
    Route::delete('/insumos/{id}', [ApiController::class, 'deleteInsumo'])->name('api.insumos.destroy');
    Route::post('/insumos/{id}/adjust-stock', [ApiController::class, 'adjustStock'])->name('api.insumos.adjust-stock');
    Route::get('/insumos/unidades-medida', [ApiController::class, 'getUnidadesMedida'])->name('api.insumos.unidades-medida');
    Route::get('/insumos/low-stock', [ApiController::class, 'getLowStock'])->name('api.insumos.low-stock');
    
    // Proveedores
    Route::get('/proveedores', [ApiController::class, 'proveedores'])->name('api.proveedores.index');
    Route::get('/proveedores/select', [ApiController::class, 'getProveedoresSelect'])->name('api.proveedores.select');
    Route::get('/proveedores/{id}', [ApiController::class, 'showProveedor'])->name('api.proveedores.show');
    Route::post('/proveedores', [ApiController::class, 'createProveedor'])->name('api.proveedores.store');
    Route::put('/proveedores/{id}', [ApiController::class, 'updateProveedor'])->name('api.proveedores.update');
    Route::delete('/proveedores/{id}', [ApiController::class, 'deleteProveedor'])->name('api.proveedores.destroy');
    
    // Facturas
    Route::get('/facturas', [ApiController::class, 'facturas'])->name('api.facturas.index');
    Route::get('/facturas/{id}', [ApiController::class, 'showFactura'])->name('api.facturas.show');
    Route::post('/facturas', [ApiController::class, 'createFactura'])->name('api.facturas.store');
    Route::put('/facturas/{id}', [ApiController::class, 'updateFactura'])->name('api.facturas.update');
    Route::delete('/facturas/{id}', [ApiController::class, 'deleteFactura'])->name('api.facturas.destroy');
    Route::get('/facturas/{id}/download', [ApiController::class, 'downloadFactura'])->name('api.facturas.download');
    Route::get('/facturas/proveedores', [ApiController::class, 'getProveedoresForFacturas'])->name('api.facturas.proveedores');
    
    // Solicitudes
    Route::get('/solicitudes', [ApiController::class, 'solicitudes'])->name('api.solicitudes.index');
    Route::get('/solicitudes/{id}', [ApiController::class, 'showSolicitud'])->name('api.solicitudes.show');
    Route::post('/solicitudes', [ApiController::class, 'createSolicitud'])->name('api.solicitudes.store');
    Route::put('/solicitudes/{id}', [ApiController::class, 'updateSolicitud'])->name('api.solicitudes.update');
    Route::delete('/solicitudes/{id}', [ApiController::class, 'deleteSolicitud'])->name('api.solicitudes.destroy');
    Route::post('/solicitudes/{id}/aprobar', [ApiController::class, 'aprobarSolicitud'])->name('api.solicitudes.aprobar');
    Route::post('/solicitudes/{id}/rechazar', [ApiController::class, 'rechazarSolicitud'])->name('api.solicitudes.rechazar');
    Route::post('/solicitudes/{id}/entregar', [ApiController::class, 'entregarSolicitud'])->name('api.solicitudes.entregar');
    Route::get('/solicitudes/insumos/get', [ApiController::class, 'getInsumosForSolicitudes'])->name('api.solicitudes.insumos.get');
    
    // Carga Masiva
    Route::post('/carga-masiva/upload', [ApiController::class, 'cargaMasivaUpload'])->name('api.carga-masiva.upload');
    Route::get('/carga-masiva/template', [ApiController::class, 'cargaMasivaTemplate'])->name('api.carga-masiva.template');
    
    // Roles y Permisos (solo lectura)
    Route::get('/roles', [ApiController::class, 'roles'])->name('api.roles.index');
    Route::get('/roles/{id}', [ApiController::class, 'showRole'])->name('api.roles.show');
    Route::get('/permissions', [ApiController::class, 'permissions'])->name('api.permissions.index');
    Route::get('/permissions/{id}', [ApiController::class, 'showPermission'])->name('api.permissions.show');
});
