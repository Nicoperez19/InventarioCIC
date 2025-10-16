<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Unidad;
use App\Models\Movimientos;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class ProductoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:manage-inventory');
    }

    public function create(): View
    {
        $this->authorizeAction('create-products');
        
        $unidades = Unidad::orderByName()->get();
        
        return view('layouts.producto.producto_create', compact('unidades'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAction('create-products');
        
        try {
            $this->logAction('Creando producto', ['nombre' => $request->nombre_producto]);

            $validated = $request->validate([
                'id_producto' => ['required', 'string', 'max:255', 'unique:productos,id_producto'],
                'nombre_producto' => ['required', 'string', 'max:255'],
                'stock_minimo' => ['required', 'integer', 'min:0'],
                'stock_actual' => ['required', 'integer', 'min:0'],
                'observaciones' => ['nullable', 'string', 'max:1000'],
                'id_unidad' => ['required', 'string', 'exists:unidads,id_unidad'],
            ]);

            return $this->executeInTransaction(function () use ($validated, $request) {
                $producto = Producto::create($validated);
                
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
                    'nombre' => $producto->nombre_producto
                ]);
                
                return redirect()->route('productos')->with('status', 'Producto creado correctamente.');
            });

        } catch (\Throwable $e) {
            return $this->handleException($e, 'ProductoController@store', [
                'nombre' => $request->nombre_producto
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

    public function update(Request $request, Producto $producto): RedirectResponse
    {
        $this->authorize('update', $producto);
        
        try {
            $this->logAction('Actualizando producto', [
                'producto_id' => $producto->id_producto,
                'nombre' => $producto->nombre_producto
            ]);

            $validated = $request->validate([
                'nombre_producto' => ['required', 'string', 'max:255'],
                'stock_minimo' => ['required', 'integer', 'min:0'],
                'stock_actual' => ['required', 'integer', 'min:0'],
                'observaciones' => ['nullable', 'string', 'max:1000'],
                'id_unidad' => ['required', 'string', 'exists:unidads,id_unidad'],
            ]);

            return $this->executeInTransaction(function () use ($validated, $request, $producto) {
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
                    'nombre' => $producto->nombre_producto
                ]);
                
                return redirect()->route('productos')->with('status', 'Producto actualizado correctamente.');
            });

        } catch (\Throwable $e) {
            return $this->handleException($e, 'ProductoController@update', [
                'producto_id' => $producto->id_producto
            ]);
        }
    }

    public function destroy(Producto $producto): RedirectResponse
    {
        $this->authorizeAction('delete-products');
        
        try {
            $this->logAction('Eliminando producto', [
                'producto_id' => $producto->id_producto,
                'nombre' => $producto->nombre_producto
            ]);
            
            // Verificar si tiene movimientos o solicitudes
            if ($producto->movimientos()->exists() || $producto->detalleSolicitudes()->exists()) {
                return redirect()->back()->withErrors([
                    'error' => 'No se puede eliminar el producto porque tiene movimientos o solicitudes asociadas.'
                ]);
            }
            
            $producto->delete();
            
            $this->logAction('Producto eliminado exitosamente', [
                'producto_id' => $producto->id_producto,
                'nombre' => $producto->nombre_producto
            ]);
            
            return redirect()->route('productos')->with('status', 'Producto eliminado correctamente.');
            
        } catch (\Throwable $e) {
            return $this->handleException($e, 'ProductoController@destroy', [
                'producto_id' => $producto->id_producto
            ]);
        }
    }

    /**
     * Ajustar stock del producto
     */
    public function adjustStock(Request $request, Producto $producto): RedirectResponse
    {
        $this->authorize('update', $producto);
        
        try {
            $validated = $request->validate([
                'cantidad' => ['required', 'integer'],
                'tipo_movimiento' => ['required', 'string', 'in:entrada,salida'],
                'observaciones' => ['nullable', 'string', 'max:500']
            ]);

            return $this->executeInTransaction(function () use ($validated, $producto) {
                $cantidad = $validated['cantidad'];
                $tipo = $validated['tipo_movimiento'];
                
                if ($tipo === 'salida' && !$producto->canReduceStock($cantidad)) {
                    return redirect()->back()->withErrors([
                        'cantidad' => 'No hay suficiente stock disponible.'
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
                    'cantidad' => $cantidad
                ]);
                
                return redirect()->back()->with('status', 'Stock ajustado correctamente.');
            });

        } catch (\Throwable $e) {
            return $this->handleException($e, 'ProductoController@adjustStock', [
                'producto_id' => $producto->id_producto
            ]);
        }
    }
}

