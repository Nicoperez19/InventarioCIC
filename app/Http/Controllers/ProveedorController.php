<?php
namespace App\Http\Controllers;
use App\Models\Proveedor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class ProveedorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Proveedor::withCount('facturas')
                ->withSum('facturas', 'monto_total')
                ->orderBy('nombre_proveedor');
            if ($request->has('search')) {
                $search = $request->get('search');
                $query->where(function($q) use ($search) {
                    $q->where('nombre_proveedor', 'like', "%{$search}%")
                      ->orWhere('rut', 'like', "%{$search}%")
                      ->orWhere('telefono', 'like', "%{$search}%");
                });
            }
            $proveedores = $query->paginate($request->get('per_page', 15));
            return response()->json([
                'success' => true,
                'data' => $proveedores,
                'message' => 'Proveedores obtenidos exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener proveedores: ' . $e->getMessage()
            ], 500);
        }
    }
    public function show(Proveedor $proveedor): JsonResponse
    {
        try {
            $proveedor->load(['facturas' => function($query) {
                $query->orderBy('fecha_factura', 'desc');
            }]);
            return response()->json([
                'success' => true,
                'data' => $proveedor,
                'message' => 'Proveedor obtenido exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener proveedor: ' . $e->getMessage()
            ], 500);
        }
    }
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'rut' => 'required|string|max:20|unique:proveedores,rut',
                'nombre_proveedor' => 'required|string|max:255',
                'telefono' => 'nullable|string|max:20'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de validación incorrectos',
                    'errors' => $validator->errors()
                ], 422);
            }
            $proveedor = Proveedor::create($request->all());
            return response()->json([
                'success' => true,
                'data' => $proveedor,
                'message' => 'Proveedor creado exitosamente'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear proveedor: ' . $e->getMessage()
            ], 500);
        }
    }
    public function update(Request $request, Proveedor $proveedor): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'rut' => 'sometimes|string|max:20|unique:proveedores,rut,' . $proveedor->id,
                'nombre_proveedor' => 'sometimes|string|max:255',
                'telefono' => 'nullable|string|max:20'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de validación incorrectos',
                    'errors' => $validator->errors()
                ], 422);
            }
            $proveedor->update($request->all());
            return response()->json([
                'success' => true,
                'data' => $proveedor,
                'message' => 'Proveedor actualizado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar proveedor: ' . $e->getMessage()
            ], 500);
        }
    }
    public function destroy(Proveedor $proveedor): JsonResponse
    {
        try {
            if ($proveedor->tieneFacturas()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar el proveedor porque tiene facturas asociadas'
                ], 422);
            }
            $proveedor->delete();
            return response()->json([
                'success' => true,
                'message' => 'Proveedor eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar proveedor: ' . $e->getMessage()
            ], 500);
        }
    }
}
