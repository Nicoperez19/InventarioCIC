<?php

namespace App\Livewire\Tables;

use App\Models\Insumo;
use App\Models\TipoInsumo;
use App\Models\Solicitud;
use App\Models\SolicitudItem;
use App\Models\Notificacion;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SolicitudInsumosTable extends Component
{
    use WithPagination;
    
    public $cantidades = [];
    public $tipoInsumoFiltro = null;
    public $busqueda = '';
    public $ordenamiento = 'nombre_asc';
    public $errores = [];
    public $perPage = 12; // Número de cards por página

    public function updatingTipoInsumoFiltro()
    {
        $this->resetPage();
    }

    public function updatingBusqueda()
    {
        $this->resetPage();
    }

    public function updatingOrdenamiento()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    private function obtenerQueryInsumos()
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

        return $query;
    }
    
    private function obtenerTodosLosInsumos()
    {
        // Obtener todos los insumos (sin paginación) para validaciones
        return $this->obtenerQueryInsumos()->get();
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



    public function actualizarCantidad($insumoId, $cantidad)
    {
        $cantidad = (int) $cantidad;
        
        // Buscar el insumo en la base de datos
        $insumo = Insumo::with(['unidadMedida', 'tipoInsumo'])
            ->where('id_insumo', $insumoId)
            ->first();
        
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

            // Crear notificaciones para usuarios con permiso de administrar solicitudes
            try {
                // Buscar usuarios que pueden aprobar solicitudes (administradores o con permiso 'admin solicitudes')
                $usuariosNotificables = \App\Models\User::whereHas('roles', function ($query) {
                    $query->where('name', 'Administrador');
                })->orWhereHas('permissions', function ($query) {
                    $query->where('name', 'admin solicitudes');
                })->get();
                
                \Illuminate\Support\Facades\Log::info('Creando notificaciones de solicitud (Livewire)', [
                    'solicitud_id' => $solicitud->id,
                    'numero_solicitud' => $solicitud->numero_solicitud,
                    'usuarios_notificables_count' => $usuariosNotificables->count()
                ]);

                // Cargar relaciones necesarias para el mensaje
                $solicitud->load('departamento');

                foreach ($usuariosNotificables as $usuario) {
                    Notificacion::create([
                        'tipo' => 'solicitud',
                        'titulo' => 'Nueva Solicitud Pendiente de Aprobación',
                        'mensaje' => "Se ha creado una nueva solicitud #{$solicitud->numero_solicitud} por " . $user->nombre . " del departamento " . ($solicitud->departamento->nombre_depto ?? 'N/A') . ". Requiere aprobación.",
                        'user_id' => $usuario->run,
                        'solicitud_id' => $solicitud->id,
                    ]);
                }
                
                \Illuminate\Support\Facades\Log::info('Notificaciones de solicitud creadas exitosamente (Livewire)', [
                    'count' => $usuariosNotificables->count()
                ]);
                
                // Disparar evento Livewire para actualizar notificaciones en tiempo real
                $this->dispatch('notificacionCreada');
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Error al crear notificaciones de solicitud (Livewire)', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                // No fallar la creación de la solicitud si falla la notificación
            }

            DB::commit();
            
            // Limpiar cantidades y errores
            $this->cantidades = array_fill_keys(array_keys($this->cantidades), 0);
            $this->errores = [];
            
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

        $detalle = [];
        foreach ($itemsConCantidad as $insumoId => $cantidad) {
            // Obtener el insumo desde la base de datos
            $insumo = Insumo::with('unidadMedida')->find($insumoId);
            if ($insumo) {
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
        return $this->resumenPedido;
    }
    
    public function paginationView()
    {
        return 'vendor.livewire.tailwind';
    }

    public function render()
    {
        // Obtener insumos paginados
        $insumos = $this->obtenerQueryInsumos()->paginate($this->perPage);
        
        // Inicializar cantidades en 0 solo para nuevos insumos
        foreach ($insumos as $insumo) {
            if (!isset($this->cantidades[$insumo->id_insumo])) {
                $this->cantidades[$insumo->id_insumo] = 0;
            }
        }
        
        // Limpiar cantidades de insumos que ya no están disponibles (solo si cambió la página)
        $insumoIds = $insumos->pluck('id_insumo')->toArray();
        // Mantener las cantidades de todos los insumos, no solo los de la página actual
        
        $tiposDisponibles = TipoInsumo::whereIn('id', $this->obtenerTiposPermitidos(Auth::user()))->get();
        
        return view('livewire.tables.solicitud-insumos-table', [
            'insumos' => $insumos,
            'tiposDisponibles' => $tiposDisponibles
        ]);
    }
}
