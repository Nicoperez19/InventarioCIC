<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Departamento;
use App\Models\User;
use App\Rules\RunValidation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class ApiAuthController extends Controller
{
    /**
     * Login de usuario
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'run' => ['required', 'string', new RunValidation()],
                'contrasena' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de validación incorrectos',
                    'errors' => $validator->errors()
                ], 422);
            }

            $run = \App\Helpers\RunFormatter::format($request->run);
            $user = User::where('run', $run)->first();

            if (!$user || !Hash::check($request->contrasena, $user->contrasena)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Credenciales incorrectas'
                ], 401);
            }

            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $user->load(['departamento', 'permissions']),
                    'token' => $token
                ],
                'message' => 'Login exitoso'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en el login: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Logout de usuario
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'success' => true,
                'message' => 'Logout exitoso'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en el logout: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener usuario actual autenticado
     */
    public function me(Request $request): JsonResponse
    {
        try {
            $user = $request->user()->load(['departamento', 'permissions', 'roles']);
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

    /**
     * Listar usuarios
     */
    public function users(Request $request): JsonResponse
    {
        try {
            $query = User::with(['departamento', 'permissions'])->orderByName();

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

    /**
     * Obtener un usuario específico
     */
    public function showUser(string $run): JsonResponse
    {
        try {
            $user = User::where('run', $run)->with(['departamento', 'permissions', 'roles'])->firstOrFail();
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

    /**
     * Crear usuario
     */
    public function createUser(Request $request): JsonResponse
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

    /**
     * Actualizar usuario
     */
    public function updateUser(Request $request, string $run): JsonResponse
    {
        try {
            $user = User::where('run', $run)->firstOrFail();

            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'nombre' => 'sometimes|required|string|max:255',
                'correo' => 'sometimes|required|email|unique:users,correo,' . $user->run . ',run',
                'contrasena' => 'nullable|string|min:8',
                'id_depto' => 'sometimes|required|exists:departamentos,id_depto',
                'permissions' => 'sometimes|array',
                'permissions.*' => 'exists:permissions,name'
            ]);

            if ($validator->fails()) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de validación incorrectos',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Actualizar campos
            if ($request->has('nombre')) {
                $user->nombre = $request->nombre;
            }
            if ($request->has('correo')) {
                $user->correo = $request->correo;
            }
            if ($request->has('id_depto')) {
                $user->id_depto = $request->id_depto;
            }
            if ($request->has('contrasena') && !empty($request->contrasena)) {
                $user->contrasena = Hash::make($request->contrasena);
            }

            $user->save();

            // Sincronizar permisos
            if ($request->has('permissions')) {
                $user->syncPermissions($request->permissions);
                
                // Limpiar caché de permisos
                app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
                $user->forgetCachedPermissions();
                Cache::forget("spatie.permission.cache.user.{$user->run}");

                // Si es el usuario actual, actualizar sesión
                if (Auth::check() && Auth::user()->run === $user->run) {
                    $freshUser = User::with(['permissions', 'roles'])
                        ->where('run', $user->run)
                        ->first();
                    if ($freshUser) {
                        Auth::setUser($freshUser);
                        request()->session()->regenerate(false);
                    }
                }
            }

            DB::commit();

            $user->refresh();
            $user->load(['departamento', 'permissions', 'roles']);

            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Usuario actualizado exitosamente'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar usuario
     */
    public function deleteUser(string $run): JsonResponse
    {
        try {
            $user = User::where('run', $run)->firstOrFail();
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

    /**
     * Obtener departamentos (para formularios de usuarios)
     */
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

    /**
     * Obtener permisos (para formularios de usuarios)
     */
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

