<?php

namespace App\Services\Reportes;

use App\Models\Insumo;
use App\Models\SolicitudItem;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReporteRotacionService
{
    /**
     * Calcula la rotación de inventario para un insumo en un período
     */
    public function calcularRotacionInsumo($insumoId, $fechaInicio, $fechaFin)
    {
        // Obtener consumo total en el período
        $consumoTotal = SolicitudItem::join('solicitudes', 'solicitud_items.solicitud_id', '=', 'solicitudes.id')
            ->where('solicitud_items.insumo_id', $insumoId)
            ->whereBetween('solicitudes.fecha_solicitud', [$fechaInicio, $fechaFin])
            ->whereIn('solicitudes.estado', ['aprobada', 'entregada'])
            ->sum('solicitud_items.cantidad_entregada');

        // Obtener stock promedio (simplificado: stock actual)
        $insumo = Insumo::find($insumoId);
        $stockPromedio = $insumo ? $insumo->stock_actual : 0;

        // Calcular días del período
        $diasPeriodo = $fechaInicio->diffInDays($fechaFin) + 1;

        // Rotación = Consumo / Stock Promedio
        $rotacion = $stockPromedio > 0 ? round($consumoTotal / $stockPromedio, 2) : 0;

        // Días de rotación = Días del período / Rotación
        $diasRotacion = $rotacion > 0 ? round($diasPeriodo / $rotacion, 2) : 999;

        return [
            'consumo_total' => $consumoTotal,
            'stock_promedio' => $stockPromedio,
            'rotacion' => $rotacion,
            'dias_rotacion' => $diasRotacion,
            'dias_periodo' => $diasPeriodo,
        ];
    }

    /**
     * Obtiene la rotación de todos los insumos en un período
     */
    public function getRotacionInventario($fechaInicio, $fechaFin, $limite = null)
    {
        // Obtener todos los insumos con su consumo
        $insumos = Insumo::with(['tipoInsumo', 'unidadMedida'])->get();

        $rotaciones = $insumos->map(function ($insumo) use ($fechaInicio, $fechaFin) {
            $datos = $this->calcularRotacionInsumo($insumo->id_insumo, $fechaInicio, $fechaFin);
            
            return [
                'id_insumo' => $insumo->id_insumo,
                'nombre_insumo' => $insumo->nombre_insumo,
                'tipo_insumo' => $insumo->tipoInsumo->nombre_tipo ?? 'N/A',
                'stock_actual' => $insumo->stock_actual,
                'stock_minimo' => $insumo->stock_minimo,
                'consumo_total' => $datos['consumo_total'],
                'stock_promedio' => $datos['stock_promedio'],
                'rotacion' => $datos['rotacion'],
                'dias_rotacion' => $datos['dias_rotacion'],
                'categoria_rotacion' => $this->categorizarRotacion($datos['rotacion'], $datos['dias_rotacion']),
            ];
        });

        // Filtrar solo los que tienen consumo
        $rotaciones = $rotaciones->filter(function ($item) {
            return $item['consumo_total'] > 0;
        });

        // Ordenar por rotación descendente
        $rotaciones = $rotaciones->sortByDesc('rotacion')->values();

        if ($limite) {
            return $rotaciones->take($limite);
        }

        return $rotaciones;
    }

    /**
     * Obtiene insumos de alta rotación
     */
    public function getAltaRotacion($fechaInicio, $fechaFin, $limite = 10)
    {
        $rotaciones = $this->getRotacionInventario($fechaInicio, $fechaFin);
        
        return $rotaciones->filter(function ($item) {
            return $item['rotacion'] >= 2; // Rotación >= 2 veces en el período
        })->take($limite);
    }

    /**
     * Obtiene insumos de baja rotación
     */
    public function getBajaRotacion($fechaInicio, $fechaFin, $limite = 10)
    {
        $rotaciones = $this->getRotacionInventario($fechaInicio, $fechaFin);
        
        return $rotaciones->filter(function ($item) {
            return $item['rotacion'] < 0.5 && $item['dias_rotacion'] > 60; // Rotación < 0.5 y más de 60 días
        })->take($limite);
    }

    /**
     * Obtiene insumos sin rotación (sin consumo)
     */
    public function getSinRotacion($fechaInicio, $fechaFin)
    {
        $insumosConConsumo = SolicitudItem::join('solicitudes', 'solicitud_items.solicitud_id', '=', 'solicitudes.id')
            ->whereBetween('solicitudes.fecha_solicitud', [$fechaInicio, $fechaFin])
            ->whereIn('solicitudes.estado', ['aprobada', 'entregada'])
            ->distinct()
            ->pluck('solicitud_items.insumo_id');

        return Insumo::with(['tipoInsumo', 'unidadMedida'])
            ->whereNotIn('id_insumo', $insumosConConsumo)
            ->where('stock_actual', '>', 0) // Solo los que tienen stock
            ->orderBy('nombre_insumo')
            ->get();
    }

    /**
     * Categoriza la rotación
     */
    private function categorizarRotacion($rotacion, $diasRotacion)
    {
        if ($rotacion >= 2) {
            return 'alta';
        } elseif ($rotacion >= 0.5) {
            return 'media';
        } elseif ($rotacion > 0) {
            return 'baja';
        } else {
            return 'sin_rotacion';
        }
    }

    /**
     * Obtiene estadísticas de rotación
     */
    public function getEstadisticasRotacion($fechaInicio, $fechaFin)
    {
        $rotaciones = $this->getRotacionInventario($fechaInicio, $fechaFin);
        
        $totalInsumos = Insumo::count();
        $insumosConRotacion = $rotaciones->count();
        $insumosSinRotacion = $this->getSinRotacion($fechaInicio, $fechaFin)->count();
        
        $altaRotacion = $rotaciones->filter(fn($item) => $item['categoria_rotacion'] === 'alta')->count();
        $mediaRotacion = $rotaciones->filter(fn($item) => $item['categoria_rotacion'] === 'media')->count();
        $bajaRotacion = $rotaciones->filter(fn($item) => $item['categoria_rotacion'] === 'baja')->count();

        $rotacionPromedio = $rotaciones->count() > 0 
            ? round($rotaciones->avg('rotacion'), 2) 
            : 0;

        $diasRotacionPromedio = $rotaciones->count() > 0 
            ? round($rotaciones->avg('dias_rotacion'), 2) 
            : 0;

        return [
            'total_insumos' => $totalInsumos,
            'insumos_con_rotacion' => $insumosConRotacion,
            'insumos_sin_rotacion' => $insumosSinRotacion,
            'alta_rotacion' => $altaRotacion,
            'media_rotacion' => $mediaRotacion,
            'baja_rotacion' => $bajaRotacion,
            'rotacion_promedio' => $rotacionPromedio,
            'dias_rotacion_promedio' => $diasRotacionPromedio,
        ];
    }

    /**
     * Obtiene fechas según el tipo de período
     */
    public function getFechasPeriodo($tipoPeriodo, $fechaReferencia = null)
    {
        $fecha = $fechaReferencia ? Carbon::parse($fechaReferencia) : Carbon::now();

        return match($tipoPeriodo) {
            'semanal' => [
                'inicio' => $fecha->copy()->startOfWeek(),
                'fin' => $fecha->copy()->endOfWeek(),
            ],
            'mensual' => [
                'inicio' => $fecha->copy()->startOfMonth(),
                'fin' => $fecha->copy()->endOfMonth(),
            ],
            'semestral' => [
                'inicio' => $fecha->copy()->month <= 6 
                    ? $fecha->copy()->startOfYear() 
                    : $fecha->copy()->month(7)->startOfMonth(),
                'fin' => $fecha->copy()->month <= 6 
                    ? $fecha->copy()->month(6)->endOfMonth() 
                    : $fecha->copy()->endOfYear(),
            ],
            'anual' => [
                'inicio' => $fecha->copy()->startOfYear(),
                'fin' => $fecha->copy()->endOfYear(),
            ],
            default => [
                'inicio' => $fecha->copy()->startOfMonth(),
                'fin' => $fecha->copy()->endOfMonth(),
            ],
        };
    }
}

