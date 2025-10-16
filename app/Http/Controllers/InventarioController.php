<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\Producto;
use App\Models\Movimientos;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class InventarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:manage-inventory');
    }

    public function index(): View
    {
        $this->authorizeAction('view-inventory');
        
        $inventarios = Inventario::withProducto()
            ->orderByFecha()
            ->paginate(20);
            
        $productos = Producto::with('unidad')
            ->orderBy('nombre_producto')
            ->get();
        
        return view('layouts.inventario.inventario_index', compact('inventarios', 'productos'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAction('create-inventory');
        
        try {
            $this->logAction('Creando registro de inventario', [
                'producto_id' => $request->id_producto
            ]);

            $validated = $request->validate([
                'id_inventario' => ['required', 'string', 'max:255', 'unique:inventarios,id_inventario'],
                'id_producto' => ['required', 'string', 'exists:productos,id_producto'],
                'fecha_inventario' => ['required', 'date'],
                'cantidad_inventario' => ['required', 'integer', 'min:0'],
            ]);

            return $this->executeInTransaction(function () use ($validated) {
                $inventario = Inventario::create($validated);
                
                $this->logAction('Registro de inventario creado', [
                    'inventario_id' => $inventario->id_inventario,
                    'producto_id' => $inventario->id_producto
                ]);
                
                return redirect()->back()->with('status', 'Registro de inventario creado exitosamente.');
            });

        } catch (\Throwable $e) {
            return $this->handleException($e, 'InventarioController@store', [
                'producto_id' => $request->id_producto
            ]);
        }
    }

    public function update(Request $request, Inventario $inventario): RedirectResponse
    {
        $this->authorize('update', $inventario);
        
        try {
            $this->logAction('Actualizando inventario', [
                'inventario_id' => $inventario->id_inventario,
                'producto_id' => $inventario->id_producto
            ]);

            $validated = $request->validate([
                'cantidad_inventario' => ['required', 'integer', 'min:0'],
                'fecha_inventario' => ['required', 'date'],
            ]);

            $inventario->update($validated);
            
            $this->logAction('Inventario actualizado exitosamente', [
                'inventario_id' => $inventario->id_inventario
            ]);
            
            return redirect()->back()->with('status', 'Inventario actualizado exitosamente.');
            
        } catch (\Throwable $e) {
            return $this->handleException($e, 'InventarioController@update', [
                'inventario_id' => $inventario->id_inventario
            ]);
        }
    }

    public function destroy(Inventario $inventario): RedirectResponse
    {
        $this->authorizeAction('delete-inventory');
        
        try {
            $this->logAction('Eliminando inventario', [
                'inventario_id' => $inventario->id_inventario,
                'producto_id' => $inventario->id_producto
            ]);
            
            $inventario->delete();
            
            $this->logAction('Inventario eliminado exitosamente', [
                'inventario_id' => $inventario->id_inventario
            ]);
            
            return redirect()->back()->with('status', 'Inventario eliminado exitosamente.');
            
        } catch (\Throwable $e) {
            return $this->handleException($e, 'InventarioController@destroy', [
                'inventario_id' => $inventario->id_inventario
            ]);
        }
    }

    /**
     * Aplicar inventario al stock del producto
     */
    public function apply(Inventario $inventario): RedirectResponse
    {
        $this->authorizeAction('apply-inventory');
        
        try {
            $this->logAction('Aplicando inventario al stock', [
                'inventario_id' => $inventario->id_inventario,
                'producto_id' => $inventario->id_producto
            ]);

            return $this->executeInTransaction(function () use ($inventario) {
                if ($inventario->applyToProduct()) {
                    $this->logAction('Inventario aplicado exitosamente', [
                        'inventario_id' => $inventario->id_inventario,
                        'diferencia' => $inventario->diferencia_stock
                    ]);
                    
                    return redirect()->back()->with('status', 'Inventario aplicado al stock correctamente.');
                } else {
                    return redirect()->back()->withErrors([
                        'error' => 'No se pudo aplicar el inventario al stock.'
                    ]);
                }
            });

        } catch (\Throwable $e) {
            return $this->handleException($e, 'InventarioController@apply', [
                'inventario_id' => $inventario->id_inventario
            ]);
        }
    }

    /**
     * Obtener discrepancias de inventario
     */
    public function discrepancies(): View
    {
        $this->authorizeAction('view-inventory-discrepancies');
        
        $discrepancies = Inventario::getWithDiscrepancy();
        
        return view('layouts.inventario.discrepancies', compact('discrepancies'));
    }

    /**
     * Aplicar todas las discrepancias
     */
    public function applyAllDiscrepancies(): RedirectResponse
    {
        $this->authorizeAction('apply-all-inventory');
        
        try {
            $this->logAction('Aplicando todas las discrepancias de inventario');

            return $this->executeInTransaction(function () {
                $discrepancies = Inventario::getWithDiscrepancy();
                $applied = 0;
                $failed = 0;

                foreach ($discrepancies as $inventario) {
                    if ($inventario->applyToProduct()) {
                        $applied++;
                    } else {
                        $failed++;
                    }
                }

                $this->logAction('Discrepancias aplicadas', [
                    'aplicadas' => $applied,
                    'fallidas' => $failed
                ]);

                $message = "Se aplicaron {$applied} discrepancias correctamente.";
                if ($failed > 0) {
                    $message .= " {$failed} discrepancias no se pudieron aplicar.";
                }

                return redirect()->back()->with('status', $message);
            });

        } catch (\Throwable $e) {
            return $this->handleException($e, 'InventarioController@applyAllDiscrepancies');
        }
    }
}