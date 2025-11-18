<?php
namespace App\Livewire\Tables;
use App\Models\User;
use App\Models\Departamento;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
#[Title('Usuarios')]
class UsersTable extends Component
{
    use WithPagination;
    
    public $search = '';
    public $departamentoFilter = '';
    public $perPage = 10;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingDepartamentoFilter()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = User::getActiveUsers();

        // Búsqueda por texto
        if ($this->search) {
            $query->where(function($q) {
                $q->where('nombre', 'like', '%' . $this->search . '%')
                  ->orWhere('correo', 'like', '%' . $this->search . '%')
                  ->orWhere('run', 'like', '%' . $this->search . '%');
            });
        }

        // Filtro por departamento
        if ($this->departamentoFilter) {
            $query->where('id_depto', $this->departamentoFilter);
        }

        // Cargar relaciones necesarias para el modal de edición
        $users = $query->with([
            'departamento:id_depto,nombre_depto',
            'permissions:id,name'
        ])->orderByName()->paginate($this->perPage);

        return view('livewire.tables.users-table', [
            'users' => $users,
            'departamentos' => Departamento::orderByName()->get(),
        ]);
    }

    public function paginationView()
    {
        return 'vendor.livewire.tailwind';
    }
}
