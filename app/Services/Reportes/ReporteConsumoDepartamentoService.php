<?php

namespace App\Services\Reportes;

use App\Models\SolicitudItem;
use App\Models\Departamento;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReporteConsumoDepartamentoService
{
    /**
     * Obtiene el consumo de insumos por departamento en un período
     */
    public function getConsumoPorDepartamento($fechaInicio, $fechaFin)
    {
        return SolicitudItem::select(
                'departamentos.id_depto',
                'departamentos.nombre_depto',
                DB::raw('SUM(solicitud_items.cantidad_entregada) as total_entregado'),
                DB::raw('SUM(solicitud_items.cantidad_solicitada) as total_solicitado'),
                DB::raw('COUNT(DISTINCT solicitudes.id) as total_solicitudes'),
                DB::raw('COUNT(DISTINCT solicitud_items.insumo_id) as insumos_diferentes')
            )
            ->join('solicitudes', 'solicitud_items.solicitud_id', '=', 'solicitudes.id')
            ->join('departamentos', 'solicitudes.departamento_id', '=', 'departamentos.id_depto')
            ->whereBetween('solicitudes.fecha_solicitud', [$fechaInicio, $fechaFin])
            ->whereIn('solicitudes.estado', ['aprobada', 'entregada'])
            ->groupBy('departamentos.id_depto', 'departamentos.nombre_depto')
            ->orderByDesc('total_entregado')
            ->get();
    }

    /**
     * Obtiene los insumos más consumidos por departamento
     */
    public function getInsumosPorDepartamento($departamentoId, $fechaInicio, $fechaFin, $limite = 10)
    {
        return SolicitudItem::select(
                'insumos.id_insumo',
                'insumos.nombre_insumo',
                'tipo_insumos.nombre_tipo',
                DB::raw('SUM(solicitud_items.cantidad_entregada) as total_entregado'),
                DB::raw('SUM(solicitud_items.cantidad_solicitada) as total_solicitado'),
                DB::raw('COUNT(DISTINCT solicitudes.id) as veces_solicitado')
            )
            ->join('solicitudes', 'solicitud_items.solicitud_id', '=', 'solicitudes.id')
            ->join('insumos', 'solicitud_items.insumo_id', '=', 'insumos.id_insumo')
            ->leftJoin('tipo_insumos', 'insumos.tipo_insumo_id', '=', 'tipo_insumos.id')
            ->where('solicitudes.departamento_id', $departamentoId)
            ->whereBetween('solicitudes.fecha_solicitud', [$fechaInicio, $fechaFin])
            ->whereIn('solicitudes.estado', ['aprobada', 'entregada'])
            ->groupBy('insumos.id_insumo', 'insumos.nombre_insumo', 'tipo_insumos.nombre_tipo')
            ->orderByDesc('total_entregado')
            ->limit($limite)
            ->get();
    }

    /**
     * Obtiene estadísticas de consumo por departamento
     */
    public function getEstadisticasConsumo($fechaInicio, $fechaFin)
    {
        $consumoPorDepartamento = $this->getConsumoPorDepartamento($fechaInicio, $fechaFin);
        
        $totalConsumido = $consumoPorDepartamento->sum('total_entregado');
        $totalSolicitado = $consumoPorDepartamento->sum('total_solicitado');
        $totalSolicitudes = $consumoPorDepartamento->sum('total_solicitudes');
        $departamentosActivos = $consumoPorDepartamento->count();
        $totalDepartamentos = Departamento::count();

        $promedioConsumoPorDepartamento = $departamentosActivos > 0 
            ? round($totalConsumido / $departamentosActivos, 2) 
            : 0;

        $eficienciaEntrega = $totalSolicitado > 0 
            ? round(($totalConsumido / $totalSolicitado) * 100, 2) 
            : 0;

        return [
            'total_consumido' => $totalConsumido,
            'total_solicitado' => $totalSolicitado,
            'total_solicitudes' => $totalSolicitudes,
            'departamentos_activos' => $departamentosActivos,
            'total_departamentos' => $totalDepartamentos,
            'promedio_consumo_por_depto' => $promedioConsumoPorDepartamento,
            'eficiencia_entrega' => $eficienciaEntrega,
            'porcentaje_departamentos_activos' => $totalDepartamentos > 0 
                ? round(($departamentosActivos / $totalDepartamentos) * 100, 2) 
                : 0,
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

