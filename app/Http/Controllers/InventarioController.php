<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\Insumo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InventarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $query = Inventario::with('insumo.unidadMedida');

            if ($request->has('insumo_id')) {
                $query->where('id_producto', $request->get('insumo_id'));
            }

            if ($request->has('fecha_desde')) {
                $query->where('fecha_inventario', '>=', $request->get('fecha_desde'));
            }

            if ($request->has('fecha_hasta')) {
                $query->where('fecha_inventario', '<=', $request->get('fecha_hasta'));
            }

            $inventarios = $query->orderBy('fecha_inventario', 'desc')
                ->paginate($request->get('per_page', 15));

            return response()->json([
                'success' => true,
                'data' => $inventarios,
                'message' => 'Inventarios obtenidos exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener inventarios: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Inventario $inventario): JsonResponse
    {
        try {
            $inventario->load('insumo.unidadMedida');

            return response()->json([
                'success' => true,
                'data' => $inventario,
                'message' => 'Inventario obtenido exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener inventario: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_producto' => 'required|exists:insumos,id_insumo',
                'fecha_inventario' => 'required|date',
                'cantidad_inventario' => 'required|integer|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de validación incorrectos',
                    'errors' => $validator->errors()
                ], 422);
            }

            $inventario = Inventario::create($request->all());
            $inventario->load('insumo.unidadMedida');

            return response()->json([
                'success' => true,
                'data' => $inventario,
                'message' => 'Inventario creado exitosamente'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear inventario: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Inventario $inventario): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_producto' => 'sometimes|exists:insumos,id_insumo',
                'fecha_inventario' => 'sometimes|date',
                'cantidad_inventario' => 'sometimes|integer|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de validación incorrectos',
                    'errors' => $validator->errors()
                ], 422);
            }

            $inventario->update($request->all());
            $inventario->load('insumo.unidadMedida');

            return response()->json([
                'success' => true,
                'data' => $inventario,
                'message' => 'Inventario actualizado exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar inventario: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Inventario $inventario): JsonResponse
    {
        try {
            $inventario->delete();

            return response()->json([
                'success' => true,
                'message' => 'Inventario eliminado exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar inventario: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getInsumos(): JsonResponse
    {
        try {
            $insumos = Insumo::with('unidadMedida')->get();

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
}