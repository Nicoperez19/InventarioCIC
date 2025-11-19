<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Services\Reportes\ReporteStockService;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Dompdf\Dompdf;
use Dompdf\Options;

class ReporteStockController extends Controller
{
    protected $reporteService;

    public function __construct(ReporteStockService $reporteService)
    {
        $this->middleware('auth');
        $this->reporteService = $reporteService;
    }

    public function index()
    {
        return view('reportes.stock.index');
    }

    public function exportarExcel(Request $request)
    {
        $tab = $request->input('tab', 'agotados'); // Por defecto 'agotados'
        
        // Obtener datos según la pestaña activa
        $stockCritico = $this->reporteService->getStockCritico();
        
        // Filtrar según la pestaña
        if ($tab === 'agotados') {
            $insumosParaExportar = $stockCritico->filter(function($item) {
                return $item->stock_actual <= 0;
            })->values();
            $titulo = 'Insumos Agotados';
            $nombreArchivo = 'Reporte_Stock_Agotado_' . now()->format('Y-m-d') . '.xlsx';
        } else {
            $insumosParaExportar = $stockCritico->filter(function($item) {
                if ($item->stock_actual <= 0) {
                    return false;
                }
                if ($item->stock_minimo > 0) {
                    return $item->stock_actual <= $item->stock_minimo;
                }
                return $item->stock_actual < 10;
            })->values();
            $titulo = 'Insumos con Stock Bajo';
            $nombreArchivo = 'Reporte_Stock_Bajo_' . now()->format('Y-m-d') . '.xlsx';
        }

        $estadisticas = $this->reporteService->getEstadisticasStock();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($tab === 'agotados' ? 'Agotados' : 'Stock Bajo');
        
        $sheet->setCellValue('A1', 'Reporte de ' . $titulo . ' - GestionCIC');
        $sheet->mergeCells('A1:D1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $sheet->setCellValue('A3', 'Fecha de Generación:');
        $sheet->setCellValue('B3', now()->format('d/m/Y H:i:s'));
        $sheet->getStyle('A3')->getFont()->setBold(true);
        
        $row = 5;
        $headers = ['Insumo', 'Tipo', 'Stock Actual', 'Unidad'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $row, $header);
            $sheet->getStyle($col . $row)->getFont()->setBold(true);
            $sheet->getStyle($col . $row)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB($tab === 'agotados' ? 'DC2626' : 'EA580C');
            $sheet->getStyle($col . $row)->getFont()->getColor()->setRGB('FFFFFF');
            $sheet->getStyle($col . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($col . $row)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ]);
            $col++;
        }
        
        $row++;
        foreach ($insumosParaExportar as $insumo) {
            $sheet->setCellValue('A' . $row, $insumo->nombre_insumo);
            $sheet->setCellValue('B' . $row, $insumo->tipoInsumo->nombre_tipo ?? 'N/A');
            $sheet->setCellValue('C' . $row, $insumo->stock_actual);
            $sheet->setCellValue('D' . $row, $insumo->unidadMedida->nombre_unidad_medida ?? 'N/A');
            
            foreach (range('A', 'D') as $col) {
                $sheet->getStyle($col . $row)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);
            }
            $row++;
        }
        
        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        $writer = new Xlsx($spreadsheet);
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }

    public function exportarPdf(Request $request)
    {
        $tab = $request->input('tab', 'agotados'); // Por defecto 'agotados'
        
        // Obtener datos según la pestaña activa
        $stockCritico = $this->reporteService->getStockCritico();
        
        // Filtrar según la pestaña
        if ($tab === 'agotados') {
            $insumosParaExportar = $stockCritico->filter(function($item) {
                return $item->stock_actual <= 0;
            })->values();
            $titulo = 'Insumos Agotados';
            $nombreArchivo = 'Reporte_Stock_Agotado_' . now()->format('Y-m-d') . '.pdf';
        } else {
            $insumosParaExportar = $stockCritico->filter(function($item) {
                if ($item->stock_actual <= 0) {
                    return false;
                }
                if ($item->stock_minimo > 0) {
                    return $item->stock_actual <= $item->stock_minimo;
                }
                return $item->stock_actual < 10;
            })->values();
            $titulo = 'Insumos con Stock Bajo';
            $nombreArchivo = 'Reporte_Stock_Bajo_' . now()->format('Y-m-d') . '.pdf';
        }

        $estadisticas = $this->reporteService->getEstadisticasStock();

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);

        $html = view('reportes.stock.pdf', [
            'insumos' => $insumosParaExportar,
            'titulo' => $titulo,
            'tab' => $tab,
            'estadisticas' => $estadisticas,
            'fecha' => now()->format('d/m/Y H:i:s')
        ])->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream($nombreArchivo);
    }
}

