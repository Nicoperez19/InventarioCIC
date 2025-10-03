<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Facades\Log;

class UsersController extends Controller
{
    public function edit(User $user): View
    {
        $user->load('permissions');
        $permissions = Permission::orderBy('name')->get();
        return view('layouts.user.user_update', compact('user', 'permissions'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        try {
            Log::info('UsersController@update iniciado', [
                'user_id' => $user->id,
                'payload' => $request->except(['password']),
            ]);

            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
                'password' => ['nullable', 'string', 'min:8'],
            ]);

            if (empty($validated['password'])) {
                unset($validated['password']);
            }

            $user->update($validated);

            $selectedPermissionIds = $request->input('permissions', []);
            if (!is_array($selectedPermissionIds)) {
                $selectedPermissionIds = [];
            }
            Log::info('Sincronizando permisos por IDs', [
                'user_id' => $user->id,
                'permission_ids' => $selectedPermissionIds,
            ]);

            $permissionNames = Permission::whereIn('id', $selectedPermissionIds)->pluck('name')->toArray();
            // Limpiar cache de permisos antes de sincronizar
            app(PermissionRegistrar::class)->forgetCachedPermissions();
            $user->syncPermissions($permissionNames);
            // Limpiar nuevamente para reflejar cambios inmediatos en la siguiente carga
            app(PermissionRegistrar::class)->forgetCachedPermissions();

            Log::info('UsersController@update finalizado OK', [
                'user_id' => $user->id,
            ]);

            return redirect()->route('user-index')->with('status', 'Usuario actualizado correctamente.');
        } catch (\Throwable $e) {
            Log::error('Error en UsersController@update', [
                'user_id' => $user->id ?? null,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->withErrors(['update' => 'Ocurrió un error al actualizar el usuario. Revisa el log.'])->withInput();
        }
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->back()->with('status', 'Usuario eliminado correctamente.');
    }
}


