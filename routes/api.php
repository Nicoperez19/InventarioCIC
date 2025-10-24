<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\InsumoController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\ProveedorController;
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

    Route::apiResource('inventarios', InventarioController::class)->names('api.inventarios');
    Route::get('inventarios/insumos', [InventarioController::class, 'getInsumos'])->name('api.inventarios.insumos');

    Route::apiResource('proveedores', ProveedorController::class)->names('api.proveedores');
    Route::get('proveedores/select', [ProveedorController::class, 'getProveedores'])->name('api.proveedores.select');

    Route::apiResource('facturas', FacturaController::class)->names('api.facturas');
    Route::get('facturas/{factura}/download', [FacturaController::class, 'download'])->name('api.facturas.download');
    Route::get('facturas/proveedores', [FacturaController::class, 'getProveedores'])->name('api.facturas.proveedores');
});
