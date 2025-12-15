<?php
namespace App\Http\Controllers;
use App\Models\Solicitud;
use App\Models\SolicitudItem;
use App\Models\Insumo;
use App\Models\Departamento;
use App\Models\TipoInsumo;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
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
            // Consolidar cantidades por insumo para evitar decrementos duplicados
            $requestedQuantities = [];
            foreach ($request->items as $item) {
                $id = $item['insumo_id'];
                $qty = (int) $item['cantidad_solicitada'];
                if ($qty <= 0) {
                    throw new \Exception("Cantidad inválida para el insumo {$id}");
                }
                if (!isset($requestedQuantities[$id])) {
                    $requestedQuantities[$id] = 0;
                }
                $requestedQuantities[$id] += $qty;
            }

            // Bloquear filas y verificar stock disponible
            foreach ($requestedQuantities as $insumoId => $totalQty) {
                $insumo = Insumo::where('id_insumo', $insumoId)->lockForUpdate()->first();
                if (!$insumo) {
                    throw new \Exception("Insumo no encontrado: {$insumoId}");
                }
                if (!$insumo->canReduceStock($totalQty)) {
                    throw new \Exception("Stock insuficiente para el insumo {$insumo->nombre_insumo} (solicitado: {$totalQty}, disponible: {$insumo->stock_actual})");
                }
            }

            // Todos los insumos tienen stock suficiente, proceder a crear la solicitud
            $solicitud = Solicitud::create([
                'tipo_solicitud' => $request->tipo_solicitud,
                'observaciones' => $request->observaciones,
                'estado' => 'pendiente', // Asegurar que siempre sea pendiente
                'user_id' => Auth::id(),
                'departamento_id' => $request->departamento_id,
                'tipo_insumo_id' => $request->tipo_insumo_id,
                'fecha_solicitud' => now(),
            ]);

            // Crear items y reducir stock (reservar stock al crear la solicitud)
            foreach ($request->items as $item) {
                $insumoId = $item['insumo_id'];
                $cantidad = (int) $item['cantidad_solicitada'];

                SolicitudItem::create([
                    'solicitud_id' => $solicitud->id,
                    'insumo_id' => $insumoId,
                    'cantidad_solicitada' => $cantidad,
                    'observaciones_item' => $item['observaciones_item'] ?? null,
                    'estado_item' => 'pendiente'
                ]);

                // Reducir stock al crear la solicitud (reservar stock)
                $insumo = Insumo::where('id_insumo', $insumoId)->lockForUpdate()->first();
                if (!$insumo) {
                    throw new \Exception("Insumo no encontrado al reservar stock: {$insumoId}");
                }
                
                // Verificar que hay suficiente stock
                if (!$insumo->canReduceStock($cantidad)) {
                    throw new \Exception("Stock insuficiente para el insumo {$insumo->nombre_insumo} (solicitado: {$cantidad}, disponible: {$insumo->stock_actual})");
                }
                
                // Reducir el stock (reservar)
                if (!$insumo->reduceStock($cantidad)) {
                    throw new \Exception("Error al reservar stock para el insumo {$insumo->nombre_insumo}");
                }
            }

            // Crear notificaciones para usuarios con permiso de administrar solicitudes
            try {
                $usuariosNotificables = \App\Models\User::whereHas('roles', function ($query) {
                    $query->where('name', 'Administrador');
                })->orWhereHas('permissions', function ($query) {
                    $query->where('name', 'admin solicitudes');
                })->get();

                foreach ($usuariosNotificables as $usuario) {
                    Notificacion::create([
                        'tipo' => 'solicitud',
                        'titulo' => 'Nueva Solicitud Pendiente de Aprobación',
                        'mensaje' => "Se ha creado una nueva solicitud #{$solicitud->numero_solicitud} por " . Auth::user()->nombre . " del departamento " . ($solicitud->departamento->nombre_depto ?? 'N/A') . ". Requiere aprobación.",
                        'user_id' => $usuario->run,
                        'solicitud_id' => $solicitud->id,
                    ]);
                }
            } catch (\Exception $e) {
                // No fallar la creación de la solicitud si falla la notificación
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
}
