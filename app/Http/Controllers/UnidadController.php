<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUnidadRequest;
use App\Http\Requests\UpdateUnidadRequest;
use App\Models\Unidad;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UnidadController extends Controller
{
    public function create(): View
    {
        return view('layouts.unidad.unidad_create');
    }

    public function store(StoreUnidadRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        Unidad::create($validated);

        return redirect()->route('unidades')->with('status', 'Unidad creada correctamente.');
    }

    public function edit(Unidad $unidad): View
    {
        return view('layouts.unidad.unidad_update', compact('unidad'));
    }

    public function update(UpdateUnidadRequest $request, Unidad $unidad): RedirectResponse
    {
        $validated = $request->validated();
        $unidad->update($validated);

        return redirect()->route('unidades')->with('status', 'Unidad actualizada correctamente.');
    }

    public function destroy(Unidad $unidad): RedirectResponse
    {
        $unidad->delete();

        return redirect()->route('unidades')->with('status', 'Unidad eliminada correctamente.');
    }
}
