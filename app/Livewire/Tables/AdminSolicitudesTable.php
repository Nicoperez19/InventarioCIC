<?php

namespace App\Livewire\Tables;

use App\Models\Solicitud;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class AdminSolicitudesTable extends Component
{
    use WithPagination;

    public $search = '';
    public $estadoFiltro = '';
    public $departamentoFiltro = '';
    public $fechaDesde = '';
    public $fechaHasta = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'estadoFiltro' => ['except' => ''],
        'departamentoFiltro' => ['except' => ''],
        'fechaDesde' => ['except' => ''],
        'fechaHasta' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingEstadoFiltro()
    {
        $this->resetPage();
    }

    public function updatingDepartamentoFiltro()
    {
        $this->resetPage();
    }

    public function updatingFechaDesde()
    {
        $this->resetPage();
    }

    public function updatingFechaHasta()
    {
        $this->resetPage();
    }

    public function aprobarSolicitud($solicitudId)
    {
        try {
            $solicitud = Solicitud::findOrFail($solicitudId);
            $solicitud->aprobar(Auth::user()->run);
            
            session()->flash('success', "Solicitud #{$solicitud->numero_solicitud} aprobada exitosamente");
        } catch (\Exception $e) {
            session()->flash('error', 'Error al aprobar la solicitud: ' . $e->getMessage());
        }
    }

    public function rechazarSolicitud($solicitudId)
    {
        try {
            $solicitud = Solicitud::findOrFail($solicitudId);
            $solicitud->rechazar(Auth::user()->run);
            
            session()->flash('success', "Solicitud #{$solicitud->numero_solicitud} rechazada");
        } catch (\Exception $e) {
            session()->flash('error', 'Error al rechazar la solicitud: ' . $e->getMessage());
        }
    }

    public function entregarSolicitud($solicitudId)
    {
        try {
            $solicitud = Solicitud::findOrFail($solicitudId);
            $solicitud->entregar(Auth::user()->run);
            
            session()->flash('success', "Solicitud #{$solicitud->numero_solicitud} marcada como entregada");
        } catch (\Exception $e) {
            session()->flash('error', 'Error al marcar como entregada: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = Solicitud::with(['user', 'departamento', 'items.insumo'])
            ->orderBy('created_at', 'desc');

        // Aplicar filtros
        if ($this->search) {
            $query->where(function($q) {
                $q->where('numero_solicitud', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function($userQuery) {
                      $userQuery->where('nombre', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->estadoFiltro) {
            $query->where('estado', $this->estadoFiltro);
        }

        if ($this->departamentoFiltro) {
            $query->where('departamento_id', $this->departamentoFiltro);
        }

        if ($this->fechaDesde) {
            $query->whereDate('fecha_solicitud', '>=', $this->fechaDesde);
        }

        if ($this->fechaHasta) {
            $query->whereDate('fecha_solicitud', '<=', $this->fechaHasta);
        }

        $solicitudes = $query->paginate(10);

        return view('livewire.tables.admin-solicitudes-table', [
            'solicitudes' => $solicitudes
        ]);
    }
}
