<?php

namespace App\Livewire;

use App\Models\Proveedor;
use Livewire\Component;
use Livewire\WithPagination;

class ProveedoresTable extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'nombre_proveedor';
    public $sortDirection = 'asc';

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'nombre_proveedor'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function updatingSearch()
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
        $proveedor = Proveedor::findOrFail($id);
        
        if ($proveedor->tieneFacturas()) {
            session()->flash('error', 'No se puede eliminar el proveedor porque tiene facturas asociadas.');
            return;
        }

        $proveedor->delete();
        session()->flash('success', 'Proveedor eliminado exitosamente.');
    }

    public function render()
    {
        $proveedores = Proveedor::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nombre_proveedor', 'like', '%' . $this->search . '%')
                      ->orWhere('rut', 'like', '%' . $this->search . '%')
                      ->orWhere('telefono', 'like', '%' . $this->search . '%');
                });
            })
            ->withCount('facturas')
            ->withSum('facturas', 'monto_total')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.proveedores-table', compact('proveedores'));
    }
}