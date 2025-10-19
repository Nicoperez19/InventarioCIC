<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Unidad;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductoController extends Controller
{
    public function create(): View
    {
        return view('layouts.producto.producto_create', ['unidades' => Unidad::orderBy('nombre_unidad')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id_producto' => ['required', 'string', 'max:255', 'unique:productos,id_producto'],
            'codigo_producto' => ['required', 'string', 'max:255', 'unique:productos,codigo_producto'],
            'nombre_producto' => ['required', 'string', 'max:255'],
            'stock_minimo' => ['required', 'integer', 'min:0'],
            'stock_actual' => ['required', 'integer', 'min:0'],
            'observaciones' => ['nullable', 'string'],
            'id_unidad' => ['required', 'string', 'exists:unidads,id_unidad'],
        ]);

        Producto::create($validated);
        return redirect()->route('productos')->with('status', 'Producto creado correctamente.');
    }

    public function edit(Producto $producto): View
    {
        return view('layouts.producto.producto_update', ['producto' => $producto, 'unidades' => Unidad::orderBy('nombre_unidad')->get()]);
    }

    public function update(Request $request, Producto $producto): RedirectResponse
    {
        $validated = $request->validate([
            'codigo_producto' => ['required', 'string', 'max:255', 'unique:productos,codigo_producto,' . $producto->id_producto . ',id_producto'],
            'nombre_producto' => ['required', 'string', 'max:255'],
            'stock_minimo' => ['required', 'integer', 'min:0'],
            'stock_actual' => ['required', 'integer', 'min:0'],
            'observaciones' => ['nullable', 'string'],
            'id_unidad' => ['required', 'string', 'exists:unidads,id_unidad'],
        ]);

        $producto->update($validated);
        return redirect()->route('productos')->with('status', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto): RedirectResponse
    {
        $producto->delete();
        return redirect()->route('productos')->with('status', 'Producto eliminado correctamente.');
    }
}

