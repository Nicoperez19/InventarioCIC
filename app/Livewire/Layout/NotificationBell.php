<?php

namespace App\Livewire\Layout;

use App\Models\Notificacion;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NotificationBell extends Component
{
    public $notificaciones = [];
    public $countNoLeidas = 0;

    protected $listeners = ['notificacionCreada' => 'actualizarNotificaciones'];

    // Polling cada 30 segundos para actualizar notificaciones
    public function poll()
    {
        $this->cargarNotificaciones();
    }

    public function mount()
    {
        // Cargar notificaciones para todos los usuarios autenticados
        if (Auth::check()) {
            $this->cargarNotificaciones();
        }
    }

    public function cargarNotificaciones()
    {
        $this->notificaciones = Notificacion::paraUsuario(Auth::user()->run)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->toArray();
        
        $this->countNoLeidas = Notificacion::paraUsuario(Auth::user()->run)
            ->noLeidas()
            ->count();
    }

    public function marcarComoLeida($notificacionId)
    {
        $notificacion = Notificacion::find($notificacionId);
        if ($notificacion && $notificacion->user_id === Auth::user()->run) {
            $notificacion->marcarComoLeida();
            $this->cargarNotificaciones();
        }
    }

    public function marcarTodasComoLeidas()
    {
        Notificacion::paraUsuario(Auth::user()->run)
            ->noLeidas()
            ->update([
                'leida' => true,
                'leida_at' => now(),
            ]);
        
        $this->cargarNotificaciones();
    }

    public function actualizarNotificaciones()
    {
        $this->cargarNotificaciones();
    }

    public function render()
    {
        // Renderizar notificaciones para todos los usuarios autenticados
        if (!Auth::check()) {
            return view('livewire.layout.notification-bell-empty');
        }
        
        // Verificar si el usuario tiene permiso para ver notificaciones
        if (!Auth::user()->can('notificaciones')) {
            return view('livewire.layout.notification-bell-empty');
        }
        
        return view('livewire.layout.notification-bell');
    }
}
