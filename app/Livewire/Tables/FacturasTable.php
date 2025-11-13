<?php
namespace App\Livewire\Tables;
use App\Models\Factura;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
#[Title('Facturas')]
class FacturasTable extends Component
{
    use WithPagination;
    public function render()
    {
        return view('livewire.tables.facturas-table', [
            'facturas' => Factura::where('run', Auth::user()->run)
                ->with(['proveedor', 'user'])
                ->orderBy('created_at', 'desc')
                ->paginate(10),
        ]);
    }
    public function paginationView()
    {
        return 'vendor.livewire.tailwind';
    }
}



