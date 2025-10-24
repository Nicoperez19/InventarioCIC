<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use App\Models\UnidadMedida;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InsumoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    // Métodos para web
    public function create()
    {
        $unidades = UnidadMedida::all();
        return view('layouts.insumo.insumo_create', compact('unidades'));
    }

    public function edit(Insumo $insumo)
    {
        $unidades = UnidadMedida::all();
        return view('layouts.insumo.insumo_edit', compact('insumo', 'unidades'));
    }

    public function index(Request $request): JsonResponse
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
                switch ($status) {
                    case 'low':
                        $query->whereRaw('stock_actual <= stock_minimo');
                        break;
                    case 'out':
                        $query->where('stock_actual', '<=', 0);
                        break;
                    case 'normal':
                        $query->whereRaw('stock_actual > stock_minimo');
                        break;
                }
            }

            $insumos = $query->orderBy('nombre_insumo')
                ->paginate($request->get('per_page', 15));

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

    public function show(Insumo $insumo): JsonResponse
    {
        try {
            $insumo->load(['unidadMedida', 'departamentos']);

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

    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_insumo' => 'required|string|max:20|unique:insumos,id_insumo',
                'codigo_barra' => 'nullable|string|max:50|unique:insumos,codigo_barra',
                'nombre_insumo' => 'required|string|max:255',
                'stock_minimo' => 'required|integer|min:0',
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

            $insumoData = $request->only(['id_insumo', 'codigo_barra', 'nombre_insumo', 'stock_minimo', 'stock_actual', 'id_unidad']);
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

    public function update(Request $request, Insumo $insumo): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_insumo' => 'sometimes|string|max:20|unique:insumos,id_insumo,' . $insumo->id_insumo,
                'codigo_barra' => 'nullable|string|max:50|unique:insumos,codigo_barra,' . $insumo->id_insumo,
                'nombre_insumo' => 'sometimes|string|max:255',
                'stock_minimo' => 'sometimes|integer|min:0',
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

            $insumoData = $request->only(['id_insumo', 'codigo_barra', 'nombre_insumo', 'stock_minimo', 'stock_actual', 'id_unidad']);
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

    public function destroy(Insumo $insumo): JsonResponse
    {
        try {
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

    public function adjustStock(Request $request, Insumo $insumo): JsonResponse
    {
        try {
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

            switch ($tipo) {
                case 'add':
                    $insumo->addStock($cantidad);
                    break;
                case 'subtract':
                    if (!$insumo->canReduceStock($cantidad)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'No hay suficiente stock para reducir'
                        ], 422);
                    }
                    $insumo->reduceStock($cantidad);
                    break;
                case 'set':
                    $insumo->stock_actual = $cantidad;
                    $insumo->save();
                    break;
            }

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
            $unidades = UnidadMedida::orderBy('nombre_unidad_medida')->get();

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
            $insumos = Insumo::lowStock()->with('unidadMedida')->get();

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
}