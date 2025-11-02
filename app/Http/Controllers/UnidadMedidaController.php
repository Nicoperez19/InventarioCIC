<?php
namespace App\Http\Controllers;
use App\Models\UnidadMedida;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class UnidadMedidaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request): JsonResponse
    {
        try {
            $query = UnidadMedida::withCount('insumos');
            if ($request->has('search')) {
                $search = $request->get('search');
                $query->where('nombre_unidad_medida', 'like', "%{$search}%");
            }
            $unidades = $query->orderByName()
                ->paginate($request->get('per_page', 15));
            return response()->json([
                'success' => true,
                'data' => $unidades,
                'message' => 'Unidades de medida obtenidas exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener unidades de medida: ' . $e->getMessage()
            ], 500);
        }
    }
    public function show(UnidadMedida $unidadMedida): JsonResponse
    {
        try {
            // No cargar relaciones innecesarias, solo devolver los datos básicos
            return response()->json([
                'success' => true,
                'data' => [
                    'id_unidad' => $unidadMedida->id_unidad,
                    'nombre_unidad_medida' => $unidadMedida->nombre_unidad_medida,
                ],
                'message' => 'Unidad de medida obtenida exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener unidad de medida: ' . $e->getMessage()
            ], 500);
        }
    }
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_unidad' => 'required|string|max:20|unique:unidad_medidas,id_unidad',
                'nombre_unidad_medida' => 'required|string|max:255'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de validación incorrectos',
                    'errors' => $validator->errors()
                ], 422);
            }
            $unidadMedida = UnidadMedida::create($request->all());
            return response()->json([
                'success' => true,
                'data' => $unidadMedida,
                'message' => 'Unidad de medida creada exitosamente'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear unidad de medida: ' . $e->getMessage()
            ], 500);
        }
    }
    public function update(Request $request, UnidadMedida $unidadMedida): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_unidad' => 'sometimes|string|max:20|unique:unidad_medidas,id_unidad,' . $unidadMedida->id_unidad,
                'nombre_unidad_medida' => 'sometimes|string|max:255'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de validación incorrectos',
                    'errors' => $validator->errors()
                ], 422);
            }
            $unidadMedida->update($request->all());
            return response()->json([
                'success' => true,
                'data' => $unidadMedida,
                'message' => 'Unidad de medida actualizada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar unidad de medida: ' . $e->getMessage()
            ], 500);
        }
    }
    public function destroy(UnidadMedida $unidadMedida): JsonResponse
    {
        try {
            if ($unidadMedida->hasActiveInsumos()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar la unidad de medida porque tiene insumos asociados'
                ], 422);
            }
            $unidadMedida->delete();
            return response()->json([
                'success' => true,
                'message' => 'Unidad de medida eliminada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar unidad de medida: ' . $e->getMessage()
            ], 500);
        }
    }
}
