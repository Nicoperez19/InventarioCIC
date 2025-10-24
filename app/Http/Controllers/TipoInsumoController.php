<?php

namespace App\Http\Controllers;

use App\Models\TipoInsumo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TipoInsumoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $query = TipoInsumo::withCount('insumos')
                ->orderBy('nombre_tipo');

            if ($request->has('search')) {
                $search = $request->get('search');
                $query->where(function($q) use ($search) {
                    $q->where('nombre_tipo', 'like', "%{$search}%")
                      ->orWhere('descripcion', 'like', "%{$search}%");
                });
            }

            if ($request->has('activo')) {
                $query->where('activo', $request->get('activo'));
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

    public function show(TipoInsumo $tipoInsumo): JsonResponse
    {
        try {
            $tipoInsumo->load(['insumos' => function($query) {
                $query->orderBy('nombre_insumo');
            }]);

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

    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'nombre_tipo' => 'required|string|max:255|unique:tipo_insumos,nombre_tipo',
                'descripcion' => 'nullable|string|max:1000',
                'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
                'activo' => 'boolean'
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

    public function update(Request $request, TipoInsumo $tipoInsumo): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'nombre_tipo' => 'sometimes|string|max:255|unique:tipo_insumos,nombre_tipo,' . $tipoInsumo->id,
                'descripcion' => 'nullable|string|max:1000',
                'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
                'activo' => 'boolean'
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

    public function destroy(TipoInsumo $tipoInsumo): JsonResponse
    {
        try {
            if ($tipoInsumo->tieneInsumos()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar el tipo de insumo porque tiene insumos asociados'
                ], 422);
            }

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

    public function toggleStatus(TipoInsumo $tipoInsumo): JsonResponse
    {
        try {
            $tipoInsumo->update(['activo' => !$tipoInsumo->activo]);

            return response()->json([
                'success' => true,
                'data' => $tipoInsumo,
                'message' => $tipoInsumo->activo ? 'Tipo de insumo activado exitosamente' : 'Tipo de insumo desactivado exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar estado del tipo de insumo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getActivos(): JsonResponse
    {
        try {
            $tiposInsumo = TipoInsumo::activos()
                ->orderByName()
                ->get(['id', 'nombre_tipo', 'color']);

            return response()->json([
                'success' => true,
                'data' => $tiposInsumo,
                'message' => 'Tipos de insumo activos obtenidos exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener tipos de insumo activos: ' . $e->getMessage()
            ], 500);
        }
    }
}