<?php

namespace App\Livewire\Tables;

use App\Models\Insumo;
use App\Models\TipoInsumo;
use App\Models\Solicitud;
use App\Models\SolicitudItem;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SolicitudInsumosTable extends Component
{
    public $insumos;
    public $cantidades = [];
    public $tipoInsumoFiltro = null;

    public function mount()
    {
        $this->cargarInsumos();
    }

    public function cargarInsumos()
    {
        $user = Auth::user();
        
        // Determinar qué tipos de insumo puede ver según su rol
        $tiposPermitidos = $this->obtenerTiposPermitidos($user);
        
        $query = Insumo::with([
                'unidadMedida:id_unidad,nombre_unidad_medida',
                'tipoInsumo:id,nombre_tipo'
            ])
            ->whereIn('tipo_insumo_id', $tiposPermitidos)
            ->where('stock_actual', '>', 0); // Solo insumos con stock disponible

        // Aplicar filtro por tipo si está seleccionado
        if ($this->tipoInsumoFiltro) {
            $query->where('tipo_insumo_id', $this->tipoInsumoFiltro);
        }

        $this->insumos = $query->get();
        
        // Inicializar cantidades en 0 solo para nuevos insumos
        foreach ($this->insumos as $insumo) {
            if (!isset($this->cantidades[$insumo->id_insumo])) {
                $this->cantidades[$insumo->id_insumo] = 0;
            }
        }
        
        // Limpiar cantidades de insumos que ya no están disponibles
        $insumoIds = $this->insumos->pluck('id_insumo')->toArray();
        $this->cantidades = array_intersect_key($this->cantidades, array_flip($insumoIds));
    }

    public function obtenerTiposPermitidos($user)
    {
        if ($user->hasRole('jefe-departamento')) {
            // Jefe departamento ve "Artículos Oficina"
            return TipoInsumo::where('nombre_tipo', 'Artículos Oficina')->pluck('id')->toArray();
        } elseif ($user->hasRole('auxiliar')) {
            // Auxiliar ve "Artículos Aseo"
            return TipoInsumo::where('nombre_tipo', 'Artículos Aseo')->pluck('id')->toArray();
        } else {
            // Admin ve todos los tipos
            return TipoInsumo::pluck('id')->toArray();
        }
    }

    private function verificarRelacionesCargadas()
    {
        if (!$this->insumos || $this->insumos->isEmpty()) {
            return false;
        }

        $firstInsumo = $this->insumos->first();
        return $firstInsumo->relationLoaded('tipoInsumo') && $firstInsumo->relationLoaded('unidadMedida');
    }

    public function updatedTipoInsumoFiltro()
    {
        $this->cargarInsumos();
    }

    public function actualizarCantidad($insumoId, $cantidad)
    {
        $cantidad = (int) $cantidad;
        $insumo = $this->insumos->find($insumoId);
        
        if ($insumo && $cantidad >= 0 && $cantidad <= $insumo->stock_actual) {
            $this->cantidades[$insumoId] = $cantidad;
        } else {
            $this->cantidades[$insumoId] = 0;
        }
    }

    public function crearSolicitud()
    {
        $itemsConCantidad = collect($this->cantidades)->filter(function($cantidad) {
            return $cantidad > 0;
        });

        if ($itemsConCantidad->isEmpty()) {
            session()->flash('error', 'Debe seleccionar al menos un insumo con cantidad mayor a 0');
            return;
        }

        try {
            DB::beginTransaction();

            $user = Auth::user();
            
            // Crear la solicitud
            $solicitud = Solicitud::create([
                'tipo_solicitud' => 'individual',
                'estado' => 'aprobada', // Auto-aprobar ya que se reduce el stock inmediatamente
                'user_id' => $user->run,
                'departamento_id' => $user->id_depto,
                'fecha_solicitud' => now(),
                'fecha_aprobacion' => now(),
                'aprobado_por' => $user->run,
                'observaciones' => 'Solicitud creada y aprobada automáticamente desde el sistema'
            ]);

            // Crear los items de la solicitud y reducir stock
            foreach ($itemsConCantidad as $insumoId => $cantidad) {
                // Verificar que hay suficiente stock
                $insumo = Insumo::find($insumoId);
                if (!$insumo || $insumo->stock_actual < $cantidad) {
                    DB::rollBack();
                    $nombreInsumo = $insumo ? $insumo->nombre_insumo : $insumoId;
                    session()->flash('error', "No hay suficiente stock para el insumo {$nombreInsumo}");
                    return;
                }

                // Crear el item de la solicitud
                SolicitudItem::create([
                    'solicitud_id' => $solicitud->id,
                    'insumo_id' => $insumoId,
                    'cantidad_solicitada' => $cantidad,
                    'cantidad_aprobada' => $cantidad, // Auto-aprobar la cantidad solicitada
                    'cantidad_entregada' => 0,
                    'estado_item' => 'aprobado', // Marcar como aprobado automáticamente
                    'observaciones_item' => null
                ]);

                // Reducir el stock del insumo
                $insumo->decrement('stock_actual', $cantidad);
            }

            DB::commit();
            
            session()->flash('success', "Solicitud #{$solicitud->numero_solicitud} creada y aprobada exitosamente con {$itemsConCantidad->count()} insumos. El stock ha sido reducido automáticamente.");
            
            // Limpiar cantidades
            $this->cantidades = array_fill_keys(array_keys($this->cantidades), 0);
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al crear la solicitud: ' . $e->getMessage());
        }
    }

    public function limpiarSolicitud()
    {
        $this->cantidades = array_fill_keys(array_keys($this->cantidades), 0);
        session()->flash('info', 'Solicitud limpiada');
    }

    public function render()
    {
        // Asegurar que los insumos siempre tengan las relaciones cargadas
        if (!$this->insumos || $this->insumos->isEmpty()) {
            $this->cargarInsumos();
        }
        
        // Verificar que las relaciones estén cargadas
        if (!$this->verificarRelacionesCargadas()) {
            $this->cargarInsumos();
        }
        
        $tiposDisponibles = TipoInsumo::whereIn('id', $this->obtenerTiposPermitidos(Auth::user()))->get();
        
        return view('livewire.tables.solicitud-insumos-table', [
            'tiposDisponibles' => $tiposDisponibles
        ]);
    }
}
