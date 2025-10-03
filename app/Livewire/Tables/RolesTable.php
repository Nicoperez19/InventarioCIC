<?php

namespace App\Livewire\Tables;

use Livewire\Component;
use Spatie\Permission\Models\Role;

class RolesTable extends Component
{
    public function render()
    {
        return view('livewire.tables.roles-table', [
            'roles' => Role::orderBy('name')->get(),
        ]);
    }
}


