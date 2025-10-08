<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DepartamentoController extends Controller
{
    public function create(): View
    {
        return view('layouts.departamento.departamento_create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id_depto' => ['required', 'string', 'max:255', 'unique:departamentos,id_depto'],
            'nombre_depto' => ['required', 'string', 'max:255'],
        ]);

        Departamento::create($validated);

        return redirect()->route('departamentos')->with('status', 'Departamento creado correctamente.');
    }

    public function edit(Departamento $departamento): View
    {
        return view('layouts.departamento.departamento_update', compact('departamento'));
    }

    public function update(Request $request, Departamento $departamento): RedirectResponse
    {
        $validated = $request->validate([
            'nombre_depto' => ['required', 'string', 'max:255'],
        ]);

        $departamento->update($validated);

        return redirect()->route('departamentos')->with('status', 'Departamento actualizado correctamente.');
    }

    public function destroy(Departamento $departamento): RedirectResponse
    {
        $departamento->delete();
        return redirect()->route('departamentos')->with('status', 'Departamento eliminado correctamente.');
    }
}

