<?php
namespace App\Http\Controllers;
use App\Models\Departamento;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
class DepartamentoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar formulario de creación
     */
    public function create(): View
    {
        return view('layouts.departamento.departamento_create');
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Departamento $departamento): View
    {
        return view('layouts.departamento.departamento_update', compact('departamento'));
    }
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Departamento::withCount('users');
            if ($request->has('search')) {
                $search = $request->get('search');
                $query->where('nombre_depto', 'like', "%{$search}%");
            }
            $departamentos = $query->orderByName()->paginate($request->get('per_page', 15));
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
    public function show(Departamento $departamento): JsonResponse
    {
        try {
            // Cargar solo la relación users que siempre debería existir
            // No cargar insumos porque puede requerir una tabla pivot que no existe
            $departamento->loadMissing(['users']);

            return response()->json([
                'success' => true,
                'data' => [
                    'id_depto' => $departamento->id_depto,
                    'nombre_depto' => $departamento->nombre_depto,
                ],
                'message' => 'Departamento obtenido exitosamente'
            ]);
        } catch (\Exception $e) {
        
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener departamento: ' . $e->getMessage()
            ], 500);
        }
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_depto' => 'required|string|max:20|unique:departamentos,id_depto',
                'nombre_depto' => 'required|string|max:255'
            ]);

            if ($validator->fails()) {
                // Si es una petición AJAX, retornar JSON
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Datos de validación incorrectos',
                        'errors' => $validator->errors()
                    ], 422);
                }

                // Si es una petición web normal, redirigir con errores
                return back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Por favor, corrige los errores en el formulario.');
            }

            $departamento = Departamento::create($request->all());

            // Si es una petición AJAX, retornar JSON
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => $departamento,
                    'message' => 'Departamento creado exitosamente'
                ], 201);
            }

            // Si es una petición web normal, redirigir con mensaje de éxito
            return redirect()->route('departamentos')
                ->with('success', "¡Excelente! El departamento '{$departamento->nombre_depto}' ha sido creado exitosamente.");

        } catch (\Exception $e) {
            Log::error('Error al crear departamento: ' . $e->getMessage());

            // Si es una petición AJAX, retornar JSON
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear departamento: ' . $e->getMessage()
                ], 500);
            }

            // Si es una petición web normal, redirigir con error
            return back()
                ->withInput()
                ->with('error', 'Ocurrió un error al crear el departamento. Por favor, intenta nuevamente.');
        }
    }
    public function update(Request $request, Departamento $departamento)
    {
        try {
            // Obtener datos del request (puede ser JSON o FormData)
            $requestData = $request->all();

            // Si es JSON, también intentar obtener del JSON
            if ($request->isJson() || $request->expectsJson()) {
                $jsonData = $request->json()->all();
                $requestData = array_merge($requestData, $jsonData);
            }

            // Log para debug
            Log::info('Actualizando departamento', [
                'departamento_id' => $departamento->id_depto,
                'request_all' => $request->all(),
                'request_data' => $requestData,
                'nombre_depto' => $requestData['nombre_depto'] ?? null,
                'is_json' => $request->isJson(),
                'content_type' => $request->header('Content-Type')
            ]);

            // Obtener el nombre_depto del request
            $nombreDepto = $requestData['nombre_depto'] ?? $request->input('nombre_depto');

            // Validar que nombre_depto esté presente y no sea vacío
            if (empty($nombreDepto) || trim($nombreDepto) === '') {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'El nombre del departamento es requerido'
                    ], 422);
                }
                return back()
                    ->withInput()
                    ->with('error', 'El nombre del departamento es requerido.');
            }

            // Validación completa
            $validator = Validator::make(['nombre_depto' => $nombreDepto], [
                'nombre_depto' => 'required|string|max:255'
            ]);

            if ($validator->fails()) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Datos de validación incorrectos',
                        'errors' => $validator->errors()
                    ], 422);
                }

                return back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Por favor, corrige los errores en el formulario.');
            }

            // Guardar el nombre anterior antes de actualizar
            $nombreAnterior = $departamento->nombre_depto;
            $nombreNuevo = trim($nombreDepto);

            // Solo actualizar el nombre_depto si es diferente
            if ($nombreAnterior !== $nombreNuevo) {
                $departamento->nombre_depto = $nombreNuevo;
                $departamento->save();

                // Recargar el modelo para obtener los datos actualizados
                $departamento->refresh();
            }

            // Si es una petición AJAX, retornar JSON
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => $departamento,
                    'nombre_anterior' => $nombreAnterior,
                    'nombre_nuevo' => $nombreNuevo,
                    'message' => 'Departamento actualizado exitosamente'
                ]);
            }

            // Si es una petición web normal, redirigir con mensaje de éxito
            if ($nombreAnterior !== $nombreNuevo) {
                $mensaje = "El departamento \"{$nombreAnterior}\" fue renombrado a \"{$nombreNuevo}\".";
            } else {
                $mensaje = "Departamento \"{$nombreNuevo}\" actualizado.";
            }

            return redirect()->route('departamentos')
                ->with('success', $mensaje);

        } catch (\Exception $e) {
            Log::error('Error al actualizar departamento: ' . $e->getMessage());

            // Si es una petición AJAX, retornar JSON
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar departamento: ' . $e->getMessage()
                ], 500);
            }

            // Si es una petición web normal, redirigir con error
            return back()
                ->withInput()
                ->with('error', 'Ocurrió un error al actualizar el departamento. Por favor, intenta nuevamente.');
        }
    }
    public function destroy(Departamento $departamento): JsonResponse
    {
        try {
            // Guardar el nombre antes de eliminar para el mensaje
            $nombreDepartamento = $departamento->nombre_depto;

            // Con eliminación en cascada, los usuarios y solicitudes se eliminarán automáticamente
            $departamento->delete();

            return response()->json([
                'success' => true,
                'nombre_depto' => $nombreDepartamento,
                'message' => 'Departamento eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar departamento: ' . $e->getMessage()
            ], 500);
        }
    }
}
