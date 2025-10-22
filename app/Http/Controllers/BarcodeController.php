<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Services\BarcodeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BarcodeController extends Controller
{
    public function __construct(private BarcodeService $barcodeService)
    {
    }

    public function show(string $productoId): RedirectResponse
    {
        $producto = Producto::findOrFail($productoId);
        
        if (!$producto->codigo_barra) {
            abort(404, 'Producto sin código de barras');
        }

        $imagePath = $this->barcodeService->generateBarcodeImage($producto->codigo_barra);
        $url = asset('storage/' . $imagePath);
        
        return redirect($url);
    }

    public function generate(string $productoId): BinaryFileResponse
    {
        $producto = Producto::findOrFail($productoId);
        
        if (!$producto->codigo_barra) {
            abort(404, 'Producto sin código de barras');
        }

        $imagePath = $this->barcodeService->generateBarcodeImage($producto->codigo_barra);
        $fullPath = storage_path('app/public/' . $imagePath);
        
        if (!file_exists($fullPath)) {
            abort(404, 'Imagen del código de barras no encontrada');
        }

        return response()->file($fullPath, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    public function generateSmall(string $productoId): BinaryFileResponse
    {
        $producto = Producto::findOrFail($productoId);
        
        if (!$producto->codigo_barra) {
            abort(404, 'Producto sin código de barras');
        }

        $imagePath = $this->barcodeService->generateSmallBarcode($producto->codigo_barra);
        $fullPath = storage_path('app/public/' . $imagePath);
        
        if (!file_exists($fullPath)) {
            abort(404, 'Imagen pequeña del código de barras no encontrada');
        }

        return response()->file($fullPath, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    public function generateSvg(string $productoId): BinaryFileResponse
    {
        $producto = Producto::findOrFail($productoId);
        
        if (!$producto->codigo_barra) {
            abort(404, 'Producto sin código de barras');
        }

        $imagePath = $this->barcodeService->generateBarcodeSVG($producto->codigo_barra);
        $fullPath = storage_path('app/public/' . $imagePath);
        
        if (!file_exists($fullPath)) {
            abort(404, 'Imagen SVG del código de barras no encontrada');
        }

        return response()->file($fullPath, [
            'Content-Type' => 'image/svg+xml',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    public function regenerate(string $productoId): RedirectResponse
    {
        $producto = Producto::findOrFail($productoId);
        
        if (!$producto->codigo_barra) {
            abort(404, 'Producto sin código de barras');
        }

        $newBarcode = $this->barcodeService->generateUniqueBarcode($producto->id_unidad);
        
        // Eliminar imagen anterior si existe
        $oldImagePath = "codigos_productos/barcode_{$producto->codigo_barra}.png";
        if (Storage::disk('public')->exists($oldImagePath)) {
            Storage::disk('public')->delete($oldImagePath);
        }
        
        $producto->update(['codigo_barra' => $newBarcode]);
        
        $this->barcodeService->generateBarcodeImage($newBarcode);

        return back()->with('success', 'Código de barras regenerado exitosamente');
    }

    public function validate(Request $request)
    {
        $barcode = $request->input('barcode');
        
        if (!$barcode) {
            return response()->json(['valid' => false, 'message' => 'Código de barras requerido']);
        }

        $isValid = $this->barcodeService->validateBarcode($barcode);
        $info = $this->barcodeService->getBarcodeInfo($barcode);

        return response()->json([
            'valid' => $isValid,
            'info' => $info
        ]);
    }
}