<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\SolicitudItem;
use App\Models\Insumo;
use App\Models\Factura;
use App\Models\Proveedor;
use App\Models\Departamento;
use App\Models\User;
use App\Models\TipoInsumo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = $this->getDashboardStats();
        
        return view('layouts.dashboard.dashboard', compact('stats'));
    }

    private function getDashboardStats()
    {
        // ========== ESTADÍSTICAS DE INVENTARIO ==========
        $totalInsumos = Insumo::count();
        $insumosStockCritico = Insumo::where(function($query) {
            $query->where(function($q) {
                $q->whereColumn('stock_actual', '<=', 'stock_minimo')
                  ->where('stock_minimo', '>', 0);
            })
            ->orWhere(function($q) {
                $q->where('stock_actual', '<=', 0);
            });
        })->count();
        
        $insumosStockAgotado = Insumo::where('stock_actual', '<=', 0)->count();
        $insumosStockBajo = Insumo::where('stock_actual', '>', 0)
            ->whereColumn('stock_actual', '<=', 'stock_minimo')
            ->where('stock_minimo', '>', 0)
            ->count();
        
        $insumosStockNormal = Insumo::where(function($query) {
            $query->whereColumn('stock_actual', '>', 'stock_minimo')
                  ->where('stock_minimo', '>', 0);
        })->orWhere(function($query) {
            $query->where('stock_minimo', '<=', 0)
                  ->where('stock_actual', '>', 0);
        })->count();
        
        $totalStockActual = Insumo::sum('stock_actual') ?? 0;
        $totalStockMinimo = Insumo::sum('stock_minimo') ?? 0;
        
        // Insumos más solicitados (últimos 30 días) - Simplificado para no usarse en la vista actual
        $insumosMasSolicitados = collect([]);

        // ========== ESTADÍSTICAS DE SOLICITUDES ==========
        $totalSolicitudes = Solicitud::count();
        $solicitudesPendientes = Solicitud::where('estado', 'pendiente')->count();
        $solicitudesAprobadas = Solicitud::where('estado', 'aprobada')->count();
        $solicitudesEntregadas = Solicitud::where('estado', 'entregada')->count();
        $solicitudesRechazadas = Solicitud::where('estado', 'rechazada')->count();
        
        // Cantidades de items
        $totalCantidadSolicitada = SolicitudItem::sum('cantidad_solicitada') ?? 0;
        $totalCantidadAprobada = SolicitudItem::sum('cantidad_aprobada') ?? 0;
        $totalCantidadEntregada = SolicitudItem::sum('cantidad_entregada') ?? 0;
        
        // Solicitudes del mes
        $solicitudesMesActual = Solicitud::whereMonth('fecha_solicitud', now()->month)
            ->whereYear('fecha_solicitud', now()->year)
            ->count();
        
        $solicitudesEntregadasMes = Solicitud::where('estado', 'entregada')
            ->whereMonth('fecha_entrega', now()->month)
            ->whereYear('fecha_entrega', now()->year)
            ->count();
        
        // Tipos de solicitudes
        $solicitudesIndividuales = Solicitud::where('tipo_solicitud', 'individual')->count();
        $solicitudesMasivas = Solicitud::where('tipo_solicitud', 'masiva')->count();
        
        // Cantidades del mes
        $cantidadSolicitadaMes = SolicitudItem::join('solicitudes', 'solicitud_items.solicitud_id', '=', 'solicitudes.id')
            ->whereMonth('solicitudes.fecha_solicitud', now()->month)
            ->whereYear('solicitudes.fecha_solicitud', now()->year)
            ->sum('cantidad_solicitada') ?? 0;
        
        $cantidadEntregadaMes = SolicitudItem::join('solicitudes', 'solicitud_items.solicitud_id', '=', 'solicitudes.id')
            ->where('solicitudes.estado', 'entregada')
            ->whereMonth('solicitudes.fecha_entrega', now()->month)
            ->whereYear('solicitudes.fecha_entrega', now()->year)
            ->sum('cantidad_entregada') ?? 0;

        // ========== ESTADÍSTICAS DE ADMINISTRACIÓN DE SOLICITUDES ==========
        // Solicitudes pendientes de aprobación (con tiempo de espera)
        $solicitudesPendientesConTiempo = Solicitud::with(['user', 'departamento', 'items'])
            ->where('estado', 'pendiente')
            ->orderBy('fecha_solicitud', 'asc')
            ->get()
            ->map(function($solicitud) {
                $solicitud->tiempo_espera = $solicitud->fecha_solicitud->diffInDays(now());
                $solicitud->total_items = $solicitud->items->count();
                $solicitud->total_cantidad = $solicitud->items->sum('cantidad_solicitada');
                return $solicitud;
            })
            ->sortByDesc('tiempo_espera')
            ->take(10);
        
        // Solicitudes aprobadas pendientes de entrega
        $solicitudesAprobadasPendientes = Solicitud::with(['user', 'departamento', 'items'])
            ->where('estado', 'aprobada')
            ->orderBy('fecha_aprobacion', 'asc')
            ->get()
            ->map(function($solicitud) {
                $solicitud->tiempo_espera = $solicitud->fecha_aprobacion->diffInDays(now());
                $solicitud->total_items = $solicitud->items->count();
                $solicitud->total_cantidad = $solicitud->items->sum('cantidad_aprobada');
                return $solicitud;
            })
            ->sortByDesc('tiempo_espera')
            ->take(10);
        
        // Tiempo promedio de aprobación (días)
        $tiempoPromedioAprobacion = Solicitud::whereNotNull('fecha_aprobacion')
            ->whereNotNull('fecha_solicitud')
            ->get()
            ->map(function($s) {
                return $s->fecha_solicitud->diffInDays($s->fecha_aprobacion);
            })
            ->avg();
        
        // Tiempo promedio de entrega (días desde aprobación)
        $tiempoPromedioEntrega = Solicitud::whereNotNull('fecha_entrega')
            ->whereNotNull('fecha_aprobacion')
            ->get()
            ->map(function($s) {
                return $s->fecha_aprobacion->diffInDays($s->fecha_entrega);
            })
            ->avg();

        // ========== DATOS RECIENTES ==========
        // Insumos con stock crítico
        $insumosCriticos = Insumo::with(['tipoInsumo', 'unidadMedida'])
            ->where(function($query) {
                $query->where(function($q) {
                    $q->whereColumn('stock_actual', '<=', 'stock_minimo')
                      ->where('stock_minimo', '>', 0);
                })
                ->orWhere(function($q) {
                    $q->where('stock_actual', '<=', 0);
                });
            })
            ->orderBy('stock_actual', 'asc')
            ->limit(15)
            ->get();

        // Solicitudes recientes
        $solicitudesRecientes = Solicitud::with(['user', 'departamento', 'items'])
            ->orderBy('fecha_solicitud', 'desc')
            ->limit(10)
            ->get();

        // Top departamentos
        $topDepartamentos = Departamento::withCount('solicitudes')
            ->orderBy('solicitudes_count', 'desc')
            ->limit(5)
            ->get();

        // Top tipos de insumo más solicitados
        $topTiposInsumo = DB::table('tipo_insumos')
            ->select('tipo_insumos.id', 'tipo_insumos.nombre_tipo', DB::raw('COUNT(DISTINCT solicitud_items.insumo_id) as insumos_count'))
            ->join('insumos', 'tipo_insumos.id', '=', 'insumos.tipo_insumo_id')
            ->join('solicitud_items', 'insumos.id_insumo', '=', 'solicitud_items.insumo_id')
            ->join('solicitudes', 'solicitud_items.solicitud_id', '=', 'solicitudes.id')
            ->whereIn('solicitudes.estado', ['aprobada', 'entregada'])
            ->where('solicitudes.fecha_solicitud', '>=', now()->subDays(30))
            ->groupBy('tipo_insumos.id', 'tipo_insumos.nombre_tipo')
            ->orderBy('insumos_count', 'desc')
            ->limit(5)
            ->get();

        return [
            'inventario' => [
                'total_insumos' => $totalInsumos,
                'stock_critico' => $insumosStockCritico,
                'stock_agotado' => $insumosStockAgotado,
                'stock_bajo' => $insumosStockBajo,
                'stock_normal' => $insumosStockNormal,
                'total_stock_actual' => $totalStockActual,
                'total_stock_minimo' => $totalStockMinimo,
                'insumos_mas_solicitados' => $insumosMasSolicitados,
            ],
            'solicitudes' => [
                'total' => $totalSolicitudes,
                'pendientes' => $solicitudesPendientes,
                'aprobadas' => $solicitudesAprobadas,
                'entregadas' => $solicitudesEntregadas,
                'rechazadas' => $solicitudesRechazadas,
                'individuales' => $solicitudesIndividuales,
                'masivas' => $solicitudesMasivas,
                'mes_actual' => $solicitudesMesActual,
                'entregadas_mes' => $solicitudesEntregadasMes,
                'total_cantidad_solicitada' => $totalCantidadSolicitada,
                'total_cantidad_aprobada' => $totalCantidadAprobada,
                'total_cantidad_entregada' => $totalCantidadEntregada,
                'cantidad_solicitada_mes' => $cantidadSolicitadaMes,
                'cantidad_entregada_mes' => $cantidadEntregadaMes,
            ],
            'administracion' => [
                'pendientes_aprobacion' => $solicitudesPendientesConTiempo,
                'aprobadas_pendientes_entrega' => $solicitudesAprobadasPendientes,
                'tiempo_promedio_aprobacion' => round($tiempoPromedioAprobacion ?? 0, 1),
                'tiempo_promedio_entrega' => round($tiempoPromedioEntrega ?? 0, 1),
            ],
            'recientes' => [
                'solicitudes' => $solicitudesRecientes,
                'insumos_criticos' => $insumosCriticos,
            ],
            'top' => [
                'departamentos' => $topDepartamentos,
                'tipos_insumo' => $topTiposInsumo,
            ],
        ];
    }
}

