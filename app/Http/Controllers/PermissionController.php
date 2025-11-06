<?php
namespace App\Http\Controllers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
            'guard_name' => 'required|string|max:255'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $permission->update($validator->validated());
        return redirect()->route('permissions.index')->with('status', 'Permiso actualizado correctamente.');
    }
    public function destroy(Permission $permission): RedirectResponse
    {
        $permission->delete();
        return redirect()->back()->with('status', 'Permiso eliminado correctamente.');
    }

    // ==================== MÉTODOS API PARA APLICACIÓN MÓVIL ====================

    /**
     * API: Listar todos los permisos
     */
    public function apiIndex(): JsonResponse
    {
        try {
            $permissions = Permission::orderBy('name')->get();
            
            return response()->json([
                'success' => true,
                'data' => $permissions,
                'message' => 'Permisos obtenidos exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener permisos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Obtener un permiso específico
     */
    public function apiShow(Permission $permission): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $permission,
                'message' => 'Permiso obtenido exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener permiso: ' . $e->getMessage()
            ], 500);
        }
    }
}
