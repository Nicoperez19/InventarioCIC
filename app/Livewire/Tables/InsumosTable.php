<?php

namespace App\Livewire\Tables;

use App\Models\Insumo;
use Livewire\Component;

class InsumosTable extends Component
{
    public function render()
    {
        return view('livewire.tables.insumos-table', [
            'insumos' => Insumo::with('unidadMedida')->get(),
        ]);
    }
}
