<?php

namespace App\Livewire\Tables;

use App\Models\Producto;
use Livewire\Component;

class ProductosTable extends Component
{
    public function render()
    {
        return view('livewire.tables.productos-table', [
            'productos' => Producto::with('unidad')->get(),
        ]);
    }
}
