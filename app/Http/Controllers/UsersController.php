<?php
namespace App\Http\Controllers;
use App\Models\Departamento;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
class UsersController extends Controller
{
    protected UserService $userService;
    public function __construct(UserService $userService)
    {
        $this->middleware('auth');
        $this->userService = $userService;
    }
    public function index(Request $request): JsonResponse
    {
        try {
            $query = User::with(['departamento', 'permissions'])
                ->orderByName();
            if ($request->has('search')) {
                $search = $request->get('search');
                $query->where(function($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%")
                      ->orWhere('correo', 'like', "%{$search}%")
                      ->orWhere('run', 'like', "%{$search}%");
                });
            }
            if ($request->has('departamento')) {
                $query->where('id_depto', $request->get('departamento'));
            }
            $users = $query->paginate($request->get('per_page', 15));
            return response()->json([
                'success' => true,
                'data' => $users,
                'message' => 'Usuarios obtenidos exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener usuarios: ' . $e->getMessage()
            ], 500);
        }
    }
    public function show(User $user): JsonResponse
    {
        try {
            $user->load(['departamento', 'permissions', 'roles']);
            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Usuario obtenido exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener usuario: ' . $e->getMessage()
            ], 500);
        }
    }
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'run' => 'required|string|max:20|unique:users,run',
                'nombre' => 'required|string|max:255',
                'correo' => 'required|email|unique:users,correo',
                'contrasena' => 'required|string|min:8',
                'id_depto' => 'required|exists:departamentos,id_depto',
                'permissions' => 'array',
                'permissions.*' => 'exists:permissions,name'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de validación incorrectos',
                    'errors' => $validator->errors()
                ], 422);
            }
            $userData = $request->only(['run', 'nombre', 'correo', 'contrasena', 'id_depto']);
            $userData['contrasena'] = Hash::make($userData['contrasena']);
            $user = User::create($userData);
            if ($request->has('permissions')) {
                $user->syncPermissions($request->permissions);
            }
            $user->load(['departamento', 'permissions']);
            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Usuario creado exitosamente'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear usuario: ' . $e->getMessage()
            ], 500);
        }
    }
    public function update(Request $request, User $user): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'run' => 'sometimes|string|max:20|unique:users,run,' . $user->run,
                'nombre' => 'sometimes|string|max:255',
                'correo' => 'sometimes|email|unique:users,correo,' . $user->run,
                'contrasena' => 'sometimes|string|min:8',
                'id_depto' => 'sometimes|exists:departamentos,id_depto',
                'permissions' => 'array',
                'permissions.*' => 'exists:permissions,name'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de validación incorrectos',
                    'errors' => $validator->errors()
                ], 422);
            }
            $userData = $request->only(['run', 'nombre', 'correo', 'id_depto']);
            if ($request->has('contrasena')) {
                $userData['contrasena'] = Hash::make($request->contrasena);
            }
            $user->update($userData);
            if ($request->has('permissions')) {
                $user->syncPermissions($request->permissions);
            }
            $user->load(['departamento', 'permissions']);
            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Usuario actualizado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar usuario: ' . $e->getMessage()
            ], 500);
        }
    }
    public function destroy(User $user): JsonResponse
    {
        try {
            $user->delete();
            return response()->json([
                'success' => true,
                'message' => 'Usuario eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar usuario: ' . $e->getMessage()
            ], 500);
        }
    }
    public function getDepartamentos(): JsonResponse
    {
        try {
            $departamentos = Departamento::orderByName()->get();
            return response()->json([
                'success' => true,
                'data' => $departamentos,
                'message' => 'Departamentos obtenidos exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener departamentos: ' . $e->getMessage()
            ], 500);
        }
    }
    public function getPermissions(): JsonResponse
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
}
