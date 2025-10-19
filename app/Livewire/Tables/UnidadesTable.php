<?php

namespace App\Livewire\Tables;

use App\Models\Unidad;
use Livewire\Component;

class UnidadesTable extends Component
{
    public function render()
    {
        return view('livewire.tables.unidades-table', [
            'unidades' => Unidad::all(),
        ]);
    }
}
