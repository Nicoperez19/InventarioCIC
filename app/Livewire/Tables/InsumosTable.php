<?php
namespace App\Livewire\Tables;
use App\Models\Insumo;
use App\Models\UnidadMedida;
use App\Models\TipoInsumo;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
#[Title('Insumos')]
class InsumosTable extends Component
{
    use WithPagination;
    
    public $search = '';
    public $unidadFilter = '';
    public $tipoInsumoFilter = '';
    public $stockFilter = '';
    public $perPage = 10;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingUnidadFilter()
    {
        $this->resetPage();
    }

    public function updatingTipoInsumoFilter()
    {
        $this->resetPage();
    }

    public function updatingStockFilter()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Insumo::with(['unidadMedida', 'tipoInsumo']);

        // Búsqueda por texto
        if ($this->search) {
            $query->where(function($q) {
                $q->where('nombre_insumo', 'like', '%' . $this->search . '%')
                  ->orWhere('codigo_barra', 'like', '%' . $this->search . '%')
                  ->orWhere('id_insumo', 'like', '%' . $this->search . '%');
            });
        }

        // Filtro por unidad de medida
        if ($this->unidadFilter) {
            $query->where('id_unidad', $this->unidadFilter);
        }

        // Filtro por tipo de insumo
        if ($this->tipoInsumoFilter) {
            $query->where('tipo_insumo_id', $this->tipoInsumoFilter);
        }

        // Filtro por estado de stock
        if ($this->stockFilter) {
            match ($this->stockFilter) {
                'agotado' => $query->where('stock_actual', '<=', 0),
                'bajo' => $query->where(function($q) {
                    $q->whereColumn('stock_actual', '<=', 'stock_minimo')
                      ->where('stock_actual', '>', 0);
                }),
                'normal' => $query->where('stock_actual', '>', 0),
                default => null
            };
        }

        $insumos = $query->orderBy('nombre_insumo')->paginate($this->perPage);

        return view('livewire.tables.insumos-table', [
            'insumos' => $insumos,
            'unidades' => UnidadMedida::orderBy('nombre_unidad_medida')->get(),
            'tiposInsumo' => TipoInsumo::orderBy('nombre_tipo')->get(),
        ]);
    }

    public function paginationView()
    {
        return 'vendor.livewire.tailwind';
    }
}