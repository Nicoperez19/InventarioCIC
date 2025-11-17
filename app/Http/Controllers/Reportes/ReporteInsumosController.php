<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Services\Reportes\ReporteInsumosService;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Dompdf\Dompdf;
use Dompdf\Options;

class ReporteInsumosController extends Controller
{
    protected $reporteService;

    public function __construct(ReporteInsumosService $reporteService)
    {
        $this->middleware('auth');
        $this->reporteService = $reporteService;
    }

    public function index()
    {
        return view('reportes.insumos.index');
    }

    public function generar(Request $request)
    {
        $request->validate([
            'tipo_periodo' => 'required|in:semanal,mensual,semestral,anual',
            'fecha_referencia' => 'nullable|date',
        ]);

        $fechas = $this->reporteService->getFechasPeriodo(
            $request->tipo_periodo,
            $request->fecha_referencia
        );

        $insumosMasSolicitados = $this->reporteService->getInsumosMasSolicitados(
            $fechas['inicio'],
            $fechas['fin']
        );

        $insumosNoSolicitados = $this->reporteService->getInsumosNoSolicitados(
            $fechas['inicio'],
            $fechas['fin']
        );

        $estadisticas = $this->reporteService->getEstadisticasPeriodo(
            $fechas['inicio'],
            $fechas['fin']
        );

        return view('reportes.insumos.resultado', [
            'tipoPeriodo' => $request->tipo_periodo,
            'fechaInicio' => $fechas['inicio'],
            'fechaFin' => $fechas['fin'],
            'insumosMasSolicitados' => $insumosMasSolicitados,
            'insumosNoSolicitados' => $insumosNoSolicitados,
            'estadisticas' => $estadisticas,
        ]);
    }

    public function exportarExcel(Request $request)
    {
        $request->validate([
            'tipo_periodo' => 'required|in:semanal,mensual,semestral,anual',
            'fecha_referencia' => 'nullable|date',
        ]);

        $fechas = $this->reporteService->getFechasPeriodo(
            $request->tipo_periodo,
            $request->fecha_referencia
        );

        $insumosMasSolicitados = $this->reporteService->getInsumosMasSolicitados(
            $fechas['inicio'],
            $fechas['fin'],
            50
        );

        $insumosNoSolicitados = $this->reporteService->getInsumosNoSolicitados(
            $fechas['inicio'],
            $fechas['fin']
        );

        $estadisticas = $this->reporteService->getEstadisticasPeriodo(
            $fechas['inicio'],
            $fechas['fin']
        );

        $spreadsheet = new Spreadsheet();
        
        // Hoja 1: Insumos más solicitados
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Más Solicitados');
        
        $row = 1;
        
        // Encabezado principal
        $sheet1->setCellValue('A' . $row, 'Reporte de Insumos - GestionCIC');
        $sheet1->mergeCells('A' . $row . ':E' . $row);
        $sheet1->getStyle('A' . $row)->getFont()->setBold(true)->setSize(14);
        $sheet1->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $row++;
        
        $sheet1->setCellValue('A' . $row, 'Insumos Más Solicitados - Período ' . ucfirst($request->tipo_periodo));
        $sheet1->mergeCells('A' . $row . ':E' . $row);
        $sheet1->getStyle('A' . $row)->getFont()->setBold(true)->setSize(12);
        $sheet1->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $row += 2;
        
        // Información del período
        $sheet1->setCellValue('A' . $row, 'Período:');
        $sheet1->setCellValue('B' . $row, $fechas['inicio']->format('d/m/Y') . ' - ' . $fechas['fin']->format('d/m/Y'));
        $sheet1->getStyle('A' . $row)->getFont()->setBold(true);
        $row++;
        
        $sheet1->setCellValue('A' . $row, 'Fecha de Generación:');
        $sheet1->setCellValue('B' . $row, now()->format('d/m/Y H:i'));
        $sheet1->getStyle('A' . $row)->getFont()->setBold(true);
        $row += 2;
        
        // Encabezados de tabla
        $headers = ['#', 'Insumo', 'Tipo', 'Total Solicitado', 'Veces Solicitado'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet1->setCellValue($col . $row, $header);
            $sheet1->getStyle($col . $row)->getFont()->setBold(true);
            $sheet1->getStyle($col . $row)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('306073');
            $sheet1->getStyle($col . $row)->getFont()->getColor()->setRGB('FFFFFF');
            $sheet1->getStyle($col . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet1->getStyle($col . $row)->applyFromArray([
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
        
        // Datos
        $contador = 1;
        foreach ($insumosMasSolicitados as $insumo) {
            $sheet1->setCellValue('A' . $row, $contador);
            $sheet1->setCellValue('B' . $row, $insumo->nombre_insumo);
            $sheet1->setCellValue('C' . $row, $insumo->nombre_tipo ?? 'N/A');
            $sheet1->setCellValue('D' . $row, $insumo->total_solicitado);
            $sheet1->setCellValue('E' . $row, $insumo->veces_solicitado);
            $sheet1->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet1->getStyle('D' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet1->getStyle('E' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            
            // Bordes para todas las celdas
            foreach (range('A', 'E') as $col) {
                $sheet1->getStyle($col . $row)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'CCCCCC'],
                        ],
                    ],
                ]);
            }
            $row++;
            $contador++;
        }
        
        // Ajustar ancho de columnas
        foreach (range('A', 'E') as $col) {
            $sheet1->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Hoja 2: Insumos no solicitados
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('No Solicitados');
        
        $row = 1;
        
        // Encabezado principal
        $sheet2->setCellValue('A' . $row, 'Reporte de Insumos - GestionCIC');
        $sheet2->mergeCells('A' . $row . ':D' . $row);
        $sheet2->getStyle('A' . $row)->getFont()->setBold(true)->setSize(14);
        $sheet2->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $row++;
        
        $sheet2->setCellValue('A' . $row, 'Insumos No Solicitados - Período ' . ucfirst($request->tipo_periodo));
        $sheet2->mergeCells('A' . $row . ':D' . $row);
        $sheet2->getStyle('A' . $row)->getFont()->setBold(true)->setSize(12);
        $sheet2->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $row += 2;
        
        // Información del período
        $sheet2->setCellValue('A' . $row, 'Período:');
        $sheet2->setCellValue('B' . $row, $fechas['inicio']->format('d/m/Y') . ' - ' . $fechas['fin']->format('d/m/Y'));
        $sheet2->getStyle('A' . $row)->getFont()->setBold(true);
        $row++;
        
        $sheet2->setCellValue('A' . $row, 'Fecha de Generación:');
        $sheet2->setCellValue('B' . $row, now()->format('d/m/Y H:i'));
        $sheet2->getStyle('A' . $row)->getFont()->setBold(true);
        $row += 2;
        
        // Encabezados de tabla
        $headers2 = ['Insumo', 'Tipo', 'Stock Actual', 'Unidad'];
        $col = 'A';
        foreach ($headers2 as $header) {
            $sheet2->setCellValue($col . $row, $header);
            $sheet2->getStyle($col . $row)->getFont()->setBold(true);
            $sheet2->getStyle($col . $row)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('306073');
            $sheet2->getStyle($col . $row)->getFont()->getColor()->setRGB('FFFFFF');
            $sheet2->getStyle($col . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet2->getStyle($col . $row)->applyFromArray([
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
        
        // Datos
        foreach ($insumosNoSolicitados as $insumo) {
            $sheet2->setCellValue('A' . $row, $insumo->nombre_insumo);
            $sheet2->setCellValue('B' . $row, $insumo->tipoInsumo->nombre_tipo ?? 'N/A');
            $sheet2->setCellValue('C' . $row, $insumo->stock_actual);
            $sheet2->setCellValue('D' . $row, $insumo->unidadMedida->nombre_unidad_medida ?? 'N/A');
            $sheet2->getStyle('C' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            
            // Bordes para todas las celdas
            foreach (range('A', 'D') as $col) {
                $sheet2->getStyle($col . $row)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'CCCCCC'],
                        ],
                    ],
                ]);
            }
            $row++;
        }
        
        foreach (range('A', 'D') as $col) {
            $sheet2->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Hoja 3: Estadísticas
        $sheet3 = $spreadsheet->createSheet();
        $sheet3->setTitle('Estadísticas');
        
        $row = 1;
        
        // Encabezado principal
        $sheet3->setCellValue('A' . $row, 'Reporte de Insumos - GestionCIC');
        $sheet3->mergeCells('A' . $row . ':B' . $row);
        $sheet3->getStyle('A' . $row)->getFont()->setBold(true)->setSize(14);
        $sheet3->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $row++;
        
        $sheet3->setCellValue('A' . $row, 'Estadísticas del Período ' . ucfirst($request->tipo_periodo));
        $sheet3->mergeCells('A' . $row . ':B' . $row);
        $sheet3->getStyle('A' . $row)->getFont()->setBold(true)->setSize(12);
        $sheet3->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $row += 2;
        
        // Información del período
        $sheet3->setCellValue('A' . $row, 'Período:');
        $sheet3->setCellValue('B' . $row, $fechas['inicio']->format('d/m/Y') . ' - ' . $fechas['fin']->format('d/m/Y'));
        $sheet3->getStyle('A' . $row)->getFont()->setBold(true);
        $row++;
        
        $sheet3->setCellValue('A' . $row, 'Fecha de Generación:');
        $sheet3->setCellValue('B' . $row, now()->format('d/m/Y H:i'));
        $sheet3->getStyle('A' . $row)->getFont()->setBold(true);
        $row += 2;
        
        // Estadísticas
        $stats = [
            'Total de Solicitudes:' => $estadisticas['total_solicitudes'],
            'Total Insumos Solicitados:' => number_format($estadisticas['total_insumos_solicitados']),
            'Insumos Únicos Solicitados:' => $estadisticas['insumos_unicos_solicitados'],
            'Insumos No Solicitados:' => $estadisticas['insumos_no_solicitados'],
            'Total de Insumos en Sistema:' => $estadisticas['total_insumos'],
            'Porcentaje de Utilización:' => $estadisticas['porcentaje_utilizacion'] . '%',
        ];
        
        foreach ($stats as $label => $value) {
            $sheet3->setCellValue('A' . $row, $label);
            $sheet3->setCellValue('B' . $row, $value);
            $sheet3->getStyle('A' . $row)->getFont()->setBold(true);
            
            // Bordes
            $sheet3->getStyle('A' . $row)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC'],
                    ],
                ],
            ]);
            $sheet3->getStyle('B' . $row)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC'],
                    ],
                ],
            ]);
            $row++;
        }
        
        $sheet3->getColumnDimension('A')->setWidth(30);
        $sheet3->getColumnDimension('B')->setWidth(20);
        
        // Volver a la primera hoja
        $spreadsheet->setActiveSheetIndex(0);
        
        $writer = new Xlsx($spreadsheet);
        $nombreArchivo = 'Reporte_Insumos_' . $request->tipo_periodo . '_' . now()->format('Y-m-d') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }

    public function exportarPdf(Request $request)
    {
        $request->validate([
            'tipo_periodo' => 'required|in:semanal,mensual,semestral,anual',
            'fecha_referencia' => 'nullable|date',
        ]);

        $fechas = $this->reporteService->getFechasPeriodo(
            $request->tipo_periodo,
            $request->fecha_referencia
        );

        $insumosMasSolicitados = $this->reporteService->getInsumosMasSolicitados(
            $fechas['inicio'],
            $fechas['fin']
        );

        $insumosNoSolicitados = $this->reporteService->getInsumosNoSolicitados(
            $fechas['inicio'],
            $fechas['fin']
        );

        $estadisticas = $this->reporteService->getEstadisticasPeriodo(
            $fechas['inicio'],
            $fechas['fin']
        );

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);

        $html = view('reportes.insumos.pdf', [
            'tipoPeriodo' => $request->tipo_periodo,
            'fechaInicio' => $fechas['inicio'],
            'fechaFin' => $fechas['fin'],
            'insumosMasSolicitados' => $insumosMasSolicitados,
            'insumosNoSolicitados' => $insumosNoSolicitados,
            'estadisticas' => $estadisticas,
            'fecha' => now()->format('d/m/Y H:i:s')
        ])->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $nombreArchivo = 'Reporte_Insumos_' . $request->tipo_periodo . '_' . now()->format('Y-m-d') . '.pdf';

        return $dompdf->stream($nombreArchivo);
    }
}

