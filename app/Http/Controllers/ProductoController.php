<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdjustStockRequest;
use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use App\Models\Movimientos;
use App\Models\Producto;
use App\Models\Unidad;
use App\Services\BarcodeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(): View
    {
        $this->authorizeAction('create-products');

        $unidades = Unidad::orderByName()->get();

        return view('layouts.producto.producto_create', compact('unidades'));
    }

    public function store(StoreProductoRequest $request): RedirectResponse
    {
        $this->authorizeAction('create-products');

        try {
            $this->logAction('Creando producto', ['nombre' => $request->nombre_producto]);

            $validated = $request->validated();

            return $this->executeInTransaction(function () use ($validated) {
                // Generar código de barras si no se proporciona uno
                if (empty($validated['codigo_barra'])) {
                    $barcodeService = new BarcodeService();
                    $validated['codigo_barra'] = $barcodeService->generateUniqueBarcode($validated['id_unidad']);
                }

                $producto = Producto::create($validated);

                // Generar imagen del código de barras
                if ($producto->codigo_barra) {
                    try {
                        $barcodeService = new BarcodeService();
                        $barcodeService->generateBarcodeImage($producto->codigo_barra);
                    } catch (\Throwable $e) {
                        \Log::warning('Error generando código de barras para producto', [
                            'producto_id' => $producto->id_producto,
                            'error' => $e->getMessage()
                        ]);
                    }
                }

                // Registrar movimiento inicial si hay stock
                if ($producto->stock_actual > 0) {
                    Movimientos::createMovimiento([
                        'id_movimiento' => uniqid('MOV_'),
                        'tipo_movimiento' => Movimientos::TIPO_ENTRADA,
                        'cantidad' => $producto->stock_actual,
                        'fecha_movimiento' => now(),
                        'observaciones' => 'Stock inicial del producto',
                        'id_producto' => $producto->id_producto,
                        'id_usuario' => auth()->id(),
                    ]);
                }

                $this->logAction('Producto creado exitosamente', [
                    'producto_id' => $producto->id_producto,
                    'nombre' => $producto->nombre_producto,
                ]);

                return redirect()->route('productos')->with('status', 'Producto creado correctamente.');
            });

        } catch (\Throwable $e) {
            return $this->handleException($e, 'ProductoController@store', [
                'nombre' => $request->nombre_producto,
            ]);
        }
    }

    public function edit(Producto $producto): View
    {
        $this->authorize('update', $producto);

        $producto->load('unidad');
        $unidades = Unidad::orderByName()->get();

        return view('layouts.producto.producto_update', compact('producto', 'unidades'));
    }

    public function update(UpdateProductoRequest $request, Producto $producto): RedirectResponse
    {
        $this->authorize('update', $producto);

        try {
            $this->logAction('Actualizando producto', [
                'producto_id' => $producto->id_producto,
                'nombre' => $producto->nombre_producto,
            ]);

            $validated = $request->validated();

            return $this->executeInTransaction(function () use ($validated, $producto) {
                $stockAnterior = $producto->stock_actual;
                $producto->update($validated);

                // Registrar movimiento si cambió el stock
                $diferenciaStock = $producto->stock_actual - $stockAnterior;
                if ($diferenciaStock !== 0) {
                    Movimientos::createMovimiento([
                        'id_movimiento' => uniqid('MOV_'),
                        'tipo_movimiento' => $diferenciaStock > 0 ? Movimientos::TIPO_ENTRADA : Movimientos::TIPO_SALIDA,
                        'cantidad' => abs($diferenciaStock),
                        'fecha_movimiento' => now(),
                        'observaciones' => 'Ajuste manual de stock',
                        'id_producto' => $producto->id_producto,
                        'id_usuario' => auth()->id(),
                    ]);
                }

                $this->logAction('Producto actualizado exitosamente', [
                    'producto_id' => $producto->id_producto,
                    'nombre' => $producto->nombre_producto,
                ]);

                return redirect()->route('productos')->with('status', 'Producto actualizado correctamente.');
            });

        } catch (\Throwable $e) {
            return $this->handleException($e, 'ProductoController@update', [
                'producto_id' => $producto->id_producto,
            ]);
        }
    }

    public function destroy(Producto $producto): RedirectResponse
    {
        $this->authorizeAction('delete-products');

        try {
            $this->logAction('Eliminando producto', [
                'producto_id' => $producto->id_producto,
                'nombre' => $producto->nombre_producto,
            ]);

            // Verificar si tiene movimientos o solicitudes
            if ($producto->movimientos()->exists() || $producto->detalleSolicitudes()->exists()) {
                return redirect()->back()->withErrors([
                    'error' => 'No se puede eliminar el producto porque tiene movimientos o solicitudes asociadas.',
                ]);
            }

            $producto->delete();

            $this->logAction('Producto eliminado exitosamente', [
                'producto_id' => $producto->id_producto,
                'nombre' => $producto->nombre_producto,
            ]);

            return redirect()->route('productos')->with('status', 'Producto eliminado correctamente.');

        } catch (\Throwable $e) {
            return $this->handleException($e, 'ProductoController@destroy', [
                'producto_id' => $producto->id_producto,
            ]);
        }
    }

    /**
     * Ajustar stock del producto
     */
    public function adjustStock(AdjustStockRequest $request, Producto $producto): RedirectResponse
    {
        $this->authorize('update', $producto);

        try {
            $validated = $request->validated();

            return $this->executeInTransaction(function () use ($validated, $producto) {
                $cantidad = $validated['cantidad'];
                $tipo = $validated['tipo_movimiento'];

                if ($tipo === 'salida' && ! $producto->canReduceStock($cantidad)) {
                    return redirect()->back()->withErrors([
                        'cantidad' => 'No hay suficiente stock disponible.',
                    ]);
                }

                // Actualizar stock
                if ($tipo === 'entrada') {
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
                    'observaciones' => $validated['observaciones'] ?? 'Ajuste manual de stock',
                    'id_producto' => $producto->id_producto,
                    'id_usuario' => auth()->id(),
                ]);

                $this->logAction('Stock ajustado', [
                    'producto_id' => $producto->id_producto,
                    'tipo' => $tipo,
                    'cantidad' => $cantidad,
                ]);

                return redirect()->back()->with('status', 'Stock ajustado correctamente.');
            });

        } catch (\Throwable $e) {
            return $this->handleException($e, 'ProductoController@adjustStock', [
                'producto_id' => $producto->id_producto,
            ]);
        }
    }
}
