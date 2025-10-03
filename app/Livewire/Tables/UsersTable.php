<?php

namespace App\Livewire\Tables;

use Livewire\Component;
use App\Models\User;

class UsersTable extends Component
{
    public function render()
    {
        return view('livewire.tables.users-table', [
            'users' => User::all(),
        ]);
    }
}
