<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ProveedorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        Log::info('ProveedorController@index - Iniciando acceso a vista proveedores', [
            'user_id' => auth()->id(),
            'user_run' => auth()->user()->run ?? 'N/A',
            'request_type' => $request->expectsJson() ? 'API' : 'Web',
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        if ($request->expectsJson()) {
            try {
                $query = Proveedor::withCount('facturas')
                    ->withSum('facturas', 'monto_total');

                if ($request->has('search')) {
                    $search = $request->get('search');
                    $query->where(function($q) use ($search) {
                        $q->where('nombre_proveedor', 'like', "%{$search}%")
                          ->orWhere('rut', 'like', "%{$search}%")
                          ->orWhere('telefono', 'like', "%{$search}%");
                    });
                }

                $proveedores = $query->orderBy('nombre_proveedor')
                    ->paginate($request->get('per_page', 15));

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

        Log::info('ProveedorController@index - Retornando vista proveedor_index', [
            'view_path' => 'layouts.proveedor.proveedor_index',
            'user_id' => auth()->id()
        ]);

        return view('layouts.proveedor.proveedor_index');
    }

    public function show(Proveedor $proveedor)
    {
        if (request()->expectsJson()) {
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

        return view('layouts.proveedor.proveedor_show', compact('proveedor'));
    }

    public function create()
    {
        return view('layouts.proveedor.proveedor_create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rut' => 'required|string|max:20|unique:proveedores,rut',
            'nombre_proveedor' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20'
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de validación incorrectos',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $proveedor = Proveedor::create($request->all());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $proveedor,
                    'message' => 'Proveedor creado exitosamente'
                ], 201);
            }

            return redirect()->route('proveedores.index')->with('success', 'Proveedor creado exitosamente.');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear proveedor: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Error al crear proveedor: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Proveedor $proveedor)
    {
        return view('layouts.proveedor.proveedor_update', compact('proveedor'));
    }

    public function update(Request $request, Proveedor $proveedor)
    {
        $validator = Validator::make($request->all(), [
            'rut' => 'sometimes|string|max:20|unique:proveedores,rut,' . $proveedor->id,
            'nombre_proveedor' => 'sometimes|string|max:255',
            'telefono' => 'nullable|string|max:20'
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de validación incorrectos',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $proveedor->update($request->all());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $proveedor,
                    'message' => 'Proveedor actualizado exitosamente'
                ]);
            }

            return redirect()->route('proveedores.index')->with('success', 'Proveedor actualizado exitosamente.');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar proveedor: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Error al actualizar proveedor: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Proveedor $proveedor)
    {
        try {
            if ($proveedor->tieneFacturas()) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No se puede eliminar el proveedor porque tiene facturas asociadas'
                    ], 422);
                }
                return redirect()->back()->with('error', 'No se puede eliminar el proveedor porque tiene facturas asociadas.');
            }

            $proveedor->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Proveedor eliminado exitosamente'
                ]);
            }

            return redirect()->route('proveedores.index')->with('success', 'Proveedor eliminado exitosamente.');

        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar proveedor: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Error al eliminar proveedor: ' . $e->getMessage());
        }
    }

    public function getProveedores(): JsonResponse
    {
        try {
            $proveedores = Proveedor::select('id', 'nombre_proveedor', 'rut')
                ->orderBy('nombre_proveedor')
                ->get();

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
}
