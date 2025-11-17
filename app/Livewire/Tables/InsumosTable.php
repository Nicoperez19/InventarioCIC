<?php
namespace App\Livewire\Tables;
use App\Models\Insumo;
use App\Models\UnidadMedida;
use App\Models\TipoInsumo;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
#[Title('Insumos')]
class InsumosTable extends Component
{
    use WithPagination;
    
    public $search = '';
    public $unidadFilter = '';
    #[Url(as: 'tipoInsumoFilter')]
    public $tipoInsumoFilter = '';
    public $stockFilter = '';
    public $perPage = 10;
    public $sortField = 'nombre_insumo';
    public $sortDirection = 'asc';

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
        // Resetear el filtro de unidad cuando cambia el tipo de insumo
        $this->unidadFilter = '';
    }

    public function updatingStockFilter()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            // Si ya está ordenando por este campo, alternar la dirección
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            // Si es un campo nuevo, ordenar ascendente por defecto
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
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

        // Aplicar ordenamiento
        $insumos = $query->orderBy($this->sortField, $this->sortDirection)->paginate($this->perPage);

        // Filtrar unidades de medida según el tipo de insumo seleccionado
        $unidades = UnidadMedida::orderBy('nombre_unidad_medida');
        if ($this->tipoInsumoFilter) {
            // Obtener solo las unidades usadas por insumos del tipo seleccionado
            $unidades = $unidades->whereHas('insumos', function($q) {
                $q->where('tipo_insumo_id', $this->tipoInsumoFilter);
            });
        }
        $unidades = $unidades->get();

        return view('livewire.tables.insumos-table', [
            'insumos' => $insumos,
            'unidades' => $unidades,
            'tiposInsumo' => TipoInsumo::orderBy('nombre_tipo')->get(),
        ]);
    }

    public function paginationView()
    {
        return 'vendor.livewire.tailwind';
    }

    protected $paginationTheme = 'tailwind';
}