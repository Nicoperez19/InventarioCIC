<?php

namespace App\Livewire;

use App\Models\Factura;
use App\Models\Proveedor;
use Livewire\Component;
use Livewire\WithPagination;

class FacturasTable extends Component
{
    use WithPagination;

    public $search = '';
    public $proveedorFilter = '';
    public $fechaDesde = '';
    public $fechaHasta = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'proveedorFilter' => ['except' => ''],
        'fechaDesde' => ['except' => ''],
        'fechaHasta' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingProveedorFilter()
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

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function delete($id)
    {
        $factura = Factura::findOrFail($id);
        
        if ($factura->run !== auth()->user()->run) {
            session()->flash('error', 'No tienes permisos para eliminar esta factura.');
            return;
        }

        if ($factura->archivo_path && \Storage::disk('public')->exists($factura->archivo_path)) {
            \Storage::disk('public')->delete($factura->archivo_path);
        }

        $factura->delete();
        session()->flash('success', 'Factura eliminada exitosamente.');
    }

    public function download($id)
    {
        $factura = Factura::findOrFail($id);
        
        if ($factura->run !== auth()->user()->run) {
            session()->flash('error', 'No tienes permisos para descargar esta factura.');
            return;
        }

        if (!$factura->archivo_path || !\Storage::disk('public')->exists($factura->archivo_path)) {
            session()->flash('error', 'El archivo no existe.');
            return;
        }

        return \Storage::disk('public')->download($factura->archivo_path, $factura->archivo_nombre);
    }

    public function render()
    {
        $facturas = Factura::query()
            ->where('run', auth()->user()->run)
            ->with('proveedor')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('numero_factura', 'like', '%' . $this->search . '%')
                      ->orWhere('observaciones', 'like', '%' . $this->search . '%')
                      ->orWhereHas('proveedor', function ($q) {
                          $q->where('nombre_proveedor', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->proveedorFilter, function ($query) {
                $query->where('proveedor_id', $this->proveedorFilter);
            })
            ->when($this->fechaDesde, function ($query) {
                $query->where('fecha_factura', '>=', $this->fechaDesde);
            })
            ->when($this->fechaHasta, function ($query) {
                $query->where('fecha_factura', '<=', $this->fechaHasta);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        $proveedores = Proveedor::orderBy('nombre_proveedor')->get();

        return view('livewire.facturas-table', compact('facturas', 'proveedores'));
    }
}