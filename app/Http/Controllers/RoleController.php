<?php
namespace App\Http\Controllers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name',
            'guard_name' => 'required|string|max:255',
            'permissions' => 'array'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $role = Role::create($validator->validated());
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
    public function update(Request $request, Role $role): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'guard_name' => 'required|string|max:255',
            'permissions' => 'array'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $role->update($validator->validated());
        return redirect()->route('roles.index')->with('status', 'Rol actualizado correctamente.');
    }
    public function destroy(Role $role): RedirectResponse
    {
        $role->delete();
        return redirect()->back()->with('status', 'Rol eliminado correctamente.');
    }

    // ==================== MÉTODOS API PARA APLICACIÓN MÓVIL ====================

    /**
     * API: Listar todos los roles
     */
    public function apiIndex(): JsonResponse
    {
        try {
            $roles = Role::with('permissions')->orderBy('name')->get();
            
            return response()->json([
                'success' => true,
                'data' => $roles,
                'message' => 'Roles obtenidos exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener roles: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Obtener un rol específico
     */
    public function apiShow(Role $role): JsonResponse
    {
        try {
            $role->load('permissions');
            
            return response()->json([
                'success' => true,
                'data' => $role,
                'message' => 'Rol obtenido exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener rol: ' . $e->getMessage()
            ], 500);
        }
    }
}
