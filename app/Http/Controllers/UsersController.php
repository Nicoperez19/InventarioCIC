<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function create(): View
    {
        $permissions = Permission::orderBy('name')->get();
        return view('layouts.user.user_create', compact('permissions'));
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            Log::info('UsersController@store iniciado', [
                'payload' => $request->except(['contrasena']),
            ]);

            $validated = $request->validate([
                'run' => ['required', 'string', 'max:255', 'unique:users,run'],
                'nombre' => ['required', 'string', 'max:255'],
                'correo' => ['required', 'email', 'max:255', 'unique:users,correo'],
                'contrasena' => ['required', 'string', 'min:8', 'confirmed'],
                'id_depto' => ['required', 'string', 'exists:departamentos,id_depto'],
            ]);

            $validated['contrasena'] = Hash::make($validated['contrasena']);

            $user = User::create($validated);

            $selectedPermissionIds = $request->input('permissions', []);
            if (!is_array($selectedPermissionIds)) {
                $selectedPermissionIds = [];
            }
            
            Log::info('Sincronizando permisos por IDs', [
                'user_run' => $user->run,
                'permission_ids' => $selectedPermissionIds,
            ]);

            $permissionNames = Permission::whereIn('id', $selectedPermissionIds)->pluck('name')->toArray();
            // Limpiar cache de permisos antes de sincronizar
            app(PermissionRegistrar::class)->forgetCachedPermissions();
            $user->syncPermissions($permissionNames);
            // Limpiar nuevamente para reflejar cambios inmediatos en la siguiente carga
            app(PermissionRegistrar::class)->forgetCachedPermissions();

            Log::info('UsersController@store finalizado OK', [
                'user_run' => $user->run,
            ]);

            return redirect()->route('users')->with('status', 'Usuario creado correctamente.');
        } catch (\Throwable $e) {
            Log::error('Error en UsersController@store', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->withErrors(['create' => 'Ocurrió un error al crear el usuario. Revisa el log.'])->withInput();
        }
    }

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
                'user_run' => $user->run,
                'payload' => $request->except(['contrasena']),
            ]);

            $validated = $request->validate([
                'run' => ['required', 'string', 'max:255', 'unique:users,run,' . $user->run],
                'nombre' => ['required', 'string', 'max:255'],
                'correo' => ['required', 'email', 'max:255', 'unique:users,correo,' . $user->run],
                'contrasena' => ['nullable', 'string', 'min:8'],
                'id_depto' => ['required', 'string', 'exists:departamentos,id_depto'],
            ]);

            if (empty($validated['contrasena'])) {
                unset($validated['contrasena']);
            } else {
                $validated['contrasena'] = Hash::make($validated['contrasena']);
            }

            $user->update($validated);

            $selectedPermissionIds = $request->input('permissions', []);
            if (!is_array($selectedPermissionIds)) {
                $selectedPermissionIds = [];
            }
            Log::info('Sincronizando permisos por IDs', [
                'user_run' => $user->run,
                'permission_ids' => $selectedPermissionIds,
            ]);

            $permissionNames = Permission::whereIn('id', $selectedPermissionIds)->pluck('name')->toArray();
            // Limpiar cache de permisos antes de sincronizar
            app(PermissionRegistrar::class)->forgetCachedPermissions();
            $user->syncPermissions($permissionNames);
            // Limpiar nuevamente para reflejar cambios inmediatos en la siguiente carga
            app(PermissionRegistrar::class)->forgetCachedPermissions();

            Log::info('UsersController@update finalizado OK', [
                'user_run' => $user->run,
            ]);

            return redirect()->route('user-index')->with('status', 'Usuario actualizado correctamente.');
        } catch (\Throwable $e) {
            Log::error('Error en UsersController@update', [
                'user_run' => $user->run ?? null,
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


