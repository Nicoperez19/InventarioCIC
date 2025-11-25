<?php

namespace App\Livewire;

use App\Models\User;
use App\Services\BarcodeService;
use Livewire\Component;

class UserBarcodeDisplay extends Component
{
    public User $user;
    public string $barcodeUrl = '';
    public bool $showBarcode = false;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->loadBarcode();
    }

    public function loadBarcode()
    {
        if ($this->user->codigo_barra) {
            $barcodeService = new BarcodeService();
            $this->barcodeUrl = $barcodeService->getUserBarcodeUrl($this->user->codigo_barra);
            $this->showBarcode = true;
        }
    }

    public function generateBarcode()
    {
        $barcodeService = new BarcodeService();
        
        // Eliminar todas las imágenes anteriores del usuario
        if ($this->user->codigo_barra) {
            $barcodeService->deleteUserBarcodeImage($this->user->codigo_barra);
        }
        $barcodeService->deleteAllUserBarcodeImages($this->user);
        
        // Generar nuevo código de barras
        $nuevoCodigo = $barcodeService->generateUserBarcode($this->user->run);
        $this->user->update(['codigo_barra' => $nuevoCodigo]);
        
        // Generar las imágenes (PNG y SVG)
        $barcodeService->generateUserBarcodeImage($nuevoCodigo);
        $barcodeService->generateUserBarcodeSVG($nuevoCodigo);
        
        // Recargar
        $this->user->refresh();
        $this->loadBarcode();
        
        session()->flash('message', 'Código de barras generado exitosamente');
    }

    public function regenerateBarcode()
    {
        if (!$this->user->codigo_barra) {
            $this->generateBarcode();
            return;
        }
        
        $barcodeService = new BarcodeService();
        
        // Eliminar todas las imágenes anteriores
        $barcodeService->deleteUserBarcodeImage($this->user->codigo_barra);
        $barcodeService->deleteAllUserBarcodeImages($this->user);
        
        // Generar nuevo código de barras
        $nuevoCodigo = $barcodeService->generateUserBarcode($this->user->run);
        $this->user->update(['codigo_barra' => $nuevoCodigo]);
        
        // Generar las imágenes (PNG y SVG)
        $barcodeService->generateUserBarcodeImage($nuevoCodigo);
        $barcodeService->generateUserBarcodeSVG($nuevoCodigo);
        
        // Recargar
        $this->user->refresh();
        $this->loadBarcode();
        
        session()->flash('message', 'Código de barras regenerado exitosamente');
    }

    public function render()
    {
        return view('livewire.user-barcode-display');
    }
}
