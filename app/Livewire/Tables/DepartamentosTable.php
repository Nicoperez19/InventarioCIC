<?php

namespace App\Livewire\Tables;

use Livewire\Component;
use App\Models\Departamento;

class DepartamentosTable extends Component
{
    public function render()
    {
        return view('livewire.tables.departamentos-table', [
            'departamentos' => Departamento::all(),
        ]);
    }
}


