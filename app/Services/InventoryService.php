<?php

namespace App\Services;

use App\Models\Producto;
use App\Models\Inventario;
use App\Models\Movimientos;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InventoryService
{
    /**
     * Ajustar stock de un producto
     */
    public function adjustStock(Producto $producto, int $cantidad, string $tipo, string $observaciones = null): bool
    {
        try {
            return DB::transaction(function () use ($producto, $cantidad, $tipo, $observaciones) {
                // Validar que se puede reducir stock si es salida
                if ($tipo === Movimientos::TIPO_SALIDA && !$producto->canReduceStock($cantidad)) {
                    throw new \Exception('No hay suficiente stock disponible');
                }

                // Actualizar stock
                if ($tipo === Movimientos::TIPO_ENTRADA) {
                    $producto->addStock($cantidad);
                } else {
                    $producto->reduceStock($cantidad);
                }

                // Registrar movimiento
                Movimientos::createMovimiento([
                    'id_movimiento' => uniqid('MOV_'),
                    'tipo_movimiento' => $tipo,
                    'cantidad' => $cantidad,
                    'fecha_movimiento' => now(),
                    'observaciones' => $observaciones ?? 'Ajuste manual de stock',
                    'id_producto' => $producto->id_producto,
                    'id_usuario' => auth()->id(),
                ]);

                Log::info('Stock ajustado', [
                    'producto_id' => $producto->id_producto,
                    'tipo' => $tipo,
                    'cantidad' => $cantidad,
                    'usuario_id' => auth()->id()
                ]);

                return true;
            });
        } catch (\Exception $e) {
            Log::error('Error ajustando stock', [
                'producto_id' => $producto->id_producto,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Aplicar inventario al stock
     */
    public function applyInventory(Inventario $inventario): bool
    {
        try {
            return DB::transaction(function () use ($inventario) {
                if (!$inventario->hasDiscrepancy()) {
                    return true; // No hay nada que aplicar
                }

                $producto = $inventario->producto;
                $stockAnterior = $producto->stock_actual;
                $producto->stock_actual = $inventario->cantidad_inventario;
                
                if ($producto->save()) {
                    // Registrar movimiento de ajuste
                    Movimientos::createMovimiento([
                        'id_movimiento' => uniqid('MOV_'),
                        'tipo_movimiento' => Movimientos::TIPO_AJUSTE,
                        'cantidad' => abs($inventario->diferencia_stock),
                        'fecha_movimiento' => now(),
                        'observaciones' => "Ajuste por inventario - ID: {$inventario->id_inventario}",
                        'id_producto' => $inventario->id_producto,
                        'id_usuario' => auth()->id(),
                    ]);

                    Log::info('Inventario aplicado', [
                        'inventario_id' => $inventario->id_inventario,
                        'producto_id' => $inventario->id_producto,
                        'stock_anterior' => $stockAnterior,
                        'stock_nuevo' => $producto->stock_actual,
                        'diferencia' => $inventario->diferencia_stock
                    ]);

                    return true;
                }

                return false;
            });
        } catch (\Exception $e) {
            Log::error('Error aplicando inventario', [
                'inventario_id' => $inventario->id_inventario,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Obtener productos con stock bajo
     */
    public function getLowStockProducts(): \Illuminate\Database\Eloquent\Collection
    {
        return Producto::lowStock()->with('unidad')->get();
    }

    /**
     * Obtener productos agotados
     */
    public function getOutOfStockProducts(): \Illuminate\Database\Eloquent\Collection
    {
        return Producto::outOfStock()->with('unidad')->get();
    }

    /**
     * Obtener estadísticas de inventario
     */
    public function getInventoryStats(): array
    {
        return [
            'total_productos' => Producto::count(),
            'productos_con_stock' => Producto::inStock()->count(),
            'productos_agotados' => Producto::outOfStock()->count(),
            'productos_stock_bajo' => Producto::lowStock()->count(),
            'valor_total_inventario' => Producto::sum('stock_actual'),
        ];
    }

    /**
     * Generar reporte de movimientos
     */
    public function generateMovementsReport(string $fechaInicio, string $fechaFin, string $tipoMovimiento = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = Movimientos::withRelations()
            ->byFechaRange($fechaInicio, $fechaFin);

        if ($tipoMovimiento) {
            $query->byTipo($tipoMovimiento);
        }

        return $query->get();
    }
}
