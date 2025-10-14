<?php

namespace App\Livewire;

use App\Models\Inventario;
use App\Models\Producto;
use App\Models\Unidad;
use App\Models\Departamento;
use Livewire\Component;
use Livewire\WithPagination;

class InventarioTable extends Component
{
    use WithPagination;

    public $search = '';
    public $productoFilter = [];
    public $unidadFilter = [];
    public $departamentoFilter = [];

    public function updatingSearch() { $this->resetPage(); }
    public function updatingProductoFilter() { $this->resetPage(); }
    public function updatingUnidadFilter() { $this->resetPage(); }
    public function updatingDepartamentoFilter() { $this->resetPage(); }

    public function incrementarCantidad($inventarioId)
    {
        $inventario = Inventario::find($inventarioId);
        if ($inventario) {
            $inventario->increment('cantidad_inventario');
            $this->dispatch('inventario-actualizado');
        }
    }

    public function decrementarCantidad($inventarioId)
    {
        $inventario = Inventario::find($inventarioId);
        if ($inventario && $inventario->cantidad_inventario > 0) {
            $inventario->decrement('cantidad_inventario');
            $this->dispatch('inventario-actualizado');
        }
    }

    public function actualizarCantidad($inventarioId, $nuevaCantidad)
    {
        $inventario = Inventario::find($inventarioId);
        if ($inventario && $nuevaCantidad >= 0) {
            $inventario->update(['cantidad_inventario' => $nuevaCantidad]);
            $this->dispatch('inventario-actualizado');
        }
    }

    public function render()
    {
        $query = $this->buildQuery();
        $inventarios = $query->paginate(15);

        return view('livewire.inventario-table', [
            'inventarios' => $inventarios,
            'productos' => Producto::orderBy('nombre_producto')->get(),
            'unidades' => Unidad::orderBy('nombre_unidad')->get(),
            'departamentos' => Departamento::orderBy('nombre_depto')->get()
        ]);
    }

    private function buildQuery()
    {
        $query = Inventario::with(['producto.unidad'])
            ->join('productos', 'inventarios.id_producto', '=', 'productos.id_producto')
            ->join('unidads', 'productos.id_unidad', '=', 'unidads.id_unidad')
            ->leftJoin('detalle__solicituds', 'productos.id_producto', '=', 'detalle__solicituds.id_producto')
            ->leftJoin('solicituds', 'detalle__solicituds.id_solicitud', '=', 'solicituds.id_solicitud')
            ->leftJoin('users', 'solicituds.id_usuario', '=', 'users.run')
            ->leftJoin('departamentos', 'users.id_depto', '=', 'departamentos.id_depto');

        if ($this->search) {
            $query->where('productos.nombre_producto', 'like', '%' . $this->search . '%');
        }

        if (!empty($this->productoFilter)) {
            $query->whereIn('inventarios.id_producto', $this->productoFilter);
        }

        if (!empty($this->unidadFilter)) {
            $query->whereIn('productos.id_unidad', $this->unidadFilter);
        }

        if (!empty($this->departamentoFilter)) {
            $query->whereIn('users.id_depto', $this->departamentoFilter);
        }

        return $query->select('inventarios.*')
            ->distinct()
            ->orderBy('productos.nombre_producto');
    }
}
