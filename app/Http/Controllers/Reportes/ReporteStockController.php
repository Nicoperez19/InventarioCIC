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
        $stockCritico = $this->reporteService->getStockCritico();
        $stockAgotado = $this->reporteService->getStockAgotado();
        $necesitanReposicion = $this->reporteService->getNecesitanReposicion();
        $estadisticas = $this->reporteService->getEstadisticasStock();

        $spreadsheet = new Spreadsheet();
        
        // Hoja 1: Stock Crítico
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Stock Crítico');
        
        $sheet1->setCellValue('A1', 'Reporte de Stock Crítico y Bajo - GestionCIC');
        $sheet1->mergeCells('A1:F1');
        $sheet1->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet1->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $sheet1->setCellValue('A3', 'Fecha de Generación:');
        $sheet1->setCellValue('B3', now()->format('d/m/Y H:i:s'));
        $sheet1->getStyle('A3')->getFont()->setBold(true);
        
        $row = 5;
        $headers = ['Insumo', 'Tipo', 'Stock Actual', 'Stock Mínimo', 'Faltante', 'Unidad'];
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
                    ],
                ],
            ]);
            $col++;
        }
        
        $row++;
        foreach ($necesitanReposicion as $insumo) {
            $sheet1->setCellValue('A' . $row, $insumo->nombre_insumo);
            $sheet1->setCellValue('B' . $row, $insumo->tipoInsumo->nombre_tipo ?? 'N/A');
            $sheet1->setCellValue('C' . $row, $insumo->stock_actual);
            $sheet1->setCellValue('D' . $row, $insumo->stock_minimo);
            $sheet1->setCellValue('E' . $row, $insumo->cantidad_faltante);
            $sheet1->setCellValue('F' . $row, $insumo->unidadMedida->nombre_unidad_medida ?? 'N/A');
            
            foreach (range('A', 'F') as $col) {
                $sheet1->getStyle($col . $row)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);
            }
            $row++;
        }
        
        foreach (range('A', 'F') as $col) {
            $sheet1->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Hoja 2: Stock Agotado
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Stock Agotado');
        
        $sheet2->setCellValue('A1', 'Insumos con Stock Agotado');
        $sheet2->mergeCells('A1:D1');
        $sheet2->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet2->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $row = 3;
        $headers2 = ['Insumo', 'Tipo', 'Stock Mínimo', 'Unidad'];
        $col = 'A';
        foreach ($headers2 as $header) {
            $sheet2->setCellValue($col . $row, $header);
            $sheet2->getStyle($col . $row)->getFont()->setBold(true);
            $sheet2->getStyle($col . $row)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('306073');
            $sheet2->getStyle($col . $row)->getFont()->getColor()->setRGB('FFFFFF');
            $sheet2->getStyle($col . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $col++;
        }
        
        $row++;
        foreach ($stockAgotado as $insumo) {
            $sheet2->setCellValue('A' . $row, $insumo->nombre_insumo);
            $sheet2->setCellValue('B' . $row, $insumo->tipoInsumo->nombre_tipo ?? 'N/A');
            $sheet2->setCellValue('C' . $row, $insumo->stock_minimo);
            $sheet2->setCellValue('D' . $row, $insumo->unidadMedida->nombre_unidad_medida ?? 'N/A');
            $row++;
        }
        
        foreach (range('A', 'D') as $col) {
            $sheet2->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Hoja 3: Estadísticas
        $sheet3 = $spreadsheet->createSheet();
        $sheet3->setTitle('Estadísticas');
        
        $sheet3->setCellValue('A1', 'Estadísticas de Stock');
        $sheet3->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        
        $row = 3;
        $sheet3->setCellValue('A' . $row, 'Total de Insumos:');
        $sheet3->setCellValue('B' . $row, $estadisticas['total_insumos']);
        $sheet3->getStyle('A' . $row)->getFont()->setBold(true);
        $row++;
        
        $sheet3->setCellValue('A' . $row, 'Stock Crítico:');
        $sheet3->setCellValue('B' . $row, $estadisticas['stock_critico']);
        $sheet3->getStyle('A' . $row)->getFont()->setBold(true);
        $row++;
        
        $sheet3->setCellValue('A' . $row, 'Stock Agotado:');
        $sheet3->setCellValue('B' . $row, $estadisticas['stock_agotado']);
        $sheet3->getStyle('A' . $row)->getFont()->setBold(true);
        $row++;
        
        $sheet3->setCellValue('A' . $row, 'Stock Normal:');
        $sheet3->setCellValue('B' . $row, $estadisticas['stock_normal']);
        $sheet3->getStyle('A' . $row)->getFont()->setBold(true);
        $row++;
        
        $sheet3->setCellValue('A' . $row, 'Total Stock Actual:');
        $sheet3->setCellValue('B' . $row, $estadisticas['total_stock_actual']);
        $sheet3->getStyle('A' . $row)->getFont()->setBold(true);
        $row++;
        
        $sheet3->setCellValue('A' . $row, 'Total Stock Mínimo:');
        $sheet3->setCellValue('B' . $row, $estadisticas['total_stock_minimo']);
        $sheet3->getStyle('A' . $row)->getFont()->setBold(true);
        
        $sheet3->getColumnDimension('A')->setWidth(30);
        $sheet3->getColumnDimension('B')->setWidth(20);
        
        $spreadsheet->setActiveSheetIndex(0);
        
        $writer = new Xlsx($spreadsheet);
        $nombreArchivo = 'Reporte_Stock_Critico_' . now()->format('Y-m-d') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }

    public function exportarPdf(Request $request)
    {
        $stockCritico = $this->reporteService->getStockCritico();
        $stockAgotado = $this->reporteService->getStockAgotado();
        $necesitanReposicion = $this->reporteService->getNecesitanReposicion();
        $estadisticas = $this->reporteService->getEstadisticasStock();

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);

        $html = view('reportes.stock.pdf', [
            'stockCritico' => $stockCritico,
            'stockAgotado' => $stockAgotado,
            'necesitanReposicion' => $necesitanReposicion,
            'estadisticas' => $estadisticas,
            'fecha' => now()->format('d/m/Y H:i:s')
        ])->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $nombreArchivo = 'Reporte_Stock_Critico_' . now()->format('Y-m-d') . '.pdf';

        return $dompdf->stream($nombreArchivo);
    }
}

