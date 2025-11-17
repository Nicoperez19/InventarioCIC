<?php

namespace App\Livewire\Reportes;

use App\Services\Reportes\ReporteConsumoDepartamentoService;
use Livewire\Component;
use Carbon\Carbon;

class ReporteConsumoDepartamento extends Component
{
    public $tipoPeriodo = 'mensual';
    public $fechaReferencia;
    public $mostrarResultados = false;
    public $consumoPorDepartamento = [];
    public $estadisticas = [];
    public $fechaInicio;
    public $fechaFin;
    public $departamentoSeleccionado = null;
    public $insumosPorDepartamento = [];

    public function generar(ReporteConsumoDepartamentoService $reporteService)
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

        $consumoPorDepartamento = $reporteService->getConsumoPorDepartamento(
            $fechas['inicio'],
            $fechas['fin']
        );

        $this->consumoPorDepartamento = $consumoPorDepartamento->map(function($item) {
            return [
                'id_depto' => $item->id_depto,
                'nombre_depto' => $item->nombre_depto,
                'total_entregado' => $item->total_entregado,
                'total_solicitado' => $item->total_solicitado,
                'total_solicitudes' => $item->total_solicitudes,
                'insumos_diferentes' => $item->insumos_diferentes,
            ];
        })->toArray();

        $this->estadisticas = $reporteService->getEstadisticasConsumo(
            $fechas['inicio'],
            $fechas['fin']
        );

        $this->mostrarResultados = true;
    }

    public function verInsumos($departamentoId, ReporteConsumoDepartamentoService $reporteService)
    {
        $this->departamentoSeleccionado = $departamentoId;
        
        $fechas = $reporteService->getFechasPeriodo(
            $this->tipoPeriodo,
            $this->fechaReferencia
        );

        $insumos = $reporteService->getInsumosPorDepartamento(
            $departamentoId,
            $fechas['inicio'],
            $fechas['fin']
        );

        $this->insumosPorDepartamento = $insumos->map(function($item) {
            return [
                'nombre_insumo' => $item->nombre_insumo,
                'nombre_tipo' => $item->nombre_tipo,
                'total_entregado' => $item->total_entregado,
                'total_solicitado' => $item->total_solicitado,
                'veces_solicitado' => $item->veces_solicitado,
            ];
        })->toArray();
    }

    public function render()
    {
        return view('reportes.consumo-departamento.livewire');
    }
}

