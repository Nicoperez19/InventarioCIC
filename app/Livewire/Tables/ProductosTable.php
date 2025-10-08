<?php

namespace App\Livewire\Tables;

use Livewire\Component;
use App\Models\Producto;

class ProductosTable extends Component
{
    public function render()
    {
        return view('livewire.tables.productos-table', [
            'productos' => Producto::all(),
        ]);
    }
}


