<?php

namespace App\Livewire\Tables;

use App\Models\User;
use Livewire\Component;

class UsersTable extends Component
{
    public function render()
    {
        return view('livewire.tables.users-table', [
            'users' => User::getActiveUsers()->orderByName()->get(),
        ]);
    }
}
