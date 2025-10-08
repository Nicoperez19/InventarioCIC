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

Route::middleware('auth')->group(function () {
    Route::get('/users/create', [\App\Http\Controllers\UsersController::class, 'create'])->name('users.create');
    Route::post('/users', [\App\Http\Controllers\UsersController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [\App\Http\Controllers\UsersController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [\App\Http\Controllers\UsersController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [\App\Http\Controllers\UsersController::class, 'destroy'])->name('users.destroy');
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
