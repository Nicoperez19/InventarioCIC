<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminSolicitudViewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        abort_if(!auth()->user()->can('admin solicitudes'), 403, 'No tienes permisos para acceder a esta página.');
        
        return view('layouts.admin_solicitudes.admin_solicitudes_index');
    }
}

