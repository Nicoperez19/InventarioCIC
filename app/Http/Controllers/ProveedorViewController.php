<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProveedorViewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        abort_if(!auth()->user()->can('mantenedor de proveedores'), 403, 'No tienes permisos para acceder a esta página.');
        
        return view('layouts.proveedor.proveedor_index');
    }
}

