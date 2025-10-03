<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index(): View
    {
        $permissions = Permission::orderBy('name')->get();
        return view('layouts.permission.permission_index', compact('permissions'));
    }

    public function edit(Permission $permission): View
    {
        return view('layouts.permission.permission_update', compact('permission'));
    }

    public function update(Request $request, Permission $permission): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name,' . $permission->id],
        ]);

        $permission->update($validated);

        return redirect()->route('permissions.index')->with('status', 'Permiso actualizado correctamente.');
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        $permission->delete();
        return redirect()->back()->with('status', 'Permiso eliminado correctamente.');
    }
}


