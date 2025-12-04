<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FacturaViewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        abort_if(!auth()->user()->can('mantenedor de facturas'), 403, 'No tienes permisos para acceder a esta página.');
        
        return view('layouts.factura.factura_index');
    }
}

