<?php

namespace App\Livewire\Reportes;

use App\Services\Reportes\ReporteInsumosService;
use Livewire\Component;

class ReporteInsumos extends Component
{
    public $tipoPeriodo = 'mensual';
    public $fechaReferencia;
    public $mostrarResultados = false;
    public $insumosMasSolicitados = [];
    public $insumosNoSolicitados = [];
    public $estadisticas = [];
    public $fechaInicio;
    public $fechaFin;

    public function generar(ReporteInsumosService $reporteService)
    {
        $this->validate([
            'tipoPeriodo' => 'required|in:semanal,mensual,semestral,anual',
            'fechaReferencia' => 'nullable|date',
        ]);

        $fechas = $reporteService->getFechasPeriodo(
            $this->tipoPeriodo,
            $this->fechaReferencia
        );

        $this->fechaInicio = $fechas['inicio']->format('Y-m-d');
        $this->fechaFin = $fechas['fin']->format('Y-m-d');

        $insumosMasSolicitados = $reporteService->getInsumosMasSolicitados(
            $fechas['inicio'],
            $fechas['fin']
        );

        $insumosNoSolicitados = $reporteService->getInsumosNoSolicitados(
            $fechas['inicio'],
            $fechas['fin']
        );

        $this->insumosMasSolicitados = $insumosMasSolicitados->map(function($item) {
            return [
                'nombre_insumo' => $item->nombre_insumo,
                'nombre_tipo' => $item->nombre_tipo,
                'total_solicitado' => $item->total_solicitado,
                'veces_solicitado' => $item->veces_solicitado,
            ];
        })->toArray();

        $this->insumosNoSolicitados = $insumosNoSolicitados->map(function($item) {
            return [
                'nombre_insumo' => $item->nombre_insumo,
                'tipo_insumo' => [
                    'nombre_tipo' => $item->tipoInsumo->nombre_tipo ?? 'N/A',
                ],
                'stock_actual' => $item->stock_actual,
                'unidad_medida' => [
                    'nombre_unidad_medida' => $item->unidadMedida->nombre_unidad_medida ?? 'N/A',
                ],
            ];
        })->toArray();

        $this->estadisticas = $reporteService->getEstadisticasPeriodo(
            $fechas['inicio'],
            $fechas['fin']
        );

        $this->mostrarResultados = true;
    }

    public function render()
    {
        return view('reportes.insumos.livewire');
    }
}

