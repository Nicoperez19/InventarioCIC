<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InsumoViewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        abort_if(!auth()->user()->can('insumos'), 403, 'No tienes permisos para acceder a esta página.');
        
        return view('layouts.insumo.insumo_index');
    }
}

