<?php

namespace App\Livewire\Tables;

use Livewire\Component;
use Spatie\Permission\Models\Permission;

class PermissionsTable extends Component
{
    public function render()
    {
        return view('livewire.tables.permissions-table', [
            'permissions' => Permission::orderBy('name')->get(),
        ]);
    }
}
