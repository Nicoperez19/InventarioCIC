<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Services\Reportes\ReporteConsumoDepartamentoService;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Dompdf\Dompdf;
use Dompdf\Options;

class ReporteConsumoDepartamentoController extends Controller
{
    protected $reporteService;

    public function __construct(ReporteConsumoDepartamentoService $reporteService)
    {
        $this->middleware('auth');
        $this->reporteService = $reporteService;
    }

    public function index()
    {
        return view('reportes.consumo-departamento.index');
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

        $consumoPorDepartamento = $this->reporteService->getConsumoPorDepartamento(
            $fechas['inicio'],
            $fechas['fin']
        );

        $estadisticas = $this->reporteService->getEstadisticasConsumo(
            $fechas['inicio'],
            $fechas['fin']
        );

        $spreadsheet = new Spreadsheet();
        
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Consumo por Departamento');
        
        $sheet1->setCellValue('A1', 'Reporte de Consumo por Departamento - GestionCIC');
        $sheet1->mergeCells('A1:F1');
        $sheet1->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet1->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $sheet1->setCellValue('A3', 'Período:');
        $sheet1->setCellValue('B3', $fechas['inicio']->format('d/m/Y') . ' - ' . $fechas['fin']->format('d/m/Y'));
        $sheet1->getStyle('A3')->getFont()->setBold(true);
        
        $row = 5;
        $headers = ['Departamento', 'Total Entregado', 'Total Solicitado', 'Total Solicitudes', 'Insumos Diferentes'];
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
        foreach ($consumoPorDepartamento as $depto) {
            $sheet1->setCellValue('A' . $row, $depto->nombre_depto);
            $sheet1->setCellValue('B' . $row, $depto->total_entregado);
            $sheet1->setCellValue('C' . $row, $depto->total_solicitado);
            $sheet1->setCellValue('D' . $row, $depto->total_solicitudes);
            $sheet1->setCellValue('E' . $row, $depto->insumos_diferentes);
            $row++;
        }
        
        foreach (range('A', 'E') as $col) {
            $sheet1->getColumnDimension($col)->setAutoSize(true);
        }
        
        $writer = new Xlsx($spreadsheet);
        $nombreArchivo = 'Reporte_Consumo_Departamento_' . $request->tipo_periodo . '_' . now()->format('Y-m-d') . '.xlsx';
        
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

        $consumoPorDepartamento = $this->reporteService->getConsumoPorDepartamento(
            $fechas['inicio'],
            $fechas['fin']
        );

        $estadisticas = $this->reporteService->getEstadisticasConsumo(
            $fechas['inicio'],
            $fechas['fin']
        );

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);

        $html = view('reportes.consumo-departamento.pdf', [
            'tipoPeriodo' => $request->tipo_periodo,
            'fechaInicio' => $fechas['inicio'],
            'fechaFin' => $fechas['fin'],
            'consumoPorDepartamento' => $consumoPorDepartamento,
            'estadisticas' => $estadisticas,
            'fecha' => now()->format('d/m/Y H:i:s')
        ])->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $nombreArchivo = 'Reporte_Consumo_Departamento_' . $request->tipo_periodo . '_' . now()->format('Y-m-d') . '.pdf';

        return $dompdf->stream($nombreArchivo);
    }
}

