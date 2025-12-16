<?php
namespace App\Livewire;
use App\Models\Insumo;
use App\Services\QrService;
use Livewire\Component;
class QrDisplay extends Component
{
    public Insumo $insumo;
    public string $qrUrl = '';
    public bool $showQr = false;
    public function mount(Insumo $insumo)
    {
        $this->insumo = $insumo;
        $this->loadQr();
    }
    public function loadQr()
    {
        if ($this->insumo->codigo_barra) {
            $qrService = new QrService();
            $this->qrUrl = $qrService->getQrUrl($this->insumo->codigo_barra);
            $this->showQr = true;
        }
    }
    public function regenerateQr()
    {
        if (!$this->insumo->codigo_barra) {
            return;
        }
        $qrService = new QrService();
        $qrService->deleteQrImage($this->insumo->codigo_barra);
        $nuevoCodigo = $qrService->generateUniqueQr($this->insumo->id_unidad);
        $this->insumo->update(['codigo_barra' => $nuevoCodigo]);
        $qrService->generateQrImage($nuevoCodigo);
        $this->loadQr();
        session()->flash('message', 'Código QR regenerado exitosamente');
    }

    public function render()
    {
        return view('livewire.qr-display');
    }
}


