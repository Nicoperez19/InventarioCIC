<?php

namespace App\Livewire\Reportes;

use App\Services\Reportes\ReporteStockService;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;

class ReporteStock extends Component
{
    use WithPagination;

    public $mostrarResultados = false;
    public $insumosCriticosData = [];
    public $estadisticas = [];
    public $perPage = 15;
    public $tabActiva = 'agotados'; // 'agotados' o 'bajo'

    public function cambiarTab($tab)
    {
        $this->tabActiva = $tab;
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function generar(ReporteStockService $reporteService)
    {
        // Obtener todos los insumos críticos (incluye agotados y los que están por debajo del mínimo)
        $insumosCriticos = $reporteService->getStockCritico();
        
        $this->insumosCriticosData = $insumosCriticos->map(function($item) {
            return [
                'id_insumo' => $item->id_insumo,
                'nombre_insumo' => $item->nombre_insumo,
                'tipo_insumo' => [
                    'nombre_tipo' => $item->tipoInsumo->nombre_tipo ?? 'N/A',
                ],
                'stock_actual' => $item->stock_actual,
                'stock_minimo' => $item->stock_minimo ?? 0,
                'cantidad_faltante' => $item->cantidad_faltante ?? max(0, ($item->stock_minimo ?? 0) - $item->stock_actual),
                'porcentaje_stock' => $item->porcentaje_stock ?? 0,
                'nivel_criticidad' => $item->nivel_criticidad ?? 'critico_bajo',
                'estado' => $item->stock_actual <= 0 ? 'agotado' : 'critico',
                'unidad_medida' => [
                    'nombre_unidad_medida' => $item->unidadMedida->nombre_unidad_medida ?? 'N/A',
                ],
            ];
        })->toArray();

        // Estadísticas simplificadas
        $this->estadisticas = $reporteService->getEstadisticasStock();
        $this->mostrarResultados = true;
        $this->resetPage(); // Resetear a la primera página al generar nuevo reporte
    }

    public function render()
    {
        $insumosCriticos = collect($this->insumosCriticosData);
        
        // Separar insumos agotados y con stock bajo
        // Agotados: stock_actual <= 0
        $insumosAgotados = $insumosCriticos->filter(function($item) {
            return $item['stock_actual'] <= 0;
        })->values();
        
        // Stock bajo: insumos con stock_actual > 0 pero que están por debajo del mínimo
        // Caso 1: Si tienen stock_minimo definido (> 0) y stock_actual <= stock_minimo
        // Caso 2: Si NO tienen stock_minimo definido pero tienen stock bajo (menos de 10 unidades como umbral)
        $insumosBajo = $insumosCriticos->filter(function($item) {
            if ($item['stock_actual'] <= 0) {
                return false; // No incluir agotados
            }
            
            // Si tiene stock_minimo definido, usar esa lógica
            if ($item['stock_minimo'] > 0) {
                return $item['stock_actual'] <= $item['stock_minimo'];
            }
            
            // Si no tiene stock_minimo definido, considerar "bajo" si tiene menos de 10 unidades
            return $item['stock_actual'] < 10;
        })->values();
        
        // Seleccionar la colección según la pestaña activa
        $insumosParaMostrar = $this->tabActiva === 'agotados' ? $insumosAgotados : $insumosBajo;
        
        // Crear paginador manual para el array
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = $this->perPage;
        $currentItems = $insumosParaMostrar->slice(($currentPage - 1) * $perPage, $perPage)->values();
        
        $insumosPaginated = new LengthAwarePaginator(
            $currentItems,
            $insumosParaMostrar->count(),
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'pageName' => 'page',
            ]
        );

        return view('reportes.stock.livewire', [
            'insumosCriticos' => $insumosPaginated,
            'totalAgotados' => $insumosAgotados->count(),
            'totalBajo' => $insumosBajo->count(),
        ]);
    }

    public function paginationView()
    {
        return 'vendor.livewire.tailwind';
    }
}

