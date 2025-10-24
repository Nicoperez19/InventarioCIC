<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FacturaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $facturas = Factura::where('run', Auth::user()->run)
            ->with('proveedor')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('facturas.index', compact('facturas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $proveedores = \App\Models\Proveedor::orderBy('nombre_proveedor')->get();
        return view('facturas.create', compact('proveedores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'numero_factura' => 'required|string|max:255|unique:facturas,numero_factura',
            'proveedor_id' => 'required|exists:proveedores,id',
            'monto_total' => 'required|numeric|min:0',
            'fecha_factura' => 'required|date',
            'archivo' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240', // 10MB max
            'observaciones' => 'nullable|string|max:1000'
        ]);

        $factura = new Factura();
        $factura->numero_factura = $request->numero_factura;
        $factura->proveedor_id = $request->proveedor_id;
        $factura->monto_total = $request->monto_total;
        $factura->fecha_factura = $request->fecha_factura;
        $factura->observaciones = $request->observaciones;
        $factura->run = Auth::user()->run;

        // Manejar archivo si se sube
        if ($request->hasFile('archivo')) {
            $archivo = $request->file('archivo');
            $nombreArchivo = Str::slug($request->numero_factura) . '_' . time() . '.' . $archivo->getClientOriginalExtension();
            $ruta = $archivo->storeAs('facturas', $nombreArchivo, 'public');
            
            $factura->archivo_path = $ruta;
            $factura->archivo_nombre = $archivo->getClientOriginalName();
        }

        $factura->save();

        return redirect()->route('facturas.index')
            ->with('success', 'Factura creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Factura $factura)
    {
        // Verificar que el usuario sea propietario de la factura
        if ($factura->run !== Auth::user()->run) {
            abort(403, 'No tienes permisos para ver esta factura.');
        }

        return view('facturas.show', compact('factura'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Factura $factura)
    {
        // Verificar que el usuario sea propietario de la factura
        if ($factura->run !== Auth::user()->run) {
            abort(403, 'No tienes permisos para editar esta factura.');
        }

        $proveedores = \App\Models\Proveedor::orderBy('nombre_proveedor')->get();
        return view('facturas.edit', compact('factura', 'proveedores'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Factura $factura)
    {
        // Verificar que el usuario sea propietario de la factura
        if ($factura->run !== Auth::user()->run) {
            abort(403, 'No tienes permisos para editar esta factura.');
        }

        $request->validate([
            'numero_factura' => 'required|string|max:255|unique:facturas,numero_factura,' . $factura->id,
            'proveedor_id' => 'required|exists:proveedores,id',
            'monto_total' => 'required|numeric|min:0',
            'fecha_factura' => 'required|date',
            'archivo' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'observaciones' => 'nullable|string|max:1000'
        ]);

        $factura->numero_factura = $request->numero_factura;
        $factura->proveedor_id = $request->proveedor_id;
        $factura->monto_total = $request->monto_total;
        $factura->fecha_factura = $request->fecha_factura;
        $factura->observaciones = $request->observaciones;

        // Manejar archivo si se sube uno nuevo
        if ($request->hasFile('archivo')) {
            // Eliminar archivo anterior si existe
            if ($factura->archivo_path && Storage::disk('public')->exists($factura->archivo_path)) {
                Storage::disk('public')->delete($factura->archivo_path);
            }

            $archivo = $request->file('archivo');
            $nombreArchivo = Str::slug($request->numero_factura) . '_' . time() . '.' . $archivo->getClientOriginalExtension();
            $ruta = $archivo->storeAs('facturas', $nombreArchivo, 'public');
            
            $factura->archivo_path = $ruta;
            $factura->archivo_nombre = $archivo->getClientOriginalName();
        }

        $factura->save();

        return redirect()->route('facturas.index')
            ->with('success', 'Factura actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Factura $factura)
    {
        // Verificar que el usuario sea propietario de la factura
        if ($factura->run !== Auth::user()->run) {
            abort(403, 'No tienes permisos para eliminar esta factura.');
        }

        // Eliminar archivo si existe
        if ($factura->archivo_path && Storage::disk('public')->exists($factura->archivo_path)) {
            Storage::disk('public')->delete($factura->archivo_path);
        }

        $factura->delete();

        return redirect()->route('facturas.index')
            ->with('success', 'Factura eliminada exitosamente.');
    }

    /**
     * Download the attached file
     */
    public function download(Factura $factura)
    {
        // Verificar que el usuario sea propietario de la factura
        if ($factura->run !== Auth::user()->run) {
            abort(403, 'No tienes permisos para descargar esta factura.');
        }

        if (!$factura->archivo_path || !Storage::disk('public')->exists($factura->archivo_path)) {
            abort(404, 'El archivo no existe.');
        }

        return Storage::disk('public')->download($factura->archivo_path, $factura->archivo_nombre);
    }
}
