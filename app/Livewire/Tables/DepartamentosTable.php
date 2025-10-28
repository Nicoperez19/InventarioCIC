<?php
namespace App\Livewire\Tables;
use App\Models\Departamento;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
#[Title('Departamentos')]
class DepartamentosTable extends Component
{
    use WithPagination;
    public function render()
    {
        return view('livewire.tables.departamentos-table', [
            'departamentos' => Departamento::paginate(10),
        ]);
    }
    public function paginationView()
    {
        return 'vendor.livewire.tailwind';
    }
}
