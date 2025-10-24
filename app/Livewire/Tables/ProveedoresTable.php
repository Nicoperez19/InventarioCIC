<?php

namespace App\Livewire\Tables;

use App\Models\Proveedor;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Proveedores')]
class ProveedoresTable extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.tables.proveedores-table', [
            'proveedores' => Proveedor::withCount('facturas')
                ->withSum('facturas', 'monto_total')
                ->orderBy('nombre_proveedor')
                ->paginate(10),
        ]);
    }

    public function paginationView()
    {
        return 'vendor.livewire.tailwind';
    }
}
