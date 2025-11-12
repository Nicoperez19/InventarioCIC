<?php
namespace App\Http\Controllers;
use App\Models\TipoInsumo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Dompdf\Dompdf;
use Dompdf\Options;
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
    public function update(Request $request, TipoInsumo $tipoInsumo): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'nombre_tipo' => 'sometimes|string|max:255|unique:tipo_insumos,nombre_tipo,' . $tipoInsumo->id
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
            // Con eliminación en cascada, los insumos y solicitudes se eliminarán automáticamente
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
    public function getAll(): JsonResponse
    {
        try {
            $tiposInsumo = TipoInsumo::orderBy('nombre_tipo')
                ->get(['id', 'nombre_tipo']);
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

    public function generatePdf(TipoInsumo $tipoInsumo)
    {
        try {
            // Cargar el tipo de insumo con sus insumos y relaciones
            $tipoInsumo->load([
                'insumos' => function($query) {
                    $query->with('unidadMedida')
                          ->orderBy('nombre_insumo');
                }
            ]);

            // Configurar opciones de DomPDF
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);
            $options->set('defaultFont', 'Arial');

            // Crear instancia de DomPDF
            $dompdf = new Dompdf($options);

            // Renderizar la vista del PDF
            $html = view('pdf.tipo-insumo-insumos', [
                'tipoInsumo' => $tipoInsumo,
                'insumos' => $tipoInsumo->insumos,
                'fecha' => now()->format('d/m/Y H:i:s')
            ])->render();

            // Cargar HTML en DomPDF
            $dompdf->loadHtml($html);

            // Configurar el tamaño del papel
            $dompdf->setPaper('A4', 'portrait');

            // Renderizar el PDF
            $dompdf->render();

            // Generar nombre del archivo
            $nombreArchivo = 'Insumos_' . str_replace(' ', '_', $tipoInsumo->nombre_tipo) . '_' . now()->format('Y-m-d') . '.pdf';

            // Retornar el PDF como descarga
            return $dompdf->stream($nombreArchivo);

        } catch (\Exception $e) {
            return back()->with('error', 'Error al generar el PDF: ' . $e->getMessage());
        }
    }
}
