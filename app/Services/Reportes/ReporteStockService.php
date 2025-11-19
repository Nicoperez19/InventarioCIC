<?php

namespace App\Services\Reportes;

use App\Models\Insumo;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReporteStockService
{
    /**
     * Obtiene insumos con stock crítico
     * 
     * DEFINICIÓN DE STOCK CRÍTICO:
     * 1. Si stock_minimo > 0: Insumos con stock_actual <= stock_minimo
     * 2. Si stock_minimo = 0 o NULL: Insumos agotados (stock_actual <= 0)
     * 
     * Esto significa que el stock está por debajo del nivel mínimo establecido
     * y requiere atención inmediata para evitar desabastecimiento.
     * 
     * Ejemplos:
     * - Si stock_minimo = 10 y stock_actual = 5 → CRÍTICO (50% del mínimo)
     * - Si stock_minimo = 10 y stock_actual = 0 → CRÍTICO (agotado)
     * - Si stock_minimo = 10 y stock_actual = 10 → CRÍTICO (justo en el mínimo)
     * - Si stock_minimo = 10 y stock_actual = 11 → NORMAL (por encima del mínimo)
     * - Si stock_minimo = 0 y stock_actual = 0 → CRÍTICO (agotado, sin mínimo definido)
     */
    public function getStockCritico()
    {
        return Insumo::with(['tipoInsumo', 'unidadMedida'])
            ->where(function($query) {
                // Caso 1: Insumos con stock_minimo definido (> 0) y stock_actual <= stock_minimo
                $query->where(function($q) {
                    $q->whereColumn('stock_actual', '<=', 'stock_minimo')
                      ->where('stock_minimo', '>', 0);
                })
                // Caso 2: Insumos sin stock_minimo definido (0 o NULL) pero agotados
                ->orWhere(function($q) {
                    $q->where('stock_actual', '<=', 0)
                      ->where(function($subQ) {
                          $subQ->where('stock_minimo', '<=', 0)
                               ->orWhereNull('stock_minimo');
                      });
                })
                // Caso 3: Insumos sin stock_minimo definido pero con stock bajo (menos de 10 unidades)
                ->orWhere(function($q) {
                    $q->where('stock_actual', '>', 0)
                      ->where('stock_actual', '<', 10)
                      ->where(function($subQ) {
                          $subQ->where('stock_minimo', '<=', 0)
                               ->orWhereNull('stock_minimo');
                      });
                });
            })
            ->orderByRaw('CASE WHEN stock_actual <= 0 THEN 0 ELSE (stock_actual - COALESCE(stock_minimo, 0)) END ASC')
            ->orderBy('nombre_insumo')
            ->get()
            ->map(function ($insumo) {
                // Si no tiene stock_minimo definido, usar 0 como referencia
                $stockMinimo = $insumo->stock_minimo ?? 0;
                
                // Agregar información adicional
                $insumo->cantidad_faltante = max(0, $stockMinimo - $insumo->stock_actual);
                $insumo->porcentaje_stock = $stockMinimo > 0 
                    ? round(($insumo->stock_actual / $stockMinimo) * 100, 2) 
                    : ($insumo->stock_actual > 0 ? 100 : 0);
                // Determinar nivel de criticidad
                $insumo->nivel_criticidad = $this->calcularNivelCriticidad($insumo);
                return $insumo;
            });
    }

    /**
     * Calcula el nivel de criticidad de un insumo
     * 
     * @param Insumo $insumo
     * @return string 'agotado', 'critico_alto', 'critico_medio', 'critico_bajo'
     */
    private function calcularNivelCriticidad($insumo)
    {
        if ($insumo->stock_actual <= 0) {
            return 'agotado';
        }
        
        $porcentaje = $insumo->stock_minimo > 0 
            ? ($insumo->stock_actual / $insumo->stock_minimo) * 100 
            : 0;
        
        if ($porcentaje <= 25) {
            return 'critico_alto'; // Menos del 25% del mínimo
        } elseif ($porcentaje <= 50) {
            return 'critico_medio'; // Entre 25% y 50% del mínimo
        } else {
            return 'critico_bajo'; // Entre 50% y 100% del mínimo
        }
    }

    /**
     * Obtiene insumos agotados (stock <= 0)
     * 
     * Estos son un subconjunto de los insumos con stock crítico.
     * Se muestran por separado porque requieren reposición inmediata.
     */
    public function getStockAgotado()
    {
        return Insumo::with(['tipoInsumo', 'unidadMedida'])
            ->where('stock_actual', '<=', 0)
            ->orderBy('nombre_insumo')
            ->get()
            ->map(function ($insumo) {
                // Agregar información adicional
                $insumo->cantidad_faltante = max(0, $insumo->stock_minimo - $insumo->stock_actual);
                return $insumo;
            });
    }

    /**
     * Obtiene insumos con stock bajo (entre 0 y mínimo)
     * 
     * Similar a stock crítico pero excluye los agotados.
     * Útil para diferenciar entre insumos que aún tienen stock vs. los agotados.
     */
    public function getStockBajo()
    {
        return Insumo::with(['tipoInsumo', 'unidadMedida'])
            ->where('stock_actual', '>', 0)
            ->whereColumn('stock_actual', '<=', 'stock_minimo')
            ->where('stock_minimo', '>', 0)
            ->orderByRaw('(stock_actual - stock_minimo) ASC')
            ->orderBy('nombre_insumo')
            ->get();
    }

    /**
     * Obtiene estadísticas generales de stock
     * Optimizado para calcular directamente en la base de datos
     */
    public function getEstadisticasStock()
    {
        $totalInsumos = Insumo::count();
        
        // Calcular stock crítico directamente en la base de datos (más eficiente)
        $stockCritico = Insumo::where(function($query) {
            $query->where(function($q) {
                $q->whereColumn('stock_actual', '<=', 'stock_minimo')
                  ->where('stock_minimo', '>', 0);
            })
            ->orWhere(function($q) {
                $q->where('stock_actual', '<=', 0)
                  ->where(function($subQ) {
                      $subQ->where('stock_minimo', '<=', 0)
                           ->orWhereNull('stock_minimo');
                  });
            });
        })->count();
        
        // Stock agotado
        $stockAgotado = Insumo::where('stock_actual', '<=', 0)->count();
        
        // Stock normal: stock_actual > stock_minimo (solo si stock_minimo > 0)
        $stockNormal = Insumo::where(function($query) {
            $query->whereColumn('stock_actual', '>', 'stock_minimo')
                  ->where('stock_minimo', '>', 0);
        })->count();
        
        // Stock bajo: stock_actual > 0 pero <= stock_minimo
        $stockBajo = Insumo::where('stock_actual', '>', 0)
            ->whereColumn('stock_actual', '<=', 'stock_minimo')
            ->where('stock_minimo', '>', 0)
            ->count();
        
        $totalStockActual = Insumo::sum('stock_actual') ?? 0;
        $totalStockMinimo = Insumo::sum('stock_minimo') ?? 0;
        $diferenciaStock = $totalStockActual - $totalStockMinimo;

        // Contar agotados dentro de los críticos
        $agotadosEnCriticos = Insumo::where('stock_actual', '<=', 0)->count();
        $criticosConStock = $stockCritico - $agotadosEnCriticos;

        return [
            'total_insumos' => $totalInsumos,
            'insumos_criticos' => $stockCritico, // Total de insumos que necesitan atención
            'agotados' => $stockAgotado, // Solo para referencia interna
            'con_stock_bajo' => $criticosConStock, // Críticos que aún tienen stock
            'total_stock_actual' => $totalStockActual,
            'total_stock_minimo' => $totalStockMinimo,
            'diferencia_stock' => $diferenciaStock,
            'porcentaje_critico' => $totalInsumos > 0 ? round(($stockCritico / $totalInsumos) * 100, 2) : 0,
        ];
    }

    /**
     * Obtiene insumos que necesitan reposición urgente
     * Incluye todos los insumos con stock_actual <= stock_minimo (críticos y agotados)
     */
    public function getNecesitanReposicion()
    {
        return Insumo::with(['tipoInsumo', 'unidadMedida'])
            ->where(function($query) {
                $query->whereColumn('stock_actual', '<=', 'stock_minimo')
                      ->where('stock_minimo', '>', 0); // Solo insumos con mínimo definido
            })
            ->orderByRaw('CASE WHEN stock_actual <= 0 THEN 0 ELSE (stock_actual - stock_minimo) END ASC')
            ->orderBy('nombre_insumo')
            ->get()
            ->map(function ($insumo) {
                $insumo->cantidad_faltante = max(0, $insumo->stock_minimo - $insumo->stock_actual);
                $insumo->porcentaje_stock = $insumo->stock_minimo > 0 
                    ? round(($insumo->stock_actual / $insumo->stock_minimo) * 100, 2) 
                    : 0;
                // Calcular cantidad recomendada para reposición (mínimo + 20% de margen)
                $insumo->cantidad_recomendada = max(
                    $insumo->stock_minimo, 
                    (int) ceil($insumo->stock_minimo * 1.2)
                );
                return $insumo;
            });
    }
}

