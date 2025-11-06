<?php
namespace App\Http\Controllers;
use App\Models\Solicitud;
use App\Models\SolicitudItem;
use App\Models\Insumo;
use App\Models\Departamento;
use App\Models\TipoInsumo;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Dompdf\Dompdf;
use Dompdf\Options;
class SolicitudController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $solicitudes = Solicitud::with(['user', 'departamento', 'tipoInsumo', 'items.insumo'])
            ->orderBy('fecha_solicitud', 'desc')
            ->paginate(15);
        return view('layouts.solicitud.solicitud_index', compact('solicitudes'));
    }
    public function create()
    {
        $departamentos = Departamento::orderBy('nombre_depto')->get();
        $tiposInsumo = TipoInsumo::activos()->orderBy('nombre_tipo')->get();
        return view('layouts.solicitud.solicitud_create', compact('departamentos', 'tiposInsumo'));
    }
    public function store(Request $request)
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
            return redirect()->route('solicitudes.index')
                ->with('success', 'Solicitud creada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error al crear la solicitud: ' . $e->getMessage());
        }
    }
    public function show(Solicitud $solicitud)
    {
        $solicitud->load(['user', 'departamento', 'tipoInsumo', 'items.insumo.unidadMedida', 'aprobadoPor', 'entregadoPor']);
        return view('layouts.solicitud.solicitud_show', compact('solicitud'));
    }
    public function edit(Solicitud $solicitud)
    {
        if ($solicitud->estado !== 'pendiente') {
            return redirect()->route('solicitudes.index')
                ->with('error', 'No se puede editar una solicitud que no está pendiente.');
        }
        $departamentos = Departamento::orderBy('nombre_depto')->get();
        $tiposInsumo = TipoInsumo::activos()->orderBy('nombre_tipo')->get();
        $solicitud->load(['items.insumo.unidadMedida']);
        return view('layouts.solicitud.solicitud_edit', compact('solicitud', 'departamentos', 'tiposInsumo'));
    }
    public function update(Request $request, Solicitud $solicitud)
    {
        if ($solicitud->estado !== 'pendiente') {
            return redirect()->route('solicitudes.index')
                ->with('error', 'No se puede editar una solicitud que no está pendiente.');
        }
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
            $solicitud->update([
                'tipo_solicitud' => $request->tipo_solicitud,
                'observaciones' => $request->observaciones,
                'departamento_id' => $request->departamento_id,
                'tipo_insumo_id' => $request->tipo_insumo_id,
            ]);
            $solicitud->items()->delete();
            foreach ($request->items as $item) {
                SolicitudItem::create([
                    'solicitud_id' => $solicitud->id,
                    'insumo_id' => $item['insumo_id'],
                    'cantidad_solicitada' => $item['cantidad_solicitada'],
                    'observaciones_item' => $item['observaciones_item'] ?? null,
                ]);
            }
            DB::commit();
            return redirect()->route('solicitudes.index')
                ->with('success', 'Solicitud actualizada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error al actualizar la solicitud: ' . $e->getMessage());
        }
    }
    public function destroy(Solicitud $solicitud)
    {
        if ($solicitud->estado !== 'pendiente') {
            return redirect()->route('solicitudes.index')
                ->with('error', 'No se puede eliminar una solicitud que no está pendiente.');
        }
        try {
            $solicitud->delete();
            return redirect()->route('solicitudes.index')
                ->with('success', 'Solicitud eliminada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('solicitudes.index')
                ->with('error', 'Error al eliminar la solicitud: ' . $e->getMessage());
        }
    }
    public function aprobar(Request $request, Solicitud $solicitud)
    {
        if ($solicitud->estado !== 'pendiente') {
            return redirect()->route('solicitudes.index')
                ->with('error', 'Solo se pueden aprobar solicitudes pendientes.');
        }
        try {
            $solicitud->aprobar(Auth::id());
            return redirect()->route('solicitudes.index')
                ->with('success', 'Solicitud aprobada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('solicitudes.index')
                ->with('error', 'Error al aprobar la solicitud: ' . $e->getMessage());
        }
    }
    public function rechazar(Request $request, Solicitud $solicitud)
    {
        if ($solicitud->estado !== 'pendiente') {
            return redirect()->route('solicitudes.index')
                ->with('error', 'Solo se pueden rechazar solicitudes pendientes.');
        }
        try {
            $solicitud->rechazar(Auth::id());
            return redirect()->route('solicitudes.index')
                ->with('success', 'Solicitud rechazada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('solicitudes.index')
                ->with('error', 'Error al rechazar la solicitud: ' . $e->getMessage());
        }
    }
    public function entregar(Request $request, Solicitud $solicitud)
    {
        if ($solicitud->estado !== 'aprobada') {
            return redirect()->route('solicitudes.index')
                ->with('error', 'Solo se pueden entregar solicitudes aprobadas.');
        }
        try {
            $solicitud->entregar(Auth::id());
            return redirect()->route('solicitudes.index')
                ->with('success', 'Solicitud entregada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('solicitudes.index')
                ->with('error', 'Error al entregar la solicitud: ' . $e->getMessage());
        }
    }
    public function getInsumos(Request $request)
    {
        $query = Insumo::with(['unidadMedida', 'tipoInsumo', 'departamento']);
        if ($request->tipo_insumo_id) {
            $query->where('tipo_insumo_id', $request->tipo_insumo_id);
        }
        if ($request->departamento_id) {
            $query->where('departamento_id', $request->departamento_id);
        }
        $insumos = $query->orderBy('nombre_insumo')->get();
        return response()->json($insumos);
    }
    public function getAllInsumos(Request $request)
    {
        $insumos = Insumo::with(['unidadMedida', 'tipoInsumo', 'departamento'])
            ->orderBy('nombre_insumo')
            ->get();
        return response()->json($insumos);
    }

    /**
     * Exporta una solicitud a Excel
     */
    public function exportExcel(Solicitud $solicitud)
    {
        try {
            $solicitud->load([
                'user', 
                'departamento', 
                'items.insumo'
            ]);

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $row = 1;

            // Información básica
            $sheet->setCellValue('A' . $row, 'N° Solicitud:');
            $sheet->setCellValue('B' . $row, $solicitud->numero_solicitud);
            $sheet->getStyle('A' . $row)->getFont()->setBold(true);
            $row++;

            $sheet->setCellValue('A' . $row, 'Fecha:');
            $sheet->setCellValue('B' . $row, $solicitud->fecha_solicitud->format('d/m/Y H:i'));
            $sheet->getStyle('A' . $row)->getFont()->setBold(true);
            $row++;

            $sheet->setCellValue('A' . $row, 'Solicitante:');
            $sheet->setCellValue('B' . $row, $solicitud->user->nombre ?? 'N/A');
            $sheet->getStyle('A' . $row)->getFont()->setBold(true);
            $row++;

            $sheet->setCellValue('A' . $row, 'Departamento:');
            $sheet->setCellValue('B' . $row, $solicitud->departamento->nombre_depto ?? 'N/A');
            $sheet->getStyle('A' . $row)->getFont()->setBold(true);
            $row += 2;

            // Encabezados de insumos
            $headers = ['Insumo'];
            $col = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($col . $row, $header);
                $sheet->getStyle($col . $row)->getFont()->setBold(true);
                $sheet->getStyle($col . $row)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('4472C4');
                $sheet->getStyle($col . $row)->getFont()->getColor()->setRGB('FFFFFF');
                $sheet->getStyle($col . $row)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);
                $col++;
            }
            $row++;

            // Listado de insumos
            foreach ($solicitud->items as $item) {
                $sheet->setCellValue('A' . $row, $item->insumo->nombre_insumo ?? 'N/A');

                // Estilos de fila
                $sheet->getStyle('A' . $row)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'CCCCCC'],
                        ],
                    ],
                ]);
                $row++;
            }

            // Autoajustar columnas
            $sheet->getColumnDimension('A')->setAutoSize(true);
            $sheet->getColumnDimension('B')->setAutoSize(true);

            // Generar nombre del archivo
            $nombreArchivo = 'Solicitud_' . $solicitud->numero_solicitud . '_' . now()->format('Y-m-d') . '.xlsx';

            // Guardar en memoria
            $writer = new Xlsx($spreadsheet);
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
            exit;

        } catch (\Exception $e) {
            return back()->with('error', 'Error al generar el archivo Excel: ' . $e->getMessage());
        }
    }

    /**
     * Exporta una solicitud a PDF
     */
    public function exportPdf(Solicitud $solicitud)
    {
        try {
            $solicitud->load([
                'user', 
                'departamento', 
                'items.insumo'
            ]);

            // Configurar opciones de DomPDF
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);
            $options->set('defaultFont', 'Arial');

            // Crear instancia de DomPDF
            $dompdf = new Dompdf($options);

            // Renderizar la vista del PDF
            $html = view('pdf.solicitud-individual', [
                'solicitud' => $solicitud,
                'fecha' => now()->format('d/m/Y H:i:s')
            ])->render();

            // Cargar HTML en DomPDF
            $dompdf->loadHtml($html);

            // Configurar el tamaño del papel
            $dompdf->setPaper('A4', 'portrait');

            // Renderizar el PDF
            $dompdf->render();

            // Generar nombre del archivo
            $nombreArchivo = 'Solicitud_' . $solicitud->numero_solicitud . '_' . now()->format('Y-m-d') . '.pdf';

            // Retornar el PDF como descarga
            return $dompdf->stream($nombreArchivo);

        } catch (\Exception $e) {
            return back()->with('error', 'Error al generar el PDF: ' . $e->getMessage());
        }
    }

    // ==================== MÉTODOS API PARA APLICACIÓN MÓVIL ====================

    /**
     * API: Listar todas las solicitudes
     */
    public function apiIndex(Request $request): JsonResponse
    {
        try {
            $query = Solicitud::with(['user', 'departamento', 'tipoInsumo', 'items.insumo.unidadMedida', 'aprobadoPor', 'entregadoPor']);

            // Filtros
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

    /**
     * API: Obtener una solicitud específica
     */
    public function apiShow(Solicitud $solicitud): JsonResponse
    {
        try {
            $solicitud->load([
                'user',
                'departamento',
                'tipoInsumo',
                'items.insumo.unidadMedida',
                'aprobadoPor',
                'entregadoPor'
            ]);

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

    /**
     * API: Crear una nueva solicitud
     */
    public function apiStore(Request $request): JsonResponse
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
                'message' => 'Error al crear la solicitud: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Actualizar una solicitud
     */
    public function apiUpdate(Request $request, Solicitud $solicitud): JsonResponse
    {
        if ($solicitud->estado !== 'pendiente') {
            return response()->json([
                'success' => false,
                'message' => 'No se puede editar una solicitud que no está pendiente'
            ], 400);
        }

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
            $solicitud->update([
                'tipo_solicitud' => $request->tipo_solicitud,
                'observaciones' => $request->observaciones,
                'departamento_id' => $request->departamento_id,
                'tipo_insumo_id' => $request->tipo_insumo_id,
            ]);

            $solicitud->items()->delete();
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
                'message' => 'Solicitud actualizada exitosamente'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la solicitud: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Eliminar una solicitud
     */
    public function apiDestroy(Solicitud $solicitud): JsonResponse
    {
        if ($solicitud->estado !== 'pendiente') {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar una solicitud que no está pendiente'
            ], 400);
        }

        try {
            $solicitud->delete();
            return response()->json([
                'success' => true,
                'message' => 'Solicitud eliminada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la solicitud: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Aprobar una solicitud
     */
    public function apiAprobar(Solicitud $solicitud): JsonResponse
    {
        if ($solicitud->estado !== 'pendiente') {
            return response()->json([
                'success' => false,
                'message' => 'Solo se pueden aprobar solicitudes pendientes'
            ], 400);
        }

        try {
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

    /**
     * API: Rechazar una solicitud
     */
    public function apiRechazar(Solicitud $solicitud): JsonResponse
    {
        if ($solicitud->estado !== 'pendiente') {
            return response()->json([
                'success' => false,
                'message' => 'Solo se pueden rechazar solicitudes pendientes'
            ], 400);
        }

        try {
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

    /**
     * API: Entregar una solicitud
     */
    public function apiEntregar(Solicitud $solicitud): JsonResponse
    {
        if ($solicitud->estado !== 'aprobada') {
            return response()->json([
                'success' => false,
                'message' => 'Solo se pueden entregar solicitudes aprobadas'
            ], 400);
        }

        try {
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

    /**
     * API: Obtener insumos para solicitudes (ya existe, pero mejorado)
     */
    public function apiGetInsumos(Request $request): JsonResponse
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
}
