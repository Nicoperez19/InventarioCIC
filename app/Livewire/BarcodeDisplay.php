<?php

namespace App\Livewire;

use App\Models\Producto;
use App\Services\BarcodeService;
use Livewire\Component;

class BarcodeDisplay extends Component
{
    public Producto $producto;
    public string $barcodeUrl = '';
    public bool $showBarcode = false;

    public function mount(Producto $producto)
    {
        $this->producto = $producto;
        $this->loadBarcode();
    }

    public function loadBarcode()
    {
        if ($this->producto->codigo_barra) {
            $barcodeService = new BarcodeService();
            $this->barcodeUrl = $barcodeService->getBarcodeUrl($this->producto->codigo_barra);
            $this->showBarcode = true;
        }
    }

    public function regenerateBarcode()
    {
        if (!$this->producto->codigo_barra) {
            return;
        }

        $barcodeService = new BarcodeService();
        
        // Eliminar imagen anterior
        $barcodeService->deleteBarcodeImage($this->producto->codigo_barra);
        
        // Generar nuevo código basado en la unidad del producto
        $nuevoCodigo = $barcodeService->generateUniqueBarcode($this->producto->id_unidad);
        
        // Actualizar producto
        $this->producto->update(['codigo_barra' => $nuevoCodigo]);
        
        // Generar nueva imagen
        $barcodeService->generateBarcodeImage($nuevoCodigo);
        
        // Recargar
        $this->loadBarcode();
        
        session()->flash('message', 'Código de barras regenerado exitosamente');
    }

    public function downloadBarcode()
    {
        if (!$this->producto->codigo_barra) {
            return;
        }

        $barcodeService = new BarcodeService();
        $imagePath = $barcodeService->generateBarcodeImage($this->producto->codigo_barra);
        $fullPath = storage_path('app/public/' . $imagePath);
        
        return response()->download($fullPath, "codigo_barras_{$this->producto->id_producto}.png");
    }

    public function render()
    {
        return view('livewire.barcode-display');
    }
}