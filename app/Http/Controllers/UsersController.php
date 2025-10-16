<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Departamento;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:manage-users')->except(['edit', 'update']);
    }

    public function create(): View
    {
        $this->authorizeAction('create-users');
        
        $departamentos = Departamento::orderByName()->get();
        $permissions = Permission::orderBy('name')->get();
        
        return view('layouts.user.user_create', compact('departamentos', 'permissions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAction('create-users');
        
        try {
            $this->logAction('Creando usuario', ['run' => $request->run]);

            $validated = $request->validate([
                'run' => ['required', 'string', 'max:255', 'unique:users,run'],
                'nombre' => ['required', 'string', 'max:255'],
                'correo' => ['required', 'email', 'max:255', 'unique:users,correo'],
                'contrasena' => ['required', 'string', 'min:8', 'confirmed'],
                'id_depto' => ['required', 'string', 'exists:departamentos,id_depto'],
                'permissions' => ['nullable', 'array'],
                'permissions.*' => ['exists:permissions,id']
            ]);

            return $this->executeInTransaction(function () use ($validated, $request) {
                $validated['contrasena'] = Hash::make($validated['contrasena']);
                $user = User::create($validated);

                $this->syncUserPermissions($user, $request->input('permissions', []));
                
                $this->logAction('Usuario creado exitosamente', ['user_run' => $user->run]);
                return redirect()->route('users')->with('status', 'Usuario creado exitosamente.');
            });

        } catch (\Throwable $e) {
            return $this->handleException($e, 'UsersController@store', ['run' => $request->run]);
        }
    }

    public function edit(User $user): View
    {
        $this->authorizeAction('edit-users');
        
        $user->load(['permissions', 'departamento']);
        $departamentos = Departamento::orderByName()->get();
        $permissions = Permission::orderBy('name')->get();
        
        return view('layouts.user.user_update', compact('user', 'departamentos', 'permissions'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->authorizeAction('edit-users');
        
        try {
            $this->logAction('Actualizando usuario', ['user_run' => $user->run]);

            $validated = $request->validate([
                'run' => ['required', 'string', 'max:255', Rule::unique('users', 'run')->ignore($user->run, 'run')],
                'nombre' => ['required', 'string', 'max:255'],
                'correo' => ['required', 'email', 'max:255', Rule::unique('users', 'correo')->ignore($user->run, 'run')],
                'contrasena' => ['nullable', 'string', 'min:8'],
                'id_depto' => ['required', 'string', 'exists:departamentos,id_depto'],
                'permissions' => ['nullable', 'array'],
                'permissions.*' => ['exists:permissions,id']
            ]);

            return $this->executeInTransaction(function () use ($validated, $request, $user) {
                if (empty($validated['contrasena'])) {
                    unset($validated['contrasena']);
                } else {
                    $validated['contrasena'] = Hash::make($validated['contrasena']);
                }

                $user->update($validated);
                $this->syncUserPermissions($user, $request->input('permissions', []));
                
                $this->logAction('Usuario actualizado exitosamente', ['user_run' => $user->run]);
                return redirect()->route('users')->with('status', 'Usuario actualizado exitosamente.');
            });

        } catch (\Throwable $e) {
            return $this->handleException($e, 'UsersController@update', ['user_run' => $user->run]);
        }
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorizeAction('delete-users');
        
        try {
            $this->logAction('Eliminando usuario', ['user_run' => $user->run]);
            
            $user->delete();
            
            $this->logAction('Usuario eliminado exitosamente', ['user_run' => $user->run]);
            return redirect()->back()->with('status', 'Usuario eliminado exitosamente.');
            
        } catch (\Throwable $e) {
            return $this->handleException($e, 'UsersController@destroy', ['user_run' => $user->run]);
        }
    }

    /**
     * Sincronizar permisos del usuario
     */
    private function syncUserPermissions(User $user, array $permissionIds): void
    {
        if (empty($permissionIds)) {
            $user->syncPermissions([]);
            return;
        }

        $permissionNames = Permission::whereIn('id', $permissionIds)->pluck('name')->toArray();
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        $user->syncPermissions($permissionNames);
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}


