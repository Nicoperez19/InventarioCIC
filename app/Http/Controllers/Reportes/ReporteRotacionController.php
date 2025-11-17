<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Services\Reportes\ReporteRotacionService;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Dompdf\Dompdf;
use Dompdf\Options;

class ReporteRotacionController extends Controller
{
    protected $reporteService;

    public function __construct(ReporteRotacionService $reporteService)
    {
        $this->middleware('auth');
        $this->reporteService = $reporteService;
    }

    public function index()
    {
        return view('reportes.rotacion.index');
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

        $altaRotacion = $this->reporteService->getAltaRotacion(
            $fechas['inicio'],
            $fechas['fin'],
            50
        );

        $bajaRotacion = $this->reporteService->getBajaRotacion(
            $fechas['inicio'],
            $fechas['fin'],
            50
        );

        $sinRotacion = $this->reporteService->getSinRotacion(
            $fechas['inicio'],
            $fechas['fin']
        );

        $estadisticas = $this->reporteService->getEstadisticasRotacion(
            $fechas['inicio'],
            $fechas['fin']
        );

        $spreadsheet = new Spreadsheet();
        
        // Hoja 1: Alta Rotación
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Alta Rotación');
        
        $sheet1->setCellValue('A1', 'Insumos con Alta Rotación - ' . ucfirst($request->tipo_periodo));
        $sheet1->mergeCells('A1:G1');
        $sheet1->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet1->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $sheet1->setCellValue('A3', 'Período:');
        $sheet1->setCellValue('B3', $fechas['inicio']->format('d/m/Y') . ' - ' . $fechas['fin']->format('d/m/Y'));
        $sheet1->getStyle('A3')->getFont()->setBold(true);
        
        $row = 5;
        $headers = ['Insumo', 'Tipo', 'Stock Actual', 'Consumo Total', 'Rotación', 'Días Rotación', 'Categoría'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet1->setCellValue($col . $row, $header);
            $sheet1->getStyle($col . $row)->getFont()->setBold(true);
            $sheet1->getStyle($col . $row)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('306073');
            $sheet1->getStyle($col . $row)->getFont()->getColor()->setRGB('FFFFFF');
            $sheet1->getStyle($col . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $col++;
        }
        
        $row++;
        foreach ($altaRotacion as $item) {
            $sheet1->setCellValue('A' . $row, $item['nombre_insumo']);
            $sheet1->setCellValue('B' . $row, $item['tipo_insumo']);
            $sheet1->setCellValue('C' . $row, $item['stock_actual']);
            $sheet1->setCellValue('D' . $row, $item['consumo_total']);
            $sheet1->setCellValue('E' . $row, $item['rotacion']);
            $sheet1->setCellValue('F' . $row, $item['dias_rotacion']);
            $sheet1->setCellValue('G' . $row, ucfirst($item['categoria_rotacion']));
            $row++;
        }
        
        foreach (range('A', 'G') as $col) {
            $sheet1->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Hoja 2: Baja Rotación
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Baja Rotación');
        
        $sheet2->setCellValue('A1', 'Insumos con Baja Rotación - ' . ucfirst($request->tipo_periodo));
        $sheet2->mergeCells('A1:G1');
        $sheet2->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet2->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $row = 3;
        $sheet2->setCellValue('A3', 'Período:');
        $sheet2->setCellValue('B3', $fechas['inicio']->format('d/m/Y') . ' - ' . $fechas['fin']->format('d/m/Y'));
        $sheet2->getStyle('A3')->getFont()->setBold(true);
        
        $row = 5;
        $headers2 = ['Insumo', 'Tipo', 'Stock Actual', 'Consumo Total', 'Rotación', 'Días Rotación', 'Categoría'];
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
        foreach ($bajaRotacion as $item) {
            $sheet2->setCellValue('A' . $row, $item['nombre_insumo']);
            $sheet2->setCellValue('B' . $row, $item['tipo_insumo']);
            $sheet2->setCellValue('C' . $row, $item['stock_actual']);
            $sheet2->setCellValue('D' . $row, $item['consumo_total']);
            $sheet2->setCellValue('E' . $row, $item['rotacion']);
            $sheet2->setCellValue('F' . $row, $item['dias_rotacion']);
            $sheet2->setCellValue('G' . $row, ucfirst($item['categoria_rotacion']));
            $row++;
        }
        
        foreach (range('A', 'G') as $col) {
            $sheet2->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Hoja 3: Sin Rotación
        $sheet3 = $spreadsheet->createSheet();
        $sheet3->setTitle('Sin Rotación');
        
        $sheet3->setCellValue('A1', 'Insumos Sin Rotación - ' . ucfirst($request->tipo_periodo));
        $sheet3->mergeCells('A1:D1');
        $sheet3->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet3->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $row = 3;
        $headers3 = ['Insumo', 'Tipo', 'Stock Actual', 'Unidad'];
        $col = 'A';
        foreach ($headers3 as $header) {
            $sheet3->setCellValue($col . $row, $header);
            $sheet3->getStyle($col . $row)->getFont()->setBold(true);
            $sheet3->getStyle($col . $row)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('306073');
            $sheet3->getStyle($col . $row)->getFont()->getColor()->setRGB('FFFFFF');
            $sheet3->getStyle($col . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $col++;
        }
        
        $row++;
        foreach ($sinRotacion as $insumo) {
            $sheet3->setCellValue('A' . $row, $insumo->nombre_insumo);
            $sheet3->setCellValue('B' . $row, $insumo->tipoInsumo->nombre_tipo ?? 'N/A');
            $sheet3->setCellValue('C' . $row, $insumo->stock_actual);
            $sheet3->setCellValue('D' . $row, $insumo->unidadMedida->nombre_unidad_medida ?? 'N/A');
            $row++;
        }
        
        foreach (range('A', 'D') as $col) {
            $sheet3->getColumnDimension($col)->setAutoSize(true);
        }
        
        $spreadsheet->setActiveSheetIndex(0);
        
        $writer = new Xlsx($spreadsheet);
        $nombreArchivo = 'Reporte_Rotacion_' . $request->tipo_periodo . '_' . now()->format('Y-m-d') . '.xlsx';
        
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

        $altaRotacion = $this->reporteService->getAltaRotacion(
            $fechas['inicio'],
            $fechas['fin']
        );

        $bajaRotacion = $this->reporteService->getBajaRotacion(
            $fechas['inicio'],
            $fechas['fin']
        );

        $sinRotacion = $this->reporteService->getSinRotacion(
            $fechas['inicio'],
            $fechas['fin']
        );

        $estadisticas = $this->reporteService->getEstadisticasRotacion(
            $fechas['inicio'],
            $fechas['fin']
        );

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);

        $html = view('reportes.rotacion.pdf', [
            'tipoPeriodo' => $request->tipo_periodo,
            'fechaInicio' => $fechas['inicio'],
            'fechaFin' => $fechas['fin'],
            'altaRotacion' => $altaRotacion,
            'bajaRotacion' => $bajaRotacion,
            'sinRotacion' => $sinRotacion,
            'estadisticas' => $estadisticas,
            'fecha' => now()->format('d/m/Y H:i:s')
        ])->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $nombreArchivo = 'Reporte_Rotacion_' . $request->tipo_periodo . '_' . now()->format('Y-m-d') . '.pdf';

        return $dompdf->stream($nombreArchivo);
    }
}

