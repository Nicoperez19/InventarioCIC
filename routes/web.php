<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::view('dashboard', 'layouts.dashboard.dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'layouts.profile.profile')
    ->middleware(['auth'])
    ->name('profile');

Route::view('users', 'layouts.user.user_index')
    ->middleware(['auth'])
    ->name('users');

// Departamentos
Route::view('departamentos', 'layouts.departamento.departamento_index')
    ->middleware(['auth'])
    ->name('departamentos');

// Unidades
Route::view('unidades', 'layouts.unidad.unidad_index')
    ->middleware(['auth'])
    ->name('unidades');

// Productos
Route::view('productos', 'layouts.producto.producto_index')
    ->middleware(['auth'])
    ->name('productos');

Route::middleware('auth')->group(function () {
    Route::get('/users/create', [\App\Http\Controllers\UsersController::class, 'create'])->name('users.create');
    Route::post('/users', [\App\Http\Controllers\UsersController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [\App\Http\Controllers\UsersController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [\App\Http\Controllers\UsersController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [\App\Http\Controllers\UsersController::class, 'destroy'])->name('users.destroy');

    // CRUD Departamentos
    Route::get('/departamentos/create', [\App\Http\Controllers\DepartamentoController::class, 'create'])->name('departamentos.create');
    Route::post('/departamentos', [\App\Http\Controllers\DepartamentoController::class, 'store'])->name('departamentos.store');
    Route::get('/departamentos/{departamento}/edit', [\App\Http\Controllers\DepartamentoController::class, 'edit'])->name('departamentos.edit');
    Route::put('/departamentos/{departamento}', [\App\Http\Controllers\DepartamentoController::class, 'update'])->name('departamentos.update');
    Route::delete('/departamentos/{departamento}', [\App\Http\Controllers\DepartamentoController::class, 'destroy'])->name('departamentos.destroy');

    // CRUD Unidades
    Route::get('/unidades/create', [\App\Http\Controllers\UnidadController::class, 'create'])->name('unidades.create');
    Route::post('/unidades', [\App\Http\Controllers\UnidadController::class, 'store'])->name('unidades.store');
    Route::get('/unidades/{unidad}/edit', [\App\Http\Controllers\UnidadController::class, 'edit'])->name('unidades.edit');
    Route::put('/unidades/{unidad}', [\App\Http\Controllers\UnidadController::class, 'update'])->name('unidades.update');
    Route::delete('/unidades/{unidad}', [\App\Http\Controllers\UnidadController::class, 'destroy'])->name('unidades.destroy');

    // CRUD Productos
    Route::get('/productos/create', [\App\Http\Controllers\ProductoController::class, 'create'])->name('productos.create');
    Route::post('/productos', [\App\Http\Controllers\ProductoController::class, 'store'])->name('productos.store');
    Route::get('/productos/{producto}/edit', [\App\Http\Controllers\ProductoController::class, 'edit'])->name('productos.edit');
    Route::put('/productos/{producto}', [\App\Http\Controllers\ProductoController::class, 'update'])->name('productos.update');
    Route::delete('/productos/{producto}', [\App\Http\Controllers\ProductoController::class, 'destroy'])->name('productos.destroy');
});

require __DIR__.'/auth.php';

// Roles y Permisos (Spatie)
Route::middleware('auth')->group(function () {
    // Roles
    Route::get('/roles', [\App\Http\Controllers\RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [\App\Http\Controllers\RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [\App\Http\Controllers\RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{role}/edit', [\App\Http\Controllers\RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{role}', [\App\Http\Controllers\RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}', [\App\Http\Controllers\RoleController::class, 'destroy'])->name('roles.destroy');

    // Permisos
    Route::get('/permissions', [\App\Http\Controllers\PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/permissions/{permission}/edit', [\App\Http\Controllers\PermissionController::class, 'edit'])->name('permissions.edit');
    Route::put('/permissions/{permission}', [\App\Http\Controllers\PermissionController::class, 'update'])->name('permissions.update');
    Route::delete('/permissions/{permission}', [\App\Http\Controllers\PermissionController::class, 'destroy'])->name('permissions.destroy');
});
