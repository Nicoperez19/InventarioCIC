<?php
namespace App\Http\Controllers;
use App\Models\Departamento;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            // Log de datos recibidos - verificar tanto all() como input()
            \Illuminate\Support\Facades\Log::info('Datos recibidos para actualizar usuario', [
                'run' => $user->run,
                'request_all' => $request->all(),
                'request_input_id_depto' => $request->input('id_depto'),
                'request_get_id_depto' => $request->get('id_depto'),
                'request_post_id_depto' => $request->post('id_depto'),
                'request_json' => $request->json()->all(),
                'content_type' => $request->header('Content-Type'),
                'method' => $request->method(),
                'id_depto_filled' => $request->filled('id_depto'),
                'id_depto_has' => $request->has('id_depto'),
                'all_inputs' => $request->input(),
            ]);
            
            DB::beginTransaction();
            
            // Validar datos
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
                \Illuminate\Support\Facades\Log::error('Validación fallida', [
                    'errors' => $validator->errors()->toArray()
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de validación incorrectos',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Guardar valores originales para logging
            $valoresOriginales = [
                'nombre' => $user->nombre,
                'correo' => $user->correo,
                'id_depto' => $user->id_depto,
            ];
            
            // Actualizar campos - siempre actualizar si están presentes en el request
            $camposActualizados = false;
            
            if ($request->has('nombre')) {
                $nuevoNombre = trim($request->input('nombre'));
                if ($nuevoNombre !== '' && $nuevoNombre !== $user->nombre) {
                    $user->nombre = $nuevoNombre;
                    $camposActualizados = true;
                }
            }
            
            if ($request->has('correo')) {
                $nuevoCorreo = trim($request->input('correo'));
                if ($nuevoCorreo !== '' && $nuevoCorreo !== $user->correo) {
                    $user->correo = $nuevoCorreo;
                    $camposActualizados = true;
                }
            }
            
            if ($request->has('id_depto')) {
                $nuevoDepto = trim($request->input('id_depto'));
                if ($nuevoDepto !== '' && $nuevoDepto !== $user->id_depto) {
                    \Illuminate\Support\Facades\Log::info('Actualizando id_depto', [
                        'valor_anterior' => $valoresOriginales['id_depto'],
                        'valor_nuevo' => $nuevoDepto,
                        'son_diferentes' => $nuevoDepto !== $user->id_depto
                    ]);
                    $user->id_depto = $nuevoDepto;
                    $camposActualizados = true;
                }
            }
            
            // Actualizar contraseña solo si se proporciona y no está vacía
            if ($request->has('contrasena') && !empty(trim($request->input('contrasena')))) {
                $user->setAttribute('contrasena', $request->input('contrasena')); // El mutator se encargará del hash
                $camposActualizados = true;
            }
            
            // Log antes de guardar
            \Illuminate\Support\Facades\Log::info('Estado del modelo antes de guardar', [
                'nombre' => $user->nombre,
                'correo' => $user->correo,
                'id_depto' => $user->id_depto,
                'isDirty' => $user->isDirty(),
                'getDirty' => $user->getDirty(),
                'camposActualizados' => $camposActualizados,
            ]);
            
            // Guardar cambios - usar update directo en la base de datos para asegurar que se guarde
            if ($camposActualizados || $user->isDirty()) {
                $dirtyAttributes = $user->getDirty();
                
                \Illuminate\Support\Facades\Log::info('Atributos dirty antes de guardar', [
                    'dirty' => $dirtyAttributes,
                    'original' => $user->getOriginal()
                ]);
                
                // Si hay cambios, actualizar directamente en la base de datos
                if (!empty($dirtyAttributes)) {
                    // Remover 'contrasena' de dirty si está presente (ya se procesó)
                    if (isset($dirtyAttributes['contrasena'])) {
                        unset($dirtyAttributes['contrasena']);
                    }
                    
                    // Actualizar campos directamente
                    if (!empty($dirtyAttributes)) {
                        DB::table('users')
                            ->where('run', $user->run)
                            ->update($dirtyAttributes);
                    }
                    
                    // Si había contraseña, actualizarla por separado
                    if ($request->has('contrasena') && !empty(trim($request->input('contrasena')))) {
                        DB::table('users')
                            ->where('run', $user->run)
                            ->update(['contrasena' => Hash::make($request->input('contrasena'))]);
                    }
                }
                
                // Recargar el modelo
                $user->refresh();
                
                \Illuminate\Support\Facades\Log::info('Resultado del guardado', [
                    'id_depto_despues' => $user->id_depto,
                    'nombre_despues' => $user->nombre,
                    'correo_despues' => $user->correo,
                ]);
            } else {
                \Illuminate\Support\Facades\Log::warning('No hay cambios para guardar', [
                    'request_data' => $request->all(),
                    'user_actual' => [
                        'nombre' => $user->nombre,
                        'correo' => $user->correo,
                        'id_depto' => $user->id_depto,
                    ]
                ]);
            }
            
            // Sincronizar permisos si se proporcionan
            if ($request->has('permissions') && is_array($request->permissions)) {
                // Sincronizar permisos en la base de datos
                $user->syncPermissions($request->permissions);
                
                // LIMPIAR TODA LA CACHÉ DE PERMISOS - CRÍTICO
                app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
                
                // Limpiar caché específica del usuario
                $user->forgetCachedPermissions();
                \Illuminate\Support\Facades\Cache::forget("spatie.permission.cache.user.{$user->run}");
                
                // Si el usuario actualizado es el usuario autenticado, actualizar su sesión
                if (Auth::check() && Auth::user()->run === $user->run) {
                    // Recargar el usuario desde la BD con permisos frescos
                    $freshUser = User::with(['permissions', 'roles'])
                        ->where('run', $user->run)
                        ->first();
                    
                    if ($freshUser) {
                        // Actualizar la sesión con el usuario fresco
                        // NO regenerar la sesión para evitar invalidar el token CSRF
                        // Solo actualizar el usuario en la sesión actual
                        Auth::setUser($freshUser);
                        
                        // Recargar las relaciones del usuario en la sesión
                        $freshUser->load(['permissions', 'roles']);
                        
                        // La sesión se guarda automáticamente, no necesitamos hacer nada más
                        // Esto preserva el token CSRF actual
                    }
                }
                
                \Illuminate\Support\Facades\Log::info('Permisos actualizados', [
                    'user_run' => $user->run,
                    'permissions' => $request->permissions,
                    'permissions_count' => count($request->permissions),
                    'is_current_user' => Auth::check() && Auth::user()->run === $user->run
                ]);
            }
            
            DB::commit();
            
            // Recargar el modelo con relaciones
            $user->refresh();
            $user->load(['departamento', 'permissions', 'roles']);
            
            // Log de cambios realizados
            $cambios = [];
            if ($valoresOriginales['nombre'] !== $user->nombre) {
                $cambios['nombre'] = ['antes' => $valoresOriginales['nombre'], 'después' => $user->nombre];
            }
            if ($valoresOriginales['correo'] !== $user->correo) {
                $cambios['correo'] = ['antes' => $valoresOriginales['correo'], 'después' => $user->correo];
            }
            if ($valoresOriginales['id_depto'] !== $user->id_depto) {
                $cambios['id_depto'] = ['antes' => $valoresOriginales['id_depto'], 'después' => $user->id_depto];
            }
            
            \Illuminate\Support\Facades\Log::info('Usuario actualizado exitosamente', [
                'run' => $user->run,
                'cambios' => $cambios,
                'usuario_actualizado_por' => Auth::id()
            ]);
            
            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Usuario actualizado exitosamente',
                'cambios' => $cambios
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Error al actualizar usuario', [
                'run' => $user->run ?? 'desconocido',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
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
