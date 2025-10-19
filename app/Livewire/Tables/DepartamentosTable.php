<?php

namespace App\Livewire\Tables;

use App\Models\Departamento;
use Livewire\Component;

class DepartamentosTable extends Component
{
    public function render()
    {
        return view('livewire.tables.departamentos-table', [
            'departamentos' => Departamento::all(),
        ]);
    }
}
