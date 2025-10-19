<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CargaMasivaController extends Controller
{
    public function index(): View
    {
        return view('layouts.carga_masiva.carga_masiva_index');
    }

    public function upload(Request $request): RedirectResponse
    {
        return back()->with('status', 'Archivo recibido. Procesamiento pendiente de implementación.');
    }
}


