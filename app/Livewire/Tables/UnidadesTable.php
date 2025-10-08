<?php

namespace App\Livewire\Tables;

use Livewire\Component;
use App\Models\Unidad;

class UnidadesTable extends Component
{
    public function render()
    {
        return view('livewire.tables.unidades-table', [
            'unidades' => Unidad::all(),
        ]);
    }
}


