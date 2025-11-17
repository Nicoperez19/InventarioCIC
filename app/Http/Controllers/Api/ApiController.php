<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CargaMasiva;
use App\Models\Departamento;
use App\Models\Factura;
use App\Models\Insumo;
use App\Models\Proveedor;
use App\Models\Solicitud;
use App\Models\SolicitudItem;
use App\Models\TipoInsumo;
use App\Models\UnidadMedida;
use App\Services\ExcelImportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ApiController extends Controller
{
    // ============================================
    // DEPARTAMENTOS
    // ============================================
    
    public function departamentos(Request $request): JsonResponse
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

    public function showDepartamento(string $id): JsonResponse
    {
        try {
            $departamento = Departamento::where('id_depto', $id)->firstOrFail();
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

    public function createDepartamento(Request $request): JsonResponse
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

    public function updateDepartamento(Request $request, string $id): JsonResponse
    {
        try {
            $departamento = Departamento::where('id_depto', $id)->firstOrFail();
            
            $validator = Validator::make($request->all(), [
                'nombre_depto' => 'sometimes|required|string|max:255'
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

    public function deleteDepartamento(string $id): JsonResponse
    {
        try {
            $departamento = Departamento::where('id_depto', $id)->firstOrFail();
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

    // ============================================
    // UNIDADES DE MEDIDA
    // ============================================

    public function unidades(Request $request): JsonResponse
    {
        try {
            $query = UnidadMedida::withCount('insumos');
            if ($request->has('search')) {
                $search = $request->get('search');
                $query->where('nombre_unidad_medida', 'like', "%{$search}%");
            }
            $unidades = $query->orderByName()->paginate($request->get('per_page', 15));
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

    public function showUnidad(string $id): JsonResponse
    {
        try {
            $unidad = UnidadMedida::where('id_unidad', $id)->firstOrFail();
            return response()->json([
                'success' => true,
                'data' => [
                    'id_unidad' => $unidad->id_unidad,
                    'nombre_unidad_medida' => $unidad->nombre_unidad_medida,
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

    public function createUnidad(Request $request): JsonResponse
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

            $unidad = UnidadMedida::create($request->all());
            return response()->json([
                'success' => true,
                'data' => $unidad,
                'message' => 'Unidad de medida creada exitosamente'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear unidad de medida: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateUnidad(Request $request, string $id): JsonResponse
    {
        try {
            $unidad = UnidadMedida::where('id_unidad', $id)->firstOrFail();
            
            $validator = Validator::make($request->all(), [
                'nombre_unidad_medida' => 'sometimes|string|max:255'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de validación incorrectos',
                    'errors' => $validator->errors()
                ], 422);
            }

            $nombreAnterior = $unidad->nombre_unidad_medida;
            $nombreNuevo = trim($request->input('nombre_unidad_medida', $nombreAnterior));

            if ($nombreAnterior !== $nombreNuevo) {
                $unidad->nombre_unidad_medida = $nombreNuevo;
                $unidad->save();
            }

            return response()->json([
                'success' => true,
                'data' => $unidad,
                'nombre_anterior' => $nombreAnterior,
                'nombre_nuevo' => $nombreNuevo,
                'message' => 'Unidad de medida actualizada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar unidad de medida: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteUnidad(string $id): JsonResponse
    {
        try {
            $unidad = UnidadMedida::where('id_unidad', $id)->firstOrFail();
            $nombreUnidad = $unidad->nombre_unidad_medida;
            $unidad->delete();
            return response()->json([
                'success' => true,
                'nombre_unidad_medida' => $nombreUnidad,
                'message' => 'Unidad de medida eliminada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar unidad de medida: ' . $e->getMessage()
            ], 500);
        }
    }

    // ============================================
    // TIPOS DE INSUMO
    // ============================================

    public function tipoInsumos(Request $request): JsonResponse
    {
        try {
            $query = TipoInsumo::withCount('insumos')->orderBy('nombre_tipo');
            if ($request->has('search')) {
                $search = $request->get('search');
                $query->where('nombre_tipo', 'like', "%{$search}%");
            }
            $tiposInsumo = $query->paginate($request->get('per_page', 15));
            return response()->json([
                'success' => true,
                'data' => $tiposInsumo,
                'message' => 'Tipos de insumo obtenidos exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener tipos de insumo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function showTipoInsumo(int $id): JsonResponse
    {
        try {
            $tipoInsumo = TipoInsumo::with(['insumos' => function($query) {
                $query->orderBy('nombre_insumo');
            }])->findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $tipoInsumo,
                'message' => 'Tipo de insumo obtenido exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener tipo de insumo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function createTipoInsumo(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'nombre_tipo' => 'required|string|max:255|unique:tipo_insumos,nombre_tipo'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de validación incorrectos',
                    'errors' => $validator->errors()
                ], 422);
            }

            $tipoInsumo = TipoInsumo::create($request->all());
            return response()->json([
                'success' => true,
                'data' => $tipoInsumo,
                'message' => 'Tipo de insumo creado exitosamente'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear tipo de insumo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateTipoInsumo(Request $request, int $id): JsonResponse
    {
        try {
            $tipoInsumo = TipoInsumo::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'nombre_tipo' => 'sometimes|string|max:255|unique:tipo_insumos,nombre_tipo,' . $id
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de validación incorrectos',
                    'errors' => $validator->errors()
                ], 422);
            }

            $tipoInsumo->update($request->all());
            return response()->json([
                'success' => true,
                'data' => $tipoInsumo,
                'message' => 'Tipo de insumo actualizado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar tipo de insumo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteTipoInsumo(int $id): JsonResponse
    {
        try {
            $tipoInsumo = TipoInsumo::findOrFail($id);
            $tipoInsumo->delete();
            return response()->json([
                'success' => true,
                'message' => 'Tipo de insumo eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar tipo de insumo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getAllTipoInsumos(): JsonResponse
    {
        try {
            $tiposInsumo = TipoInsumo::orderBy('nombre_tipo')->get(['id', 'nombre_tipo']);
            return response()->json([
                'success' => true,
                'data' => $tiposInsumo,
                'message' => 'Tipos de insumo obtenidos exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener tipos de insumo: ' . $e->getMessage()
            ], 500);
        }
    }

    // ============================================
    // INSUMOS
    // ============================================

    public function insumos(Request $request): JsonResponse
    {
        try {
            $query = Insumo::with('unidadMedida');
            if ($request->has('search')) {
                $search = $request->get('search');
                $query->where(function($q) use ($search) {
                    $q->where('nombre_insumo', 'like', "%{$search}%")
                      ->orWhere('codigo_barra', 'like', "%{$search}%");
                });
            }
            if ($request->has('unidad')) {
                $query->where('id_unidad', $request->get('unidad'));
            }
            if ($request->has('stock_status')) {
                $status = $request->get('stock_status');
                match ($status) {
                    'low' => $query->where('stock_actual', '<=', 0),
                    'out' => $query->where('stock_actual', '<=', 0),
                    'normal' => $query->where('stock_actual', '>', 0),
                    default => null
                };
            }
            $insumos = $query->orderBy('nombre_insumo')->paginate($request->get('per_page', 15));
            return response()->json([
                'success' => true,
                'data' => $insumos,
                'message' => 'Insumos obtenidos exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener insumos: ' . $e->getMessage()
            ], 500);
        }
    }

    public function showInsumo(string $id): JsonResponse
    {
        try {
            $insumo = Insumo::where('id_insumo', $id)->with(['unidadMedida', 'departamentos'])->firstOrFail();
            return response()->json([
                'success' => true,
                'data' => $insumo,
                'message' => 'Insumo obtenido exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener insumo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function createInsumo(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_insumo' => 'required|string|max:20|unique:insumos,id_insumo',
                'codigo_barra' => 'nullable|string|max:50|unique:insumos,codigo_barra',
                'nombre_insumo' => 'required|string|max:255',
                'stock_actual' => 'required|integer|min:0',
                'id_unidad' => 'required|exists:unidad_medidas,id_unidad',
                'departamentos' => 'array',
                'departamentos.*' => 'exists:departamentos,id_depto'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de validación incorrectos',
                    'errors' => $validator->errors()
                ], 422);
            }

            $insumoData = $request->only(['id_insumo', 'codigo_barra', 'nombre_insumo', 'stock_actual', 'id_unidad']);
            $insumo = Insumo::create($insumoData);

            if ($request->has('departamentos')) {
                $insumo->departamentos()->sync($request->departamentos);
            }

            $insumo->load(['unidadMedida', 'departamentos']);
            return response()->json([
                'success' => true,
                'data' => $insumo,
                'message' => 'Insumo creado exitosamente'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear insumo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateInsumo(Request $request, string $id): JsonResponse
    {
        try {
            $insumo = Insumo::where('id_insumo', $id)->firstOrFail();
            
            $validator = Validator::make($request->all(), [
                'id_insumo' => 'sometimes|string|max:20|unique:insumos,id_insumo,' . $insumo->id_insumo,
                'codigo_barra' => 'nullable|string|max:50|unique:insumos,codigo_barra,' . $insumo->id_insumo,
                'nombre_insumo' => 'sometimes|string|max:255',
                'stock_actual' => 'sometimes|integer|min:0',
                'id_unidad' => 'sometimes|exists:unidad_medidas,id_unidad',
                'departamentos' => 'array',
                'departamentos.*' => 'exists:departamentos,id_depto'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de validación incorrectos',
                    'errors' => $validator->errors()
                ], 422);
            }

            $insumoData = $request->only(['id_insumo', 'codigo_barra', 'nombre_insumo', 'stock_actual', 'id_unidad']);
            $insumo->update($insumoData);

            if ($request->has('departamentos')) {
                $insumo->departamentos()->sync($request->departamentos);
            }

            $insumo->load(['unidadMedida', 'departamentos']);
            return response()->json([
                'success' => true,
                'data' => $insumo,
                'message' => 'Insumo actualizado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar insumo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteInsumo(string $id): JsonResponse
    {
        try {
            $insumo = Insumo::where('id_insumo', $id)->firstOrFail();
            $insumo->delete();
            return response()->json([
                'success' => true,
                'message' => 'Insumo eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar insumo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function adjustStock(Request $request, string $id): JsonResponse
    {
        try {
            $insumo = Insumo::where('id_insumo', $id)->firstOrFail();
            
            $validator = Validator::make($request->all(), [
                'cantidad' => 'required|integer',
                'tipo' => 'required|in:add,subtract,set',
                'observaciones' => 'nullable|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de validación incorrectos',
                    'errors' => $validator->errors()
                ], 422);
            }

            $cantidad = $request->cantidad;
            $tipo = $request->tipo;
            
            match ($tipo) {
                'add' => $insumo->addStock($cantidad),
                'subtract' => $insumo->canReduceStock($cantidad) 
                    ? $insumo->reduceStock($cantidad) 
                    : throw new \Exception('No hay suficiente stock para reducir'),
                'set' => $insumo->update(['stock_actual' => $cantidad])
            };

            return response()->json([
                'success' => true,
                'data' => $insumo,
                'message' => 'Stock ajustado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al ajustar stock: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getUnidadesMedida(): JsonResponse
    {
        try {
            $unidades = UnidadMedida::orderByName()->get();
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

    public function getLowStock(): JsonResponse
    {
        try {
            $insumos = Insumo::where('stock_actual', '<=', 0)
                ->with('unidadMedida')
                ->orderBy('nombre_insumo')
                ->get();
            return response()->json([
                'success' => true,
                'data' => $insumos,
                'message' => 'Insumos con stock bajo obtenidos exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener insumos con stock bajo: ' . $e->getMessage()
            ], 500);
        }
    }

    // ============================================
    // PROVEEDORES
    // ============================================

    public function proveedores(Request $request): JsonResponse
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

    public function showProveedor(int $id): JsonResponse
    {
        try {
            $proveedor = Proveedor::with(['facturas' => function($query) {
                $query->orderBy('fecha_factura', 'desc');
            }])->findOrFail($id);
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

    public function createProveedor(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'rut' => ['required', 'string', new \App\Rules\RunValidation(), 'unique:proveedores,rut'],
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

            $data = $request->all();
            $data['rut'] = \App\Helpers\RunFormatter::format($data['rut']);
            $proveedor = Proveedor::create($data);
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

    public function updateProveedor(Request $request, int $id): JsonResponse
    {
        try {
            $proveedor = Proveedor::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'rut' => ['sometimes', 'required', 'string', new \App\Rules\RunValidation(), 'unique:proveedores,rut,' . $id],
                'nombre_proveedor' => 'sometimes|required|string|max:255',
                'telefono' => 'nullable|string|max:20'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de validación incorrectos',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $request->all();
            if (isset($data['rut'])) {
                $data['rut'] = \App\Helpers\RunFormatter::format($data['rut']);
            }
            $proveedor->update($data);
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

    public function deleteProveedor(int $id): JsonResponse
    {
        try {
            $proveedor = Proveedor::findOrFail($id);
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

    public function getProveedoresSelect(): JsonResponse
    {
        try {
            $proveedores = Proveedor::orderBy('nombre_proveedor')->get(['id', 'nombre_proveedor', 'rut']);
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

    // ============================================
    // FACTURAS
    // ============================================

    public function facturas(Request $request): JsonResponse
    {
        try {
            $query = Factura::where('run', Auth::user()->run)
                ->with('proveedor')
                ->orderBy('created_at', 'desc');
            if ($request->has('search')) {
                $search = $request->get('search');
                $query->where(function($q) use ($search) {
                    $q->where('numero_factura', 'like', "%{$search}%")
                      ->orWhere('observaciones', 'like', "%{$search}%")
                      ->orWhereHas('proveedor', function($q) use ($search) {
                          $q->where('nombre_proveedor', 'like', "%{$search}%");
                      });
                });
            }
            if ($request->has('proveedor_id')) {
                $query->where('proveedor_id', $request->get('proveedor_id'));
            }
            if ($request->has('fecha_desde')) {
                $query->where('fecha_factura', '>=', $request->get('fecha_desde'));
            }
            if ($request->has('fecha_hasta')) {
                $query->where('fecha_factura', '<=', $request->get('fecha_hasta'));
            }
            $facturas = $query->paginate($request->get('per_page', 15));
            return response()->json([
                'success' => true,
                'data' => $facturas,
                'message' => 'Facturas obtenidas exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener facturas: ' . $e->getMessage()
            ], 500);
        }
    }

    public function showFactura(int $id): JsonResponse
    {
        try {
            $factura = Factura::with('proveedor')->findOrFail($id);
            if ($factura->run !== Auth::user()->run) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permisos para ver esta factura'
                ], 403);
            }
            return response()->json([
                'success' => true,
                'data' => $factura,
                'message' => 'Factura obtenida exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener factura: ' . $e->getMessage()
            ], 500);
        }
    }

    public function createFactura(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'numero_factura' => 'required|string|max:255|unique:facturas,numero_factura',
                'proveedor_id' => 'required|exists:proveedores,id',
                'monto_total' => 'required|numeric|min:0',
                'fecha_factura' => 'required|date',
                'archivo' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'observaciones' => 'nullable|string|max:1000'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de validación incorrectos',
                    'errors' => $validator->errors()
                ], 422);
            }

            $facturaData = $request->only(['numero_factura', 'proveedor_id', 'monto_total', 'fecha_factura', 'observaciones']);
            $facturaData['run'] = Auth::user()->run;

            if ($request->hasFile('archivo')) {
                $archivo = $request->file('archivo');
                $nombreArchivo = Str::slug($request->numero_factura) . '_' . time() . '.' . $archivo->getClientOriginalExtension();
                $ruta = $archivo->storeAs('facturas', $nombreArchivo, 'private');
                $facturaData['archivo_path'] = $ruta;
                $facturaData['archivo_nombre'] = $archivo->getClientOriginalName();
            }

            $factura = Factura::create($facturaData);
            $factura->load('proveedor');
            return response()->json([
                'success' => true,
                'data' => $factura,
                'message' => 'Factura creada exitosamente'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear factura: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateFactura(Request $request, int $id): JsonResponse
    {
        try {
            $factura = Factura::findOrFail($id);
            
            if ($factura->run !== Auth::user()->run) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permisos para editar esta factura'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'numero_factura' => 'sometimes|required|string|max:255|unique:facturas,numero_factura,' . $id,
                'proveedor_id' => 'sometimes|required|exists:proveedores,id',
                'monto_total' => 'sometimes|required|numeric|min:0',
                'fecha_factura' => 'sometimes|required|date',
                'archivo' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
                'observaciones' => 'nullable|string|max:1000'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de validación incorrectos',
                    'errors' => $validator->errors()
                ], 422);
            }

            $facturaData = $request->only(['numero_factura', 'proveedor_id', 'monto_total', 'fecha_factura', 'observaciones']);

            if ($request->hasFile('archivo')) {
                if ($factura->archivo_path && Storage::disk('private')->exists($factura->archivo_path)) {
                    Storage::disk('private')->delete($factura->archivo_path);
                }
                $archivo = $request->file('archivo');
                $proveedor = Proveedor::findOrFail($request->proveedor_id);
                $nombreArchivo = Str::slug($proveedor->nombre_proveedor) . '_' . time() . '.' . $archivo->getClientOriginalExtension();
                $ruta = $archivo->storeAs('facturas', $nombreArchivo, 'private');
                $facturaData['archivo_path'] = $ruta;
                $facturaData['archivo_nombre'] = $archivo->getClientOriginalName();
            }

            $factura->update($facturaData);
            $factura->load('proveedor');
            return response()->json([
                'success' => true,
                'data' => $factura,
                'message' => 'Factura actualizada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar factura: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteFactura(int $id): JsonResponse
    {
        try {
            $factura = Factura::findOrFail($id);
            
            if ($factura->run !== Auth::user()->run) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permisos para eliminar esta factura'
                ], 403);
            }

            if ($factura->archivo_path && Storage::disk('private')->exists($factura->archivo_path)) {
                Storage::disk('private')->delete($factura->archivo_path);
            }

            $factura->delete();
            return response()->json([
                'success' => true,
                'message' => 'Factura eliminada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar factura: ' . $e->getMessage()
            ], 500);
        }
    }

    public function downloadFactura(int $id): BinaryFileResponse|JsonResponse
    {
        try {
            $factura = Factura::findOrFail($id);
            
            if ($factura->run !== Auth::user()->run) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permisos para descargar esta factura'
                ], 403);
            }

            if (!$factura->archivo_path) {
                return response()->json([
                    'success' => false,
                    'message' => 'El archivo no existe'
                ], 404);
            }

            // Verificar si el disco es 'public' o 'private'
            $disk = Storage::disk('public')->exists($factura->archivo_path) ? 'public' : 'private';
            
            if (!Storage::disk($disk)->exists($factura->archivo_path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'El archivo no existe'
                ], 404);
            }

            $path = Storage::disk($disk)->path($factura->archivo_path);
            
            if (!file_exists($path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'El archivo no existe en el servidor'
                ], 404);
            }

            return response()->download($path, $factura->archivo_nombre);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al descargar factura: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getProveedoresForFacturas(): JsonResponse
    {
        try {
            $proveedores = Proveedor::orderBy('nombre_proveedor')->get(['id', 'nombre_proveedor', 'rut']);
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

    // ============================================
    // SOLICITUDES
    // ============================================

    public function solicitudes(Request $request): JsonResponse
    {
        try {
            $query = Solicitud::with(['user', 'departamento', 'tipoInsumo', 'items.insumo.unidadMedida', 'aprobadoPor', 'entregadoPor']);

            if ($request->has('estado')) {
                $query->where('estado', $request->get('estado'));
            }
            if ($request->has('departamento_id')) {
                $query->where('departamento_id', $request->get('departamento_id'));
            }
            if ($request->has('tipo_insumo_id')) {
                $query->where('tipo_insumo_id', $request->get('tipo_insumo_id'));
            }
            if ($request->has('user_id')) {
                $query->where('user_id', $request->get('user_id'));
            }
            if ($request->has('tipo_solicitud')) {
                $query->where('tipo_solicitud', $request->get('tipo_solicitud'));
            }

            $solicitudes = $query->orderBy('fecha_solicitud', 'desc')
                ->paginate($request->get('per_page', 15));

            return response()->json([
                'success' => true,
                'data' => $solicitudes,
                'message' => 'Solicitudes obtenidas exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener solicitudes: ' . $e->getMessage()
            ], 500);
        }
    }

    public function showSolicitud(int $id): JsonResponse
    {
        try {
            $solicitud = Solicitud::with([
                'user',
                'departamento',
                'tipoInsumo',
                'items.insumo.unidadMedida',
                'aprobadoPor',
                'entregadoPor'
            ])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $solicitud,
                'message' => 'Solicitud obtenida exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener solicitud: ' . $e->getMessage()
            ], 500);
        }
    }

    public function createSolicitud(Request $request): JsonResponse
    {
        $request->validate([
            'tipo_solicitud' => 'required|in:individual,masiva',
            'departamento_id' => 'required|exists:departamentos,id_depto',
            'tipo_insumo_id' => 'nullable|exists:tipo_insumos,id',
            'observaciones' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.insumo_id' => 'required|exists:insumos,id_insumo',
            'items.*.cantidad_solicitada' => 'required|integer|min:1',
            'items.*.observaciones_item' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();
            $solicitud = Solicitud::create([
                'tipo_solicitud' => $request->tipo_solicitud,
                'observaciones' => $request->observaciones,
                'estado' => 'pendiente',
                'user_id' => Auth::id(),
                'departamento_id' => $request->departamento_id,
                'tipo_insumo_id' => $request->tipo_insumo_id,
                'fecha_solicitud' => now(),
            ]);

            foreach ($request->items as $item) {
                SolicitudItem::create([
                    'solicitud_id' => $solicitud->id,
                    'insumo_id' => $item['insumo_id'],
                    'cantidad_solicitada' => $item['cantidad_solicitada'],
                    'observaciones_item' => $item['observaciones_item'] ?? null,
                ]);
            }

            DB::commit();
            $solicitud->load(['user', 'departamento', 'tipoInsumo', 'items.insumo.unidadMedida']);

            return response()->json([
                'success' => true,
                'data' => $solicitud,
                'message' => 'Solicitud creada exitosamente'
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear solicitud: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateSolicitud(Request $request, int $id): JsonResponse
    {
        try {
            $solicitud = Solicitud::findOrFail($id);

            if ($solicitud->estado !== 'pendiente') {
                return response()->json([
                    'success' => false,
                    'message' => 'Solo se pueden editar solicitudes pendientes'
                ], 400);
            }

            $request->validate([
                'tipo_solicitud' => 'sometimes|required|in:individual,masiva',
                'departamento_id' => 'sometimes|required|exists:departamentos,id_depto',
                'tipo_insumo_id' => 'nullable|exists:tipo_insumos,id',
                'observaciones' => 'nullable|string|max:1000',
                'items' => 'sometimes|required|array|min:1',
                'items.*.insumo_id' => 'required|exists:insumos,id_insumo',
                'items.*.cantidad_solicitada' => 'required|integer|min:1',
                'items.*.observaciones_item' => 'nullable|string|max:500',
            ]);

            DB::beginTransaction();

            $solicitud->update($request->only(['tipo_solicitud', 'departamento_id', 'tipo_insumo_id', 'observaciones']));

            if ($request->has('items')) {
                $solicitud->items()->delete();
                foreach ($request->items as $item) {
                    SolicitudItem::create([
                        'solicitud_id' => $solicitud->id,
                        'insumo_id' => $item['insumo_id'],
                        'cantidad_solicitada' => $item['cantidad_solicitada'],
                        'observaciones_item' => $item['observaciones_item'] ?? null,
                    ]);
                }
            }

            DB::commit();
            $solicitud->load(['user', 'departamento', 'tipoInsumo', 'items.insumo.unidadMedida']);

            return response()->json([
                'success' => true,
                'data' => $solicitud,
                'message' => 'Solicitud actualizada exitosamente'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar solicitud: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteSolicitud(int $id): JsonResponse
    {
        try {
            $solicitud = Solicitud::findOrFail($id);
            $solicitud->delete();
            return response()->json([
                'success' => true,
                'message' => 'Solicitud eliminada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar solicitud: ' . $e->getMessage()
            ], 500);
        }
    }

    public function aprobarSolicitud(int $id): JsonResponse
    {
        try {
            $solicitud = Solicitud::findOrFail($id);
            
            if ($solicitud->estado !== 'pendiente') {
                return response()->json([
                    'success' => false,
                    'message' => 'Solo se pueden aprobar solicitudes pendientes'
                ], 400);
            }

            $solicitud->aprobar(Auth::id());
            $solicitud->load(['user', 'departamento', 'tipoInsumo', 'items.insumo.unidadMedida', 'aprobadoPor']);

            return response()->json([
                'success' => true,
                'data' => $solicitud,
                'message' => 'Solicitud aprobada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al aprobar la solicitud: ' . $e->getMessage()
            ], 500);
        }
    }

    public function rechazarSolicitud(int $id): JsonResponse
    {
        try {
            $solicitud = Solicitud::findOrFail($id);
            
            if ($solicitud->estado !== 'pendiente') {
                return response()->json([
                    'success' => false,
                    'message' => 'Solo se pueden rechazar solicitudes pendientes'
                ], 400);
            }

            $solicitud->rechazar(Auth::id());
            $solicitud->load(['user', 'departamento', 'tipoInsumo', 'items.insumo.unidadMedida', 'aprobadoPor']);

            return response()->json([
                'success' => true,
                'data' => $solicitud,
                'message' => 'Solicitud rechazada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al rechazar la solicitud: ' . $e->getMessage()
            ], 500);
        }
    }

    public function entregarSolicitud(int $id): JsonResponse
    {
        try {
            $solicitud = Solicitud::findOrFail($id);
            
            if ($solicitud->estado !== 'aprobada') {
                return response()->json([
                    'success' => false,
                    'message' => 'Solo se pueden entregar solicitudes aprobadas'
                ], 400);
            }

            $solicitud->entregar(Auth::id());
            $solicitud->load(['user', 'departamento', 'tipoInsumo', 'items.insumo.unidadMedida', 'aprobadoPor', 'entregadoPor']);

            return response()->json([
                'success' => true,
                'data' => $solicitud,
                'message' => 'Solicitud entregada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al entregar la solicitud: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getInsumosForSolicitudes(Request $request): JsonResponse
    {
        try {
            $query = Insumo::with(['unidadMedida', 'tipoInsumo', 'departamento']);
            
            if ($request->has('tipo_insumo_id')) {
                $query->where('tipo_insumo_id', $request->get('tipo_insumo_id'));
            }
            if ($request->has('departamento_id')) {
                $query->where('departamento_id', $request->get('departamento_id'));
            }

            $insumos = $query->orderBy('nombre_insumo')->get();
            return response()->json([
                'success' => true,
                'data' => $insumos,
                'message' => 'Insumos obtenidos exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener insumos: ' . $e->getMessage()
            ], 500);
        }
    }

    // ============================================
    // CARGA MASIVA
    // ============================================

    public function cargaMasivaUpload(Request $request): JsonResponse
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,xls,csv|max:10240'
        ]);

        try {
            $file = $request->file('archivo');
            $fileExtension = $file->getClientOriginalExtension();
            $tempPath = $file->getRealPath();

            if (!$tempPath || !file_exists($tempPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo acceder al archivo subido'
                ], 400);
            }

            $excelService = new ExcelImportService();
            $result = $excelService->importFromFile($tempPath, $fileExtension);

            if ($result['success']) {
                $message = $result['message'];
                if (!empty($result['errors'])) {
                    $message .= ' Algunos registros tuvieron errores: ' . implode(', ', $result['errors']);
                }

                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => [
                        'importados' => $result['importados'] ?? 0,
                        'errores' => $result['errors'] ?? []
                    ]
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'],
                    'errors' => $result['errors'] ?? []
                ], 400);
            }
        } catch (\Exception $e) {
            Log::error('Error en carga masiva API: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el archivo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cargaMasivaTemplate(): JsonResponse
    {
        $filePath = public_path('storage/plantillas/Plantilla_Inventario_Insumo_CIC.xlsx');
        
        if (!file_exists($filePath)) {
            return response()->json([
                'success' => false,
                'message' => 'La plantilla no existe'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'url' => asset('storage/plantillas/Plantilla_Inventario_Insumo_CIC.xlsx')
            ],
            'message' => 'Plantilla obtenida exitosamente'
        ]);
    }

    // ============================================
    // ROLES Y PERMISOS (solo lectura)
    // ============================================

    public function roles(): JsonResponse
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

    public function showRole(int $id): JsonResponse
    {
        try {
            $role = Role::with('permissions')->findOrFail($id);
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

    public function permissions(): JsonResponse
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

    public function showPermission(int $id): JsonResponse
    {
        try {
            $permission = Permission::findOrFail($id);
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

