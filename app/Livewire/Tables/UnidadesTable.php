<?php
namespace App\Livewire\Tables;
use App\Models\UnidadMedida;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
#[Title('Unidades')]
class UnidadesTable extends Component
{
    use WithPagination;
    public function render()
    {
        return view('livewire.tables.unidades-table', [
            'unidades' => UnidadMedida::paginate(10),
        ]);
    }
    public function paginationView()
    {
        return 'vendor.livewire.tailwind';
    }
}
