<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function create(): View
    {
        $permissions = Permission::orderBy('name')->get();

        return view('layouts.rol.rol_create', compact('permissions'));
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $role = Role::create($validated);

        $selectedPermissionIds = $request->input('permissions', []);
        if (! is_array($selectedPermissionIds)) {
            $selectedPermissionIds = [];
        }

        $permissionNames = Permission::whereIn('id', $selectedPermissionIds)->pluck('name')->toArray();
        $role->syncPermissions($permissionNames);

        return redirect()->route('roles.index')->with('status', 'Rol creado correctamente.');
    }

    public function index(): View
    {
        $roles = Role::orderBy('name')->get();

        return view('layouts.rol.rol_index', compact('roles'));
    }

    public function edit(Role $role): View
    {
        return view('layouts.rol.rol_update', compact('role'));
    }

    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        $validated = $request->validated();

        $role->update($validated);

        return redirect()->route('roles.index')->with('status', 'Rol actualizado correctamente.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        $role->delete();

        return redirect()->back()->with('status', 'Rol eliminado correctamente.');
    }
}
