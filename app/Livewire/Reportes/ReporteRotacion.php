<?php

namespace App\Livewire\Reportes;

use App\Services\Reportes\ReporteRotacionService;
use Livewire\Component;
use Carbon\Carbon;

class ReporteRotacion extends Component
{
    public $tipoPeriodo = 'mensual';
    public $fechaReferencia;
    public $mostrarResultados = false;
    public $altaRotacion = [];
    public $bajaRotacion = [];
    public $sinRotacion = [];
    public $estadisticas = [];
    public $fechaInicio;
    public $fechaFin;

    public function generar(ReporteRotacionService $reporteService)
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

        $altaRotacion = $reporteService->getAltaRotacion(
            $fechas['inicio'],
            $fechas['fin']
        );

        $this->altaRotacion = $altaRotacion->map(function($item) {
            return [
                'nombre_insumo' => $item['nombre_insumo'],
                'tipo_insumo' => $item['tipo_insumo'],
                'stock_actual' => $item['stock_actual'],
                'consumo_total' => $item['consumo_total'],
                'rotacion' => $item['rotacion'],
                'dias_rotacion' => $item['dias_rotacion'],
                'categoria_rotacion' => $item['categoria_rotacion'],
            ];
        })->toArray();

        $bajaRotacion = $reporteService->getBajaRotacion(
            $fechas['inicio'],
            $fechas['fin']
        );

        $this->bajaRotacion = $bajaRotacion->map(function($item) {
            return [
                'nombre_insumo' => $item['nombre_insumo'],
                'tipo_insumo' => $item['tipo_insumo'],
                'stock_actual' => $item['stock_actual'],
                'consumo_total' => $item['consumo_total'],
                'rotacion' => $item['rotacion'],
                'dias_rotacion' => $item['dias_rotacion'],
                'categoria_rotacion' => $item['categoria_rotacion'],
            ];
        })->toArray();

        $sinRotacion = $reporteService->getSinRotacion(
            $fechas['inicio'],
            $fechas['fin']
        );

        $this->sinRotacion = $sinRotacion->map(function($item) {
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

        $this->estadisticas = $reporteService->getEstadisticasRotacion(
            $fechas['inicio'],
            $fechas['fin']
        );

        $this->mostrarResultados = true;
    }

    public function render()
    {
        return view('reportes.rotacion.livewire');
    }
}

