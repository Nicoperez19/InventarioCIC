<?php

namespace App\Http\Controllers;

use App\Models\Unidad;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UnidadController extends Controller
{
    public function create(): View
    {
        return view('layouts.unidad.unidad_create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id_unidad' => ['required', 'string', 'max:255', 'unique:unidads,id_unidad'],
            'nombre_unidad' => ['required', 'string', 'max:255'],
        ]);

        Unidad::create($validated);
        return redirect()->route('unidades')->with('status', 'Unidad creada correctamente.');
    }

    public function edit(Unidad $unidad): View
    {
        return view('layouts.unidad.unidad_update', compact('unidad'));
    }

    public function update(Request $request, Unidad $unidad): RedirectResponse
    {
        $validated = $request->validate(['nombre_unidad' => ['required', 'string', 'max:255']]);
        $unidad->update($validated);
        return redirect()->route('unidades')->with('status', 'Unidad actualizada correctamente.');
    }

    public function destroy(Unidad $unidad): RedirectResponse
    {
        $unidad->delete();
        return redirect()->route('unidades')->with('status', 'Unidad eliminada correctamente.');
    }
}

