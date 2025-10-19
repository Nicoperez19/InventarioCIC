<?php

namespace App\Livewire;

use App\Models\Producto;
use Livewire\Component;

class InventarioTable extends Component
{
    // Vista de productos: no hay acciones de ajuste directo aquí

    public function render()
    {
        $query = $this->buildQuery();
        $productos = $query->get();

        return view('livewire.inventario-table', [
            'productos' => $productos,
        ]);
    }

    private function buildQuery()
    {
        return Producto::with('unidad')->orderBy('nombre_producto');
    }
}
