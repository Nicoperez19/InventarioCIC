<?php
namespace App\Livewire;
use App\Models\Insumo;
use App\Services\BarcodeService;
use Livewire\Component;
class BarcodeDisplay extends Component
{
    public Insumo $insumo;
    public string $barcodeUrl = '';
    public bool $showBarcode = false;
    public function mount(Insumo $insumo)
    {
        $this->insumo = $insumo;
        $this->loadBarcode();
    }
    public function loadBarcode()
    {
        if ($this->insumo->codigo_barra) {
            $barcodeService = new BarcodeService();
            $this->barcodeUrl = $barcodeService->getBarcodeUrl($this->insumo->codigo_barra);
            $this->showBarcode = true;
        }
    }
    public function regenerateBarcode()
    {
        if (!$this->insumo->codigo_barra) {
            return;
        }
        $barcodeService = new BarcodeService();
        $barcodeService->deleteBarcodeImage($this->insumo->codigo_barra);
        $nuevoCodigo = $barcodeService->generateUniqueBarcode($this->insumo->id_unidad);
        $this->insumo->update(['codigo_barra' => $nuevoCodigo]);
        $barcodeService->generateBarcodeImage($nuevoCodigo);
        $this->loadBarcode();
        session()->flash('message', 'Código QR regenerado exitosamente');
    }

    public function render()
    {
        return view('livewire.barcode-display');
    }
}