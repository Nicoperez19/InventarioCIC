<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TipoInsumoViewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        abort_if(!auth()->user()->can('mantenedor de tipos de insumo'), 403, 'No tienes permisos para acceder a esta página.');
        
        return view('layouts.tipo_insumo.tipo_insumo_index');
    }
}

