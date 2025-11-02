<?php
namespace App\Livewire\Tables;
use App\Models\TipoInsumo;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
#[Title('Tipos de Insumo')]
class TipoInsumosTable extends Component
{
    use WithPagination;
    public function render()
    {
        return view('livewire.tables.tipo-insumos-table', [
            'tiposInsumo' => TipoInsumo::withCount('insumos')
                ->orderBy('nombre_tipo')
                ->paginate(10),
        ]);
    }
    public function paginationView()
    {
        return 'vendor.livewire.tailwind';
    }

    protected $paginationTheme = 'tailwind';
}

