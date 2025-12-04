<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UnidadViewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        abort_if(!auth()->user()->can('mantenedor de unidades'), 403, 'No tienes permisos para acceder a esta página.');
        
        return view('layouts.unidad.unidad_index');
    }
}

