<?php

namespace App\Livewire\Tables;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Usuarios')]
class UsersTable extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.tables.users-table', [
            'users' => User::getActiveUsers()->orderByName()->paginate(10),
        ]);
    }

    public function paginationView()
    {
        return 'vendor.livewire.tailwind';
    }
}
