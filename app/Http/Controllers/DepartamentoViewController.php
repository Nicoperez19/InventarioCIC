<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DepartamentoViewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        abort_if(!auth()->user()->can('mantenedor de departamentos'), 403, 'No tienes permisos para acceder a esta página.');
        
        return view('layouts.departamento.departamento_index');
    }
}

