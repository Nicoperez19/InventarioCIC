<?php

namespace App\Livewire;

use App\Models\User;
use App\Services\QrService;
use Livewire\Component;

class UserQrDisplay extends Component
{
    public User $user;
    public string $qrUrl = '';
    public bool $showQr = false;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->loadQr();
    }

    public function loadQr()
    {
        if ($this->user->codigo_barra) {
            $qrService = new QrService();
            $this->qrUrl = $qrService->getUserQrUrl($this->user->codigo_barra);
            $this->showQr = true;
        }
    }

    public function generateQr()
    {
        $qrService = new QrService();
        
        // Eliminar todas las imágenes anteriores del usuario
        if ($this->user->codigo_barra) {
            $qrService->deleteUserQrImage($this->user->codigo_barra);
        }
        $qrService->deleteAllUserQrImages($this->user);
        
        // Generar nuevo código QR
        $nuevoCodigo = $qrService->generateUserQr($this->user->run);
        $this->user->update(['codigo_barra' => $nuevoCodigo]);
        
        // Generar las imágenes (PNG y SVG)
        $qrService->generateUserQrImage($nuevoCodigo);
        $qrService->generateUserQrSVG($nuevoCodigo);
        
        // Recargar
        $this->user->refresh();
        $this->loadQr();
        
        session()->flash('message', 'Código QR generado exitosamente');
    }

    public function regenerateQr()
    {
        if (!$this->user->codigo_barra) {
            $this->generateQr();
            return;
        }
        
        $qrService = new QrService();
        
        // Eliminar todas las imágenes anteriores
        $qrService->deleteUserQrImage($this->user->codigo_barra);
        $qrService->deleteAllUserQrImages($this->user);
        
        // Generar nuevo código QR
        $nuevoCodigo = $qrService->generateUserQr($this->user->run);
        $this->user->update(['codigo_barra' => $nuevoCodigo]);
        
        // Generar las imágenes (PNG y SVG)
        $qrService->generateUserQrImage($nuevoCodigo);
        $qrService->generateUserQrSVG($nuevoCodigo);
        
        // Recargar
        $this->user->refresh();
        $this->loadQr();
        
        session()->flash('message', 'Código QR regenerado exitosamente');
    }

    public function render()
    {
        return view('livewire.user-qr-display');
    }
}


