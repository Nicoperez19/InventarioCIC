<?php

namespace App\Services\Reportes;

use App\Models\Insumo;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReporteStockService
{
    /**
     * Obtiene insumos con stock crítico (por debajo del mínimo)
     */
    public function getStockCritico()
    {
        return Insumo::with(['tipoInsumo', 'unidadMedida'])
            ->whereColumn('stock_actual', '<=', 'stock_minimo')
            ->where('stock_actual', '>', 0)
            ->orderByRaw('(stock_actual - stock_minimo) ASC')
            ->get();
    }

    /**
     * Obtiene insumos agotados (stock = 0)
     */
    public function getStockAgotado()
    {
        return Insumo::with(['tipoInsumo', 'unidadMedida'])
            ->where('stock_actual', '<=', 0)
            ->orderBy('nombre_insumo')
            ->get();
    }

    /**
     * Obtiene insumos con stock bajo (entre 0 y mínimo)
     */
    public function getStockBajo()
    {
        return Insumo::with(['tipoInsumo', 'unidadMedida'])
            ->where('stock_actual', '>', 0)
            ->whereColumn('stock_actual', '<=', 'stock_minimo')
            ->orderByRaw('(stock_actual - stock_minimo) ASC')
            ->get();
    }

    /**
     * Obtiene estadísticas generales de stock
     */
    public function getEstadisticasStock()
    {
        $totalInsumos = Insumo::count();
        $stockCritico = $this->getStockCritico()->count();
        $stockAgotado = $this->getStockAgotado()->count();
        $stockNormal = Insumo::whereColumn('stock_actual', '>', 'stock_minimo')->count();
        $stockBajo = $this->getStockBajo()->count();
        
        $totalStockActual = Insumo::sum('stock_actual');
        $totalStockMinimo = Insumo::sum('stock_minimo');
        $diferenciaStock = $totalStockActual - $totalStockMinimo;

        return [
            'total_insumos' => $totalInsumos,
            'stock_critico' => $stockCritico,
            'stock_agotado' => $stockAgotado,
            'stock_bajo' => $stockBajo,
            'stock_normal' => $stockNormal,
            'total_stock_actual' => $totalStockActual,
            'total_stock_minimo' => $totalStockMinimo,
            'diferencia_stock' => $diferenciaStock,
            'porcentaje_critico' => $totalInsumos > 0 ? round(($stockCritico / $totalInsumos) * 100, 2) : 0,
            'porcentaje_agotado' => $totalInsumos > 0 ? round(($stockAgotado / $totalInsumos) * 100, 2) : 0,
        ];
    }

    /**
     * Obtiene insumos que necesitan reposición urgente
     */
    public function getNecesitanReposicion()
    {
        return Insumo::with(['tipoInsumo', 'unidadMedida'])
            ->whereColumn('stock_actual', '<=', 'stock_minimo')
            ->orderByRaw('(stock_actual - stock_minimo) ASC')
            ->get()
            ->map(function ($insumo) {
                $insumo->cantidad_faltante = max(0, $insumo->stock_minimo - $insumo->stock_actual);
                $insumo->porcentaje_stock = $insumo->stock_minimo > 0 
                    ? round(($insumo->stock_actual / $insumo->stock_minimo) * 100, 2) 
                    : 0;
                return $insumo;
            });
    }
}

