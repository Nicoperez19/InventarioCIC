<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class InventarioController extends Controller
{
    public function index(): View
    {
        return view('layouts.inventario.inventario_index');
    }

    public function update(Request $request, Inventario $inventario): RedirectResponse
    {
        $request->validate(['cantidad' => 'required|integer|min:0']);
        $inventario->update(['cantidad' => $request->cantidad]);
        return back()->with('status', 'Inventario actualizado exitosamente.');
    }
}