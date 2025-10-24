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
    Route::apiResource('users', UsersController::class);
    Route::get('users/departamentos', [UsersController::class, 'getDepartamentos']);
    Route::get('users/permissions', [UsersController::class, 'getPermissions']);

    Route::apiResource('departamentos', DepartamentoController::class);

    Route::apiResource('unidades-medida', UnidadMedidaController::class);

    Route::apiResource('insumos', InsumoController::class);
    Route::post('insumos/{insumo}/adjust-stock', [InsumoController::class, 'adjustStock']);
    Route::get('insumos/unidades-medida', [InsumoController::class, 'getUnidadesMedida']);
    Route::get('insumos/low-stock', [InsumoController::class, 'getLowStock']);

    Route::apiResource('inventarios', InventarioController::class);
    Route::get('inventarios/insumos', [InventarioController::class, 'getInsumos']);

    Route::apiResource('proveedores', ProveedorController::class);
    Route::get('proveedores/select', [ProveedorController::class, 'getProveedores']);

    Route::apiResource('facturas', FacturaController::class);
    Route::get('facturas/{factura}/download', [FacturaController::class, 'download']);
    Route::get('facturas/proveedores', [FacturaController::class, 'getProveedores']);
});
