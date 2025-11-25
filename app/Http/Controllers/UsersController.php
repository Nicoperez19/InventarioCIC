<?php
namespace App\Http\Controllers;
use App\Models\Departamento;
use App\Models\User;
use App\Services\BarcodeService;
use App\Services\UserService;
use Illuminate\Http\BinaryFileResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
            $query = User::with(['departamento'])
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
            // Cargar relaciones incluyendo permisos de Spatie
            $user->load(['departamento', 'permissions', 'roles']);
            
            // IMPORTANTE: Para el modal de edición, mostrar SOLO los permisos DIRECTOS
            // No incluir permisos de roles, porque solo podemos editar permisos directos
            // Los permisos de roles se gestionan editando el rol, no el usuario
            $directPermissions = $user->getDirectPermissions()->pluck('name')->toArray();
            
            \Illuminate\Support\Facades\Log::info('Obteniendo permisos del usuario para edición', [
                'user_run' => $user->run,
                'direct_permissions' => $directPermissions,
                'direct_permissions_count' => count($directPermissions),
                'all_permissions' => $user->getAllPermissions()->pluck('name')->toArray(),
                'all_permissions_count' => $user->getAllPermissions()->count(),
            ]);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'run' => $user->run,
                    'nombre' => $user->nombre,
                    'correo' => $user->correo,
                    'id_depto' => $user->id_depto,
                    'departamento' => $user->departamento,
                    // Solo permisos DIRECTOS para edición
                    'permissions' => $directPermissions,
                ],
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
                'permissions' => 'nullable|array',
                'permissions.*' => 'exists:permissions,name',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de validación incorrectos',
                    'errors' => $validator->errors()
                ], 422);
            }
            $userData = $request->only(['run', 'nombre', 'correo', 'contrasena', 'id_depto']);
            // No hashear aquí, el mutator setContrasenaAttribute del modelo se encarga
            $user = User::create($userData);
            
            // Asignar permisos si se proporcionan (Spatie Permission)
            if ($request->has('permissions') && is_array($request->permissions)) {
                // syncPermissions acepta array de nombres de permisos
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
                'permissions' => 'nullable|array',
                'permissions.*' => 'exists:permissions,name',
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
                    
                    // Si había contraseña, actualizarla usando el modelo para que el mutator la hashee
                    if ($request->has('contrasena') && !empty(trim($request->input('contrasena')))) {
                        $user->contrasena = $request->input('contrasena');
                        $user->save();
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
            
            // Actualizar permisos SIEMPRE (Spatie Permission)
            // Si no se envía el campo permissions, mantener los permisos actuales
            // Si se envía (incluso vacío), sincronizar con lo que se envió
            $permissionsUpdated = false;
            $isCurrentUser = Auth::check() && Auth::user()->run === $user->run;
            
            // Obtener permisos actuales antes de la actualización
            $permissionsBefore = $user->getDirectPermissions()->pluck('name')->toArray();
            
            // Log de lo que se recibe
            \Illuminate\Support\Facades\Log::info('Actualizando permisos del usuario', [
                'user_run' => $user->run,
                'has_permissions_key' => $request->has('permissions'),
                'permissions_received' => $request->input('permissions'),
                'permissions_is_array' => is_array($request->input('permissions')),
                'permissions_count' => is_array($request->input('permissions')) ? count($request->input('permissions')) : 0,
                'permissions_before' => $permissionsBefore,
            ]);
            
            // SIEMPRE procesar permisos si se enviaron en la petición
            if ($request->has('permissions')) {
                $permissionsToSync = [];
                
                // Si es un array, usar ese array (puede estar vacío)
                if (is_array($request->permissions)) {
                    $permissionsToSync = $request->permissions;
                } elseif ($request->permissions !== null) {
                    // Si no es array pero tiene valor, convertirlo a array
                    $permissionsToSync = [$request->permissions];
                }
                
                \Illuminate\Support\Facades\Log::info('Sincronizando permisos', [
                    'user_run' => $user->run,
                    'permissions_to_sync' => $permissionsToSync,
                    'count' => count($permissionsToSync),
                    'is_empty' => empty($permissionsToSync)
                ]);
                
                // syncPermissions acepta array de nombres de permisos
                // Si el array está vacío, se eliminarán todos los permisos directos (pero no los de roles)
                $user->syncPermissions($permissionsToSync);
                
                // Recargar el usuario para obtener los permisos actualizados
                $user->refresh();
                $user->load('permissions');
                
                // Limpiar caché de permisos de Spatie
                app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
                $user->forgetCachedPermissions();
                $permissionsUpdated = true;
                
                $permissionsAfter = $user->getDirectPermissions()->pluck('name')->toArray();
                
                \Illuminate\Support\Facades\Log::info('Permisos sincronizados exitosamente', [
                    'user_run' => $user->run,
                    'permissions_before' => $permissionsBefore,
                    'permissions_after' => $permissionsAfter,
                    'changed' => $permissionsBefore !== $permissionsAfter
                ]);
                
                // Si es el usuario actual, actualizar la sesión para que los cambios se reflejen inmediatamente
                if ($isCurrentUser) {
                    // Limpiar todo el caché de permisos
                    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
                    
                    // Recargar el usuario con permisos frescos desde la base de datos
                    $freshUser = User::with(['permissions', 'roles'])
                        ->where('run', $user->run)
                        ->first();
                    if ($freshUser) {
                        // Limpiar caché específico del usuario
                        $freshUser->forgetCachedPermissions();
                        
                        // Actualizar el usuario en la sesión
                        Auth::setUser($freshUser);
                        
                        // Regenerar sesión para asegurar que los cambios se reflejen
                        request()->session()->regenerate(false);
                        
                        // Limpiar caché de Laravel también
                        \Illuminate\Support\Facades\Cache::forget("spatie.permission.cache.user.{$user->run}");
                    }
                }
            }
            
            DB::commit();
            
            // Recargar el modelo con relaciones
            $user->refresh();
            $user->load(['departamento', 'permissions']);
            
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
            
            $response = response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Usuario actualizado exitosamente',
                'cambios' => $cambios,
                'is_current_user' => $isCurrentUser,
                'permissions_updated' => $permissionsUpdated,
                'session_updated' => $isCurrentUser && $permissionsUpdated
            ]);
            
            return $response;
            
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
            // Obtener todos los permisos usando Spatie Permission
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
     * Genera un código de barras para un usuario
     */
    public function generateBarcode(User $user): JsonResponse
    {
        try {
            $barcodeService = new BarcodeService();
            
            // Eliminar todas las imágenes anteriores del usuario si existen
            if ($user->codigo_barra) {
                $barcodeService->deleteUserBarcodeImage($user->codigo_barra);
            }
            $barcodeService->deleteAllUserBarcodeImages($user);
            
            // Generar nuevo código de barras basado en el RUN
            $codigoBarra = $barcodeService->generateUserBarcode($user->run);
            
            // Actualizar el usuario con el nuevo código
            $user->codigo_barra = $codigoBarra;
            $user->save();
            
            // Generar las imágenes (PNG y SVG)
            $barcodeService->generateUserBarcodeImage($codigoBarra);
            $barcodeService->generateUserBarcodeSVG($codigoBarra);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'codigo_barra' => $codigoBarra,
                    'url' => $barcodeService->getUserBarcodeUrl($codigoBarra)
                ],
                'message' => 'Código de barras generado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al generar código de barras: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Muestra la imagen PNG del código de barras de un usuario
     */
    public function downloadBarcodeImage(User $user)
    {
        if (!$user->codigo_barra) {
            abort(404, 'El usuario no tiene código de barras asignado');
        }

        $barcodeService = new BarcodeService();
        
        // Asegurar que la imagen existe
        $imagePath = $barcodeService->generateUserBarcodeImage($user->codigo_barra);
        $fullPath = storage_path('app/public/' . $imagePath);

        if (!file_exists($fullPath)) {
            abort(404, 'No se pudo generar la imagen del código de barras');
        }

        // Si es una petición AJAX o para mostrar, devolver la imagen
        if (request()->wantsJson() || request()->has('preview')) {
            return response()->file($fullPath, ['Content-Type' => 'image/png']);
        }

        // Si es para descargar, usar download
        return response()->download($fullPath, "codigo_barras_{$user->run}.png");
    }

    /**
     * Descarga la imagen SVG del código de barras de un usuario
     */
    public function downloadBarcodeSvg(User $user): BinaryFileResponse
    {
        if (!$user->codigo_barra) {
            abort(404, 'El usuario no tiene código de barras asignado');
        }

        $barcodeService = new BarcodeService();
        $imagePath = $barcodeService->generateUserBarcodeSVG($user->codigo_barra);
        $fullPath = storage_path('app/public/' . $imagePath);

        if (!file_exists($fullPath)) {
            abort(404, 'No se pudo generar la imagen SVG del código de barras');
        }

        return response()->download($fullPath, "codigo_barras_{$user->run}.svg");
    }

    /**
     * Genera códigos de barras para todos los usuarios
     * Elimina todas las imágenes existentes y genera nuevos códigos únicos
     */
    public function generateAllBarcodes(): JsonResponse
    {
        try {
            DB::beginTransaction();
            
            $barcodeService = new BarcodeService();
            
            // Obtener todos los usuarios
            $users = User::all();
            
            // Eliminar todas las imágenes de códigos de barras de usuarios
            $directory = "codigos_usuarios";
            if (Storage::disk('public')->exists($directory)) {
                $files = Storage::disk('public')->files($directory);
                foreach ($files as $file) {
                    Storage::disk('public')->delete($file);
                }
            }
            
            // Generar códigos únicos para cada usuario
            $generated = 0;
            $errors = [];
            $usedBarcodes = [];
            
            foreach ($users as $user) {
                try {
                    // Generar código de barras basado en el RUN
                    $codigoBarra = $barcodeService->generateUserBarcode($user->run);
                    
                    // Verificar que el código no esté duplicado
                    $attempts = 0;
                    while (in_array($codigoBarra, $usedBarcodes) || User::where('codigo_barra', $codigoBarra)->where('run', '!=', $user->run)->exists()) {
                        // Si hay duplicado, agregar un sufijo único
                        $codigoBarra = $barcodeService->generateUserBarcode($user->run . '-' . $attempts);
                        $attempts++;
                        
                        // Prevenir bucle infinito
                        if ($attempts > 100) {
                            throw new \Exception("No se pudo generar un código único para el usuario {$user->run} después de 100 intentos");
                        }
                    }
                    
                    // Actualizar el usuario con el nuevo código
                    $user->codigo_barra = $codigoBarra;
                    $user->save();
                    
                    // Agregar a la lista de códigos usados
                    $usedBarcodes[] = $codigoBarra;
                    
                    // Generar las imágenes (PNG y SVG)
                    $barcodeService->generateUserBarcodeImage($codigoBarra);
                    $barcodeService->generateUserBarcodeSVG($codigoBarra);
                    
                    $generated++;
                } catch (\Exception $e) {
                    $errors[] = [
                        'user' => $user->run,
                        'nombre' => $user->nombre,
                        'error' => $e->getMessage()
                    ];
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => "Se generaron códigos de barras para {$generated} usuario(s)" . (count($errors) > 0 ? ". Hubo " . count($errors) . " error(es)." : ""),
                'data' => [
                    'generated' => $generated,
                    'total' => $users->count(),
                    'errors' => $errors
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al generar códigos de barras: ' . $e->getMessage()
            ], 500);
        }
    }
}
