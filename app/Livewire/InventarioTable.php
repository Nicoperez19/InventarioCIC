<?php

namespace App\Livewire;

use App\Models\Producto;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Inventario')]
class InventarioTable extends Component
{
    use WithPagination;

    // Vista de productos: no hay acciones de ajuste directo aquí

    public function render()
    {
        $query = $this->buildQuery();
        $productos = $query->paginate(10);

        return view('livewire.inventario-table', [
            'productos' => $productos,
        ]);
    }

    private function buildQuery()
    {
        return Producto::with('unidad')->orderBy('nombre_producto');
    }

    public function paginationView()
    {
        return 'vendor.livewire.tailwind';
    }
}
