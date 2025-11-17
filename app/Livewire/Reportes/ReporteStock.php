<?php

namespace App\Livewire\Reportes;

use App\Services\Reportes\ReporteStockService;
use Livewire\Component;

class ReporteStock extends Component
{
    public $mostrarResultados = false;
    public $stockCritico = [];
    public $stockAgotado = [];
    public $necesitanReposicion = [];
    public $estadisticas = [];

    public function generar(ReporteStockService $reporteService)
    {
        $this->stockCritico = $reporteService->getStockCritico()->map(function($item) {
            return [
                'id_insumo' => $item->id_insumo,
                'nombre_insumo' => $item->nombre_insumo,
                'tipo_insumo' => [
                    'nombre_tipo' => $item->tipoInsumo->nombre_tipo ?? 'N/A',
                ],
                'stock_actual' => $item->stock_actual,
                'stock_minimo' => $item->stock_minimo,
                'cantidad_faltante' => max(0, $item->stock_minimo - $item->stock_actual),
                'unidad_medida' => [
                    'nombre_unidad_medida' => $item->unidadMedida->nombre_unidad_medida ?? 'N/A',
                ],
            ];
        })->toArray();

        $this->stockAgotado = $reporteService->getStockAgotado()->map(function($item) {
            return [
                'id_insumo' => $item->id_insumo,
                'nombre_insumo' => $item->nombre_insumo,
                'tipo_insumo' => [
                    'nombre_tipo' => $item->tipoInsumo->nombre_tipo ?? 'N/A',
                ],
                'stock_minimo' => $item->stock_minimo,
                'unidad_medida' => [
                    'nombre_unidad_medida' => $item->unidadMedida->nombre_unidad_medida ?? 'N/A',
                ],
            ];
        })->toArray();

        $necesitanReposicion = $reporteService->getNecesitanReposicion();
        $this->necesitanReposicion = $necesitanReposicion->map(function($item) {
            return [
                'id_insumo' => $item->id_insumo,
                'nombre_insumo' => $item->nombre_insumo,
                'tipo_insumo' => [
                    'nombre_tipo' => $item->tipoInsumo->nombre_tipo ?? 'N/A',
                ],
                'stock_actual' => $item->stock_actual,
                'stock_minimo' => $item->stock_minimo,
                'cantidad_faltante' => $item->cantidad_faltante,
                'porcentaje_stock' => $item->porcentaje_stock,
                'unidad_medida' => [
                    'nombre_unidad_medida' => $item->unidadMedida->nombre_unidad_medida ?? 'N/A',
                ],
            ];
        })->toArray();

        $this->estadisticas = $reporteService->getEstadisticasStock();
        $this->mostrarResultados = true;
    }

    public function render()
    {
        return view('reportes.stock.livewire');
    }
}

