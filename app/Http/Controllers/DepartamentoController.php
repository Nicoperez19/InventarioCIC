<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDepartamentoRequest;
use App\Http\Requests\UpdateDepartamentoRequest;
use App\Models\Departamento;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DepartamentoController extends Controller
{
    public function create(): View
    {
        return view('layouts.departamento.departamento_create');
    }

    public function store(StoreDepartamentoRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        Departamento::create($validated);

        return redirect()->route('departamentos')->with('status', 'Departamento creado correctamente.');
    }

    public function edit(Departamento $departamento): View
    {
        return view('layouts.departamento.departamento_update', compact('departamento'));
    }

    public function update(UpdateDepartamentoRequest $request, Departamento $departamento): RedirectResponse
    {
        $validated = $request->validated();
        $departamento->update($validated);

        return redirect()->route('departamentos')->with('status', 'Departamento actualizado correctamente.');
    }

    public function destroy(Departamento $departamento): RedirectResponse
    {
        $departamento->delete();

        return redirect()->route('departamentos')->with('status', 'Departamento eliminado correctamente.');
    }
}
