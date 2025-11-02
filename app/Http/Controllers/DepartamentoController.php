<?php
namespace App\Http\Controllers;
use App\Models\Departamento;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
class DepartamentoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
            \Log::error('Error al obtener departamento', [
                'departamento_id' => $departamento->id_depto ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener departamento: ' . $e->getMessage()
            ], 500);
        }
    }
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_depto' => 'required|string|max:20|unique:departamentos,id_depto',
                'nombre_depto' => 'required|string|max:255'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de validación incorrectos',
                    'errors' => $validator->errors()
                ], 422);
            }
            $departamento = Departamento::create($request->all());
            return response()->json([
                'success' => true,
                'data' => $departamento,
                'message' => 'Departamento creado exitosamente'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear departamento: ' . $e->getMessage()
            ], 500);
        }
    }
    public function update(Request $request, Departamento $departamento): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_depto' => 'sometimes|string|max:20|unique:departamentos,id_depto,' . $departamento->id_depto,
                'nombre_depto' => 'sometimes|string|max:255'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de validación incorrectos',
                    'errors' => $validator->errors()
                ], 422);
            }
            $departamento->update($request->all());
            return response()->json([
                'success' => true,
                'data' => $departamento,
                'message' => 'Departamento actualizado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar departamento: ' . $e->getMessage()
            ], 500);
        }
    }
    public function destroy(Departamento $departamento): JsonResponse
    {
        try {
            if ($departamento->hasActiveUsers()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar el departamento porque tiene usuarios activos'
                ], 422);
            }
            $departamento->delete();
            return response()->json([
                'success' => true,
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
