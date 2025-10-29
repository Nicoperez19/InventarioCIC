<?php
namespace App\Livewire\Tables;
use App\Models\Insumo;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class InsumosTable extends Component
{
    public $stockValues = [];

    public function mount()
    {
        // Inicializar los valores de stock
        $insumos = Insumo::all();
        foreach ($insumos as $insumo) {
            $this->stockValues[$insumo->id_insumo] = $insumo->stock_actual;
        }
    }

    public function updateStock($insumoId, $newValue)
    {
        try {
            // Validación rápida
            if (!is_numeric($newValue) || $newValue < 0) {
                $this->addError('stock_' . $insumoId, 'El stock debe ser un número positivo');
                return;
            }

            // Actualización directa en la base de datos (más rápido que find + save)
            $updated = Insumo::where('id_insumo', $insumoId)
                ->update(['stock_actual' => (int) $newValue]);

            if ($updated === 0) {
                $this->addError('stock_' . $insumoId, 'El insumo no existe o ha sido eliminado');
                return;
            }

            // Actualizar el valor local
            $this->stockValues[$insumoId] = (int) $newValue;

        } catch (\Exception $e) {
            $this->addError('stock_' . $insumoId, 'Error al actualizar el stock');
        }
    }

    public function render()
    {
        return view('livewire.tables.insumos-table', [
            'insumos' => Insumo::with('unidadMedida')->get(),
        ]);
    }
}