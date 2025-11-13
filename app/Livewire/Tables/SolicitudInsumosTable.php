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
    public $busqueda = '';
    public $ordenamiento = 'nombre_asc';
    public $errores = [];

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

        // Aplicar búsqueda por nombre si hay término de búsqueda
        if (!empty($this->busqueda)) {
            $query->where('nombre_insumo', 'like', '%' . $this->busqueda . '%');
        }

        // Aplicar ordenamiento
        switch ($this->ordenamiento) {
            case 'nombre_asc':
                $query->orderBy('nombre_insumo', 'asc');
                break;
            case 'nombre_desc':
                $query->orderBy('nombre_insumo', 'desc');
                break;
            case 'stock_asc':
                $query->orderBy('stock_actual', 'asc');
                break;
            case 'stock_desc':
                $query->orderBy('stock_actual', 'desc');
                break;
            default:
                $query->orderBy('nombre_insumo', 'asc');
        }

        $this->insumos = $query->get();
        
        // Asegurar que las relaciones estén cargadas
        $this->insumos->loadMissing(['unidadMedida', 'tipoInsumo']);
        
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

    public function updatedBusqueda()
    {
        $this->cargarInsumos();
    }

    public function updatedOrdenamiento()
    {
        $this->cargarInsumos();
    }


    public function actualizarCantidad($insumoId, $cantidad)
    {
        $cantidad = (int) $cantidad;
        
        // Buscar el insumo en la colección por id_insumo
        $insumo = $this->insumos->firstWhere('id_insumo', $insumoId);
        
        // Limpiar error previo para este insumo
        unset($this->errores[$insumoId]);
        
        if (!$insumo) {
            $this->cantidades[$insumoId] = 0;
            $this->errores[$insumoId] = 'Insumo no encontrado';
            return;
        }
        
        // Validar que no sea negativo - si es negativo, poner en 0
        if ($cantidad < 0) {
            $this->cantidades[$insumoId] = 0;
            $this->errores[$insumoId] = 'No se pueden solicitar valores negativos';
            return;
        }
        
        // Validar que no exceda el stock disponible - si excede, mostrar error y no permitir
        if ($cantidad > $insumo->stock_actual) {
            $this->cantidades[$insumoId] = 0;
            $this->errores[$insumoId] = "No hay stock suficiente. Stock disponible: {$insumo->stock_actual} unidades.";
            return;
        }
        
        // Si todo está bien, asignar la cantidad
        $this->cantidades[$insumoId] = $cantidad;
    }

    public function crearSolicitud()
    {
        // Limpiar errores previos
        $this->errores = [];
        
        $itemsConCantidad = collect($this->cantidades)->filter(function($cantidad) {
            return $cantidad > 0;
        });

        if ($itemsConCantidad->isEmpty()) {
            session()->flash('error', 'Debe seleccionar al menos un insumo con cantidad mayor a 0');
            return;
        }

        // Validar todas las cantidades antes de crear la solicitud
        $erroresValidacion = [];
        
        foreach ($itemsConCantidad as $insumoId => $cantidad) {
            // Validar que la cantidad no sea negativa
            if ($cantidad < 0) {
                $erroresValidacion[$insumoId] = 'No se pueden solicitar valores negativos';
                continue;
            }
            
            // Verificar que el insumo existe y tiene suficiente stock
            $insumo = Insumo::find($insumoId);
            if (!$insumo) {
                $erroresValidacion[$insumoId] = 'Insumo no encontrado';
                continue;
            }
            
            // Validar que no se exceda el stock disponible
            if ($cantidad > $insumo->stock_actual) {
                $erroresValidacion[$insumoId] = "No puedes solicitar más de {$insumo->stock_actual} unidades de {$insumo->nombre_insumo} (stock disponible: {$insumo->stock_actual})";
            }
        }
        
        // Si hay errores, mostrarlos y no continuar
        if (!empty($erroresValidacion)) {
            $this->errores = $erroresValidacion;
            $mensajeError = 'Errores de validación: ' . implode(', ', $erroresValidacion);
            session()->flash('error', $mensajeError);
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
                // Obtener el insumo con bloqueo para evitar condiciones de carrera
                $insumo = Insumo::lockForUpdate()->find($insumoId);
                
                if (!$insumo) {
                    DB::rollBack();
                    session()->flash('error', "El insumo con ID {$insumoId} no fue encontrado");
                    return;
                }
                
                // Verificar nuevamente el stock (doble verificación)
                if ($insumo->stock_actual < $cantidad) {
                    DB::rollBack();
                    session()->flash('error', "No hay suficiente stock para el insumo {$insumo->nombre_insumo}. Stock disponible: {$insumo->stock_actual}, solicitado: {$cantidad}");
                    return;
                }
                
                // Validar que la cantidad no sea negativa (segunda verificación)
                if ($cantidad < 0) {
                    DB::rollBack();
                    session()->flash('error', "No se pueden solicitar valores negativos para el insumo {$insumo->nombre_insumo}");
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
            
            // Limpiar cantidades y errores
            $this->cantidades = array_fill_keys(array_keys($this->cantidades), 0);
            $this->errores = [];
            
            // Recargar insumos para actualizar el stock
            $this->cargarInsumos();
            
            // Preparar datos para el mensaje de éxito claro y entendible
            $totalUnidades = $itemsConCantidad->sum();
            $numeroSolicitud = $solicitud->numero_solicitud;
            $cantidadInsumos = $itemsConCantidad->count();
            
            // Mensaje claro y amigable para el usuario
            if ($cantidadInsumos == 1) {
                $mensaje = "¡Solicitud #{$numeroSolicitud} creada exitosamente! Tu pedido de {$totalUnidades} unidad(es) ha sido registrado y el stock fue actualizado.";
            } else {
                $mensaje = "¡Solicitud #{$numeroSolicitud} creada exitosamente! Tu pedido de {$cantidadInsumos} insumo(s) por un total de {$totalUnidades} unidad(es) ha sido registrado y el stock fue actualizado.";
            }
            
            // Disparar evento para mostrar notificación modal centrada como en departamentos
            $this->dispatch('solicitud-creada-exito', ['mensaje' => $mensaje]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al crear la solicitud: ' . $e->getMessage());
        }
    }

    public function limpiarSolicitud()
    {
        $this->cantidades = array_fill_keys(array_keys($this->cantidades), 0);
        $this->errores = [];
        session()->flash('info', 'Solicitud limpiada');
    }

    public function getResumenPedidoProperty()
    {
        $itemsConCantidad = collect($this->cantidades)->filter(function($cantidad) {
            return $cantidad > 0;
        });

        // Asegurar que los insumos tienen las relaciones cargadas
        $this->insumos->loadMissing(['unidadMedida']);

        $detalle = [];
        foreach ($itemsConCantidad as $insumoId => $cantidad) {
            $insumo = $this->insumos->firstWhere('id_insumo', $insumoId);
            if ($insumo) {
                // Cargar la relación si no está cargada
                if (!$insumo->relationLoaded('unidadMedida')) {
                    $insumo->load('unidadMedida');
                }
                
                $detalle[] = [
                    'id' => $insumo->id_insumo,
                    'nombre' => $insumo->nombre_insumo,
                    'cantidad' => $cantidad,
                    'unidad' => $insumo->unidadMedida ? $insumo->unidadMedida->nombre_unidad_medida : 'unidad',
                    'stock_disponible' => $insumo->stock_actual
                ];
            }
        }

        return $detalle;
    }

    public function obtenerResumenPedido()
    {
        // Asegurar que los insumos tienen las relaciones cargadas antes de obtener el resumen
        if ($this->insumos && $this->insumos->isNotEmpty()) {
            $this->insumos->loadMissing(['unidadMedida']);
        }
        return $this->resumenPedido;
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
