<?php

namespace App\Services;

use App\Models\Solicitud;
use App\Models\Detalle_Solicitud;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RequestService
{
    /**
     * Crear una nueva solicitud
     */
    public function createRequest(array $data, string $userId): ?Solicitud
    {
        try {
            return DB::transaction(function () use ($data, $userId) {
                // Crear solicitud
                $solicitud = Solicitud::create([
                    'id_solicitud' => uniqid('SOL_'),
                    'fecha_solicitud' => now(),
                    'estado_solicitud' => Solicitud::ESTADO_PENDIENTE,
                    'observaciones' => $data['observaciones'] ?? null,
                    'id_usuario' => $userId,
                ]);

                // Crear detalles de solicitud
                foreach ($data['productos'] as $productoData) {
                    $producto = Producto::find($productoData['id_producto']);
                    
                    if (!$producto->canReduceStock($productoData['cantidad'])) {
                        throw new \Exception("No hay suficiente stock para el producto: {$producto->nombre_producto}");
                    }

                    Detalle_Solicitud::create([
                        'id_detalle_solicitud' => uniqid('DET_'),
                        'id_solicitud' => $solicitud->id_solicitud,
                        'id_producto' => $productoData['id_producto'],
                        'cantidad_solicitud' => $productoData['cantidad'],
                    ]);
                }

                Log::info('Solicitud creada', [
                    'solicitud_id' => $solicitud->id_solicitud,
                    'usuario_id' => $userId,
                    'productos_count' => count($data['productos'])
                ]);

                return $solicitud;
            });
        } catch (\Exception $e) {
            Log::error('Error creando solicitud', [
                'usuario_id' => $userId,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Aprobar una solicitud
     */
    public function approveRequest(Solicitud $solicitud): bool
    {
        try {
            return DB::transaction(function () use ($solicitud) {
                if (!$solicitud->canBeApproved()) {
                    throw new \Exception('La solicitud no puede ser aprobada en su estado actual');
                }

                // Verificar stock disponible
                foreach ($solicitud->detalleSolicitudes as $detalle) {
                    if (!$detalle->canBeFulfilled()) {
                        throw new \Exception("No hay suficiente stock para el producto: {$detalle->producto->nombre_producto}");
                    }
                }

                // Aprobar solicitud
                $solicitud->approve();

                // Reducir stock
                foreach ($solicitud->detalleSolicitudes as $detalle) {
                    $detalle->fulfill();
                }

                Log::info('Solicitud aprobada', [
                    'solicitud_id' => $solicitud->id_solicitud,
                    'usuario_id' => auth()->id()
                ]);

                return true;
            });
        } catch (\Exception $e) {
            Log::error('Error aprobando solicitud', [
                'solicitud_id' => $solicitud->id_solicitud,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Rechazar una solicitud
     */
    public function rejectRequest(Solicitud $solicitud): bool
    {
        try {
            if (!$solicitud->canBeRejected()) {
                throw new \Exception('La solicitud no puede ser rechazada en su estado actual');
            }

            $solicitud->reject();

            Log::info('Solicitud rechazada', [
                'solicitud_id' => $solicitud->id_solicitud,
                'usuario_id' => auth()->id()
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Error rechazando solicitud', [
                'solicitud_id' => $solicitud->id_solicitud,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Entregar una solicitud
     */
    public function deliverRequest(Solicitud $solicitud): bool
    {
        try {
            if (!$solicitud->canBeDelivered()) {
                throw new \Exception('La solicitud no puede ser entregada en su estado actual');
            }

            $solicitud->deliver();

            Log::info('Solicitud entregada', [
                'solicitud_id' => $solicitud->id_solicitud,
                'usuario_id' => auth()->id()
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Error entregando solicitud', [
                'solicitud_id' => $solicitud->id_solicitud,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Obtener estadísticas de solicitudes
     */
    public function getRequestStats(): array
    {
        return [
            'total_solicitudes' => Solicitud::count(),
            'solicitudes_pendientes' => Solicitud::pendientes()->count(),
            'solicitudes_aprobadas' => Solicitud::aprobadas()->count(),
            'solicitudes_rechazadas' => Solicitud::rechazadas()->count(),
            'solicitudes_entregadas' => Solicitud::entregadas()->count(),
        ];
    }

    /**
     * Obtener solicitudes por usuario
     */
    public function getRequestsByUser(string $userId): \Illuminate\Database\Eloquent\Collection
    {
        return Solicitud::getByUsuario($userId);
    }

    /**
     * Obtener solicitudes pendientes
     */
    public function getPendingRequests(): \Illuminate\Database\Eloquent\Collection
    {
        return Solicitud::getPendientes();
    }
}
