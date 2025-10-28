<?php
namespace App\Http\Controllers;
use App\Models\Factura;
use App\Models\Proveedor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
class FacturaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request): JsonResponse
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
    public function show(Factura $factura): JsonResponse
    {
        try {
            if ($factura->run !== Auth::user()->run) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permisos para ver esta factura'
                ], 403);
            }
            $factura->load('proveedor');
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
    public function store(Request $request): JsonResponse
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
    public function update(Request $request, Factura $factura): JsonResponse
    {
        try {
            if ($factura->run !== Auth::user()->run) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permisos para editar esta factura'
                ], 403);
            }
            $validator = Validator::make($request->all(), [
                'numero_factura' => 'sometimes|string|max:255|unique:facturas,numero_factura,' . $factura->id,
                'proveedor_id' => 'sometimes|exists:proveedores,id',
                'monto_total' => 'sometimes|numeric|min:0',
                'fecha_factura' => 'sometimes|date',
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
            if ($request->hasFile('archivo')) {
                if ($factura->archivo_path && Storage::disk('private')->exists($factura->archivo_path)) {
                    Storage::disk('private')->delete($factura->archivo_path);
                }
                $archivo = $request->file('archivo');
                $nombreArchivo = Str::slug($request->numero_factura ?? $factura->numero_factura) . '_' . time() . '.' . $archivo->getClientOriginalExtension();
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
    public function destroy(Factura $factura): JsonResponse
    {
        try {
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
    public function download(Factura $factura): JsonResponse
    {
        try {
            if ($factura->run !== Auth::user()->run) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permisos para descargar esta factura'
                ], 403);
            }
            if (!$factura->archivo_path || !Storage::disk('private')->exists($factura->archivo_path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'El archivo no existe'
                ], 404);
            }
            $url = route('facturas.download', $factura);
            return response()->json([
                'success' => true,
                'data' => [
                    'url' => $url,
                    'nombre' => $factura->archivo_nombre
                ],
                'message' => 'URL de descarga generada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al generar descarga: ' . $e->getMessage()
            ], 500);
        }
    }
    public function getProveedores(): JsonResponse
    {
        try {
            $proveedores = Proveedor::orderBy('nombre_proveedor')->get();
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
