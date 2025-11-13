<?php

namespace App\Livewire\Tables;

use App\Models\Insumo;
use App\Models\TipoInsumo;
use App\Models\Solicitud;
use App\Models\SolicitudItem;
use App\Models\Notificacion;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SolicitudInsumosTable extends Component
{
    public $insumos;
    public $cantidades = [];
    public $tipoInsumoFiltro = null;
    public $busqueda = '';
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
        
        // Validar que no exceda el stock disponible - si excede, poner en 0
        if ($cantidad > $insumo->stock_actual) {
            $this->cantidades[$insumoId] = 0;
            $this->errores[$insumoId] = "No puedes solicitar más de {$insumo->stock_actual} unidades. El valor se ha restablecido a 0.";
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
            
            // Crear la solicitud en estado pendiente (requiere aprobación)
            $solicitud = Solicitud::create([
                'tipo_solicitud' => 'individual',
                'estado' => 'pendiente', // Cambiar a pendiente para requerir aprobación
                'user_id' => $user->run,
                'departamento_id' => $user->id_depto,
                'fecha_solicitud' => now(),
                'observaciones' => null
            ]);

            // Crear los items de la solicitud (sin reducir stock todavía)
            foreach ($itemsConCantidad as $insumoId => $cantidad) {
                // Obtener el insumo
                $insumo = Insumo::find($insumoId);
                
                if (!$insumo) {
                    DB::rollBack();
                    session()->flash('error', "El insumo con ID {$insumoId} no fue encontrado");
                    return;
                }
                
                // Verificar que hay stock disponible
                if ($insumo->stock_actual < $cantidad) {
                    DB::rollBack();
                    session()->flash('error', "No hay suficiente stock para el insumo {$insumo->nombre_insumo}. Stock disponible: {$insumo->stock_actual}, solicitado: {$cantidad}");
                    return;
                }
                
                // Validar que la cantidad no sea negativa
                if ($cantidad < 0) {
                    DB::rollBack();
                    session()->flash('error', "No se pueden solicitar valores negativos para el insumo {$insumo->nombre_insumo}");
                    return;
                }

                // Crear el item de la solicitud (sin aprobar todavía)
                SolicitudItem::create([
                    'solicitud_id' => $solicitud->id,
                    'insumo_id' => $insumoId,
                    'cantidad_solicitada' => $cantidad,
                    'cantidad_aprobada' => 0, // No aprobar todavía
                    'cantidad_entregada' => 0,
                    'estado_item' => 'pendiente', // Estado pendiente
                    'observaciones_item' => null
                ]);
            }

            // Crear notificaciones para administradores
            try {
                // Buscar usuarios con rol Administrador
                $adminsPorRol = \App\Models\User::role('Administrador')->get();
                
                // Buscar usuarios con permiso manage-requests o approve-requests
                $adminsPorPermiso = \App\Models\User::permission(['manage-requests', 'approve-requests'])->get();
                
                // Combinar y eliminar duplicados
                $administradores = $adminsPorRol->merge($adminsPorPermiso)->unique('run');
                
                // Si no hay administradores, notificar a todos los usuarios (fallback)
                if ($administradores->isEmpty()) {
                    \Illuminate\Support\Facades\Log::warning('No se encontraron administradores para notificar. Notificando a todos los usuarios.');
                    $administradores = \App\Models\User::all();
                }
                
                \Illuminate\Support\Facades\Log::info('Creando notificaciones (Livewire)', [
                    'solicitud_id' => $solicitud->id,
                    'numero_solicitud' => $solicitud->numero_solicitud,
                    'administradores_count' => $administradores->count()
                ]);

                foreach ($administradores as $admin) {
                    Notificacion::create([
                        'tipo' => 'solicitud',
                        'titulo' => 'Nueva Solicitud de Insumos',
                        'mensaje' => "Se ha creado una nueva solicitud #{$solicitud->numero_solicitud} por " . $user->nombre,
                        'user_id' => $admin->run,
                        'solicitud_id' => $solicitud->id,
                    ]);
                }
                
                \Illuminate\Support\Facades\Log::info('Notificaciones creadas exitosamente (Livewire)', [
                    'count' => $administradores->count()
                ]);
                
                // Disparar evento Livewire para actualizar notificaciones en tiempo real
                $this->dispatch('notificacionCreada');
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Error al crear notificaciones (Livewire)', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                // No fallar la creación de la solicitud si falla la notificación
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
                $mensaje = "¡Solicitud #{$numeroSolicitud} creada exitosamente! Tu pedido de {$totalUnidades} unidad(es) ha sido registrado y está pendiente de aprobación.";
            } else {
                $mensaje = "¡Solicitud #{$numeroSolicitud} creada exitosamente! Tu pedido de {$cantidadInsumos} insumo(s) por un total de {$totalUnidades} unidad(es) ha sido registrado y está pendiente de aprobación.";
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
