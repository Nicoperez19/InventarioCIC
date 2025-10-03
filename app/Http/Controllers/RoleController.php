<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(): View
    {
        $roles = Role::orderBy('name')->get();
        return view('layouts.rol.rol_index', compact('roles'));
    }

    public function edit(Role $role): View
    {
        return view('layouts.rol.rol_update', compact('role'));
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name,' . $role->id],
        ]);

        $role->update($validated);

        return redirect()->route('roles.index')->with('status', 'Rol actualizado correctamente.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        $role->delete();
        return redirect()->back()->with('status', 'Rol eliminado correctamente.');
    }
}


