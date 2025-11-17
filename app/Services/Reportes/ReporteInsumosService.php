<?php

namespace App\Services\Reportes;

use App\Models\Insumo;
use App\Models\SolicitudItem;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReporteInsumosService
{
    /**
     * Obtiene los insumos más solicitados en un período
     */
    public function getInsumosMasSolicitados($fechaInicio, $fechaFin, $limite = 10)
    {
        return SolicitudItem::select(
                'insumos.id_insumo',
                'insumos.nombre_insumo',
                'insumos.tipo_insumo_id',
                DB::raw('SUM(solicitud_items.cantidad_solicitada) as total_solicitado'),
                DB::raw('COUNT(DISTINCT solicitud_items.solicitud_id) as veces_solicitado'),
                'tipo_insumos.nombre_tipo'
            )
            ->join('insumos', 'solicitud_items.insumo_id', '=', 'insumos.id_insumo')
            ->leftJoin('tipo_insumos', 'insumos.tipo_insumo_id', '=', 'tipo_insumos.id')
            ->join('solicitudes', 'solicitud_items.solicitud_id', '=', 'solicitudes.id')
            ->whereBetween('solicitudes.fecha_solicitud', [$fechaInicio, $fechaFin])
            ->whereIn('solicitudes.estado', ['aprobada', 'entregada'])
            ->groupBy('insumos.id_insumo', 'insumos.nombre_insumo', 'insumos.tipo_insumo_id', 'tipo_insumos.nombre_tipo')
            ->orderByDesc('total_solicitado')
            ->limit($limite)
            ->get();
    }

    /**
     * Obtiene los insumos NO solicitados en un período
     */
    public function getInsumosNoSolicitados($fechaInicio, $fechaFin)
    {
        $insumosSolicitados = SolicitudItem::select('insumo_id')
            ->join('solicitudes', 'solicitud_items.solicitud_id', '=', 'solicitudes.id')
            ->whereBetween('solicitudes.fecha_solicitud', [$fechaInicio, $fechaFin])
            ->whereIn('solicitudes.estado', ['aprobada', 'entregada'])
            ->distinct()
            ->pluck('insumo_id');

        return Insumo::with('tipoInsumo', 'unidadMedida')
            ->whereNotIn('id_insumo', $insumosSolicitados)
            ->orderBy('nombre_insumo')
            ->get();
    }

    /**
     * Obtiene estadísticas generales del período
     */
    public function getEstadisticasPeriodo($fechaInicio, $fechaFin)
    {
        $totalSolicitudes = DB::table('solicitudes')
            ->whereBetween('fecha_solicitud', [$fechaInicio, $fechaFin])
            ->whereIn('estado', ['aprobada', 'entregada'])
            ->count();
        
        $totalInsumosSolicitados = SolicitudItem::join('solicitudes', 'solicitud_items.solicitud_id', '=', 'solicitudes.id')
            ->whereBetween('solicitudes.fecha_solicitud', [$fechaInicio, $fechaFin])
            ->whereIn('solicitudes.estado', ['aprobada', 'entregada'])
            ->sum('cantidad_solicitada');
        
        $insumosUnicosSolicitados = SolicitudItem::join('solicitudes', 'solicitud_items.solicitud_id', '=', 'solicitudes.id')
            ->whereBetween('solicitudes.fecha_solicitud', [$fechaInicio, $fechaFin])
            ->whereIn('solicitudes.estado', ['aprobada', 'entregada'])
            ->distinct()
            ->count('solicitud_items.insumo_id');
        
        $insumosNoSolicitados = $this->getInsumosNoSolicitados($fechaInicio, $fechaFin)->count();
        
        $totalInsumos = Insumo::count();
        $porcentajeUtilizacion = $totalInsumos > 0 
            ? round(($insumosUnicosSolicitados / $totalInsumos) * 100, 2) 
            : 0;

        return [
            'total_solicitudes' => $totalSolicitudes,
            'total_insumos_solicitados' => $totalInsumosSolicitados,
            'insumos_unicos_solicitados' => $insumosUnicosSolicitados,
            'insumos_no_solicitados' => $insumosNoSolicitados,
            'total_insumos' => $totalInsumos,
            'porcentaje_utilizacion' => $porcentajeUtilizacion,
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

