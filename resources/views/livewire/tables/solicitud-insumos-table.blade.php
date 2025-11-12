<div>
    <!-- Mensajes -->
    @if (session()->has('success'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-50 border-l-4 border-red-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('info'))
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">{{ session('info') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Barra de búsqueda y filtros -->
    <div class="mb-6 overflow-hidden bg-white border rounded-lg shadow-sm border-neutral-200">
        <!-- Header del panel de filtros -->
        <div class="px-4 py-3 border-b bg-primary-50 border-neutral-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    <h3 class="text-sm font-semibold text-primary-800">Filtros de Búsqueda</h3>
                </div>
            </div>
        </div>

        <!-- Contenido de los filtros -->
        <div class="p-4 space-y-4">
            <!-- Primera fila: Buscador y Filtro por Tipo de Insumo -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Buscador -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-neutral-700">
                        <div class="flex items-center space-x-1">
                            <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span>Buscar Insumo</span>
                        </div>
                    </label>
                    <div class="relative">
                        <input 
                            type="text"
                            wire:model.live.debounce.300ms="busqueda"
                            placeholder="Buscar por nombre de insumo..."
                            class="w-full px-3 py-2.5 pl-10 border border-neutral-300 rounded-lg bg-white text-neutral-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-150"
                        >
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        @if($busqueda)
                            <button 
                                wire:click="$set('busqueda', '')"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-neutral-400 hover:text-neutral-600"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Filtro por Tipo de Insumo -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-neutral-700">
                        <div class="flex items-center space-x-1">
                            <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            <span>Tipo de Insumo</span>
                        </div>
                    </label>
                    <select 
                        wire:model.live="tipoInsumoFiltro" 
                        id="tipo-filtro"
                        class="w-full px-3 py-2.5 border border-neutral-300 rounded-lg bg-white text-neutral-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-150"
                    >
                        <option value="">Todos los tipos</option>
                        @foreach($tiposDisponibles as $tipo)
                            <option value="{{ $tipo->id }}">{{ $tipo->nombre_tipo }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Indicador de filtros activos -->
            @if($tipoInsumoFiltro || $busqueda)
                <div class="flex items-center justify-between pt-2 border-t border-neutral-200">
                    <div class="flex items-center space-x-2 text-sm text-neutral-600">
                        <svg class="w-4 h-4 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Filtros activos</span>
                    </div>
                    <button 
                        wire:click="$set('tipoInsumoFiltro', ''); $set('busqueda', '')"
                        class="flex items-center space-x-1 text-xs font-medium transition-colors duration-150 text-primary-600 hover:text-primary-800"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span>Limpiar filtros</span>
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Tabla -->
    <div class="w-full overflow-hidden bg-white border rounded-lg shadow-sm border-neutral-200">
        <div class="w-full overflow-x-auto">
        <table class="w-full divide-y divide-neutral-200">
            <thead class="bg-primary-100">
                <tr>
                    <th class="px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <div class="flex items-center space-x-1">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            <span>ID</span>
                        </div>
                    </th>
                    <th class="px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <div class="flex items-center space-x-1">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <span>Insumo</span>
                        </div>
                    </th>
                    <th class="px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <div class="flex items-center space-x-1">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                            </svg>
                            <span>Tipo</span>
                        </div>
                    </th>
                    <th class="px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <div class="flex items-center space-x-1">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span>Unidad</span>
                        </div>
                    </th>
                    <th class="px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <div class="flex items-center space-x-1">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span>Stock Disponible</span>
                        </div>
                    </th>
                    <th class="px-3 sm:px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <div class="flex items-center justify-center space-x-1">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            <span>Cantidad a Solicitar</span>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-neutral-200">
                @forelse($insumos as $insumo)
                    <tr class="hover:bg-secondary-50 transition-colors duration-150">
                        <!-- ID -->
                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-neutral-500">
                                {{ $insumo->id_insumo }}
                            </div>
                        </td>
                        
                        <!-- Insumo -->
                        <td class="px-3 sm:px-6 py-4">
                            <div class="text-sm font-medium text-neutral-900">{{ $insumo->nombre_insumo }}</div>
                        </td>
                        
                        <!-- Tipo -->
                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $insumo->tipoInsumo ? $insumo->tipoInsumo->nombre_tipo : 'Sin tipo' }}
                            </span>
                        </td>
                        
                        <!-- Unidad -->
                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-neutral-600">{{ $insumo->unidadMedida ? $insumo->unidadMedida->nombre_unidad_medida : $insumo->id_unidad }}</div>
                        </td>
                        
                        <!-- Stock Disponible -->
                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-neutral-600">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ $insumo->stock_actual }}
                                </span>
                            </div>
                        </td>
                        
                        <!-- Cantidad a Solicitar -->
                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col items-center justify-center space-y-1">
                                <input type="number" 
                                       wire:model.defer="cantidades.{{ $insumo->id_insumo }}"
                                       wire:change="actualizarCantidad('{{ $insumo->id_insumo }}', $event.target.value)"
                                       wire:blur="actualizarCantidad('{{ $insumo->id_insumo }}', $event.target.value)"
                                       class="w-20 px-2 py-1 text-xs font-medium text-center border rounded transition-colors {{ isset($errores[$insumo->id_insumo]) ? 'border-red-500 bg-red-50 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }}"
                                       min="0"
                                       max="{{ $insumo->stock_actual }}"
                                       step="1"
                                       value="{{ $cantidades[$insumo->id_insumo] ?? 0 }}"
                                       placeholder="0"
                                       data-max-stock="{{ $insumo->stock_actual }}"
                                       data-insumo-id="{{ $insumo->id_insumo }}">
                                @if(isset($errores[$insumo->id_insumo]))
                                    <span class="text-xs text-red-600 text-center max-w-32" title="{{ $errores[$insumo->id_insumo] }}">
                                        <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ Str::limit($errores[$insumo->id_insumo], 25) }}
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-3 sm:px-6 py-12 text-center" colspan="6">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-neutral-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-neutral-900 mb-2">No hay insumos disponibles</h3>
                                <p class="text-neutral-500">No se encontraron insumos con stock disponible para tu rol.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <!-- Resumen de solicitud -->
    @if(collect($cantidades)->filter(fn($cantidad) => $cantidad > 0)->count() > 0)
        @php
            $itemsConCantidad = collect($cantidades)->filter(fn($cantidad) => $cantidad > 0);
            $resumenPedido = $this->resumenPedido;
        @endphp
        <div class="p-4 bg-gradient-to-r from-secondary-50 to-secondary-100 border-l-4 border-secondary-500 rounded-lg shadow-sm">
            <div class="flex items-center mb-3">
                <svg class="w-5 h-5 text-secondary-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                <h4 class="text-base font-semibold text-secondary-900">Detalle del Pedido</h4>
            </div>
            <div class="bg-white rounded-lg border border-secondary-200 p-4 mb-3">
                <div class="space-y-2">
                    @foreach($resumenPedido as $item)
                        <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ $item['nombre'] }}</p>
                                <p class="text-xs text-gray-500">Stock disponible: {{ $item['stock_disponible'] }} {{ $item['unidad'] }}</p>
                            </div>
                            <div class="ml-4 text-right">
                                <p class="text-sm font-bold text-secondary-600">{{ $item['cantidad'] }} {{ $item['unidad'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-3 pt-3 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Total de insumos:</span>
                        <span class="text-sm font-bold text-secondary-700">{{ count($resumenPedido) }}</span>
                    </div>
                    <div class="flex items-center justify-between mt-1">
                        <span class="text-sm font-medium text-gray-700">Total de unidades:</span>
                        <span class="text-sm font-bold text-secondary-700">{{ $itemsConCantidad->sum() }}</span>
                    </div>
                </div>
            </div>
            <div class="p-3 bg-secondary-100 rounded-lg border border-secondary-300">
                <div class="flex items-start">
                    <svg class="w-4 h-4 text-secondary-600 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-xs text-secondary-800">
                        <strong>Nota:</strong> Al crear la solicitud, el stock se reducirá automáticamente y la solicitud será aprobada inmediatamente.
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Escuchar eventos desde el header
    window.addEventListener('limpiar-solicitud', function() {
        @this.limpiarSolicitud();
    });
    
    window.addEventListener('crear-solicitud', function() {
        // Obtener los insumos seleccionados desde Livewire
        @this.call('obtenerResumenPedido').then(function(resumenPedido) {
            if (!resumenPedido || resumenPedido.length === 0) {
                alert('Debe seleccionar al menos un insumo con cantidad mayor a 0');
                return;
            }

            // Construir el HTML del detalle del pedido
            let detalleHTML = '<div class="text-left space-y-3 mb-4">';
            let totalUnidades = 0;
            
            resumenPedido.forEach(function(item) {
                detalleHTML += `
                    <div class="flex items-center justify-between py-2 border-b border-gray-200 last:border-b-0">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">${item.nombre}</p>
                            <p class="text-xs text-gray-500">Stock disponible: ${item.stock_disponible} ${item.unidad}</p>
                        </div>
                        <div class="ml-4 text-right">
                            <p class="text-sm font-bold text-secondary-600">${item.cantidad} ${item.unidad}</p>
                        </div>
                    </div>
                `;
                totalUnidades += parseInt(item.cantidad);
            });
            
            detalleHTML += '</div>';
            detalleHTML += `
                <div class="bg-gray-50 rounded-lg p-3 mb-4">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700">Total de insumos:</span>
                        <span class="text-sm font-bold text-secondary-700">${resumenPedido.length}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Total de unidades:</span>
                        <span class="text-sm font-bold text-secondary-700">${totalUnidades}</span>
                    </div>
                </div>
            `;
            detalleHTML += '<p class="text-sm text-gray-600 text-center font-medium">¿Deseas confirmar esta solicitud?</p>';

            // Mostrar modal de confirmación personalizado
            window.confirmAction({
                title: 'Confirmar Solicitud de Insumos',
                message: detalleHTML,
                confirmText: 'Sí, crear solicitud',
                cancelText: 'Cancelar'
            })
            .then(function() {
                @this.crearSolicitud();
            })
            .catch(function() {
                // Usuario canceló
            });
        }).catch(function(error) {
            console.error('Error al obtener resumen:', error);
            // Fallback: crear directamente con confirmación simple
            if (confirm('¿Crear la solicitud? El stock se reducirá automáticamente.')) {
                @this.crearSolicitud();
            }
        });
    });
    
    // Escuchar evento de Livewire para mostrar notificación modal centrada como en departamentos
    document.addEventListener('livewire:init', function() {
        Livewire.on('solicitud-creada-exito', function(data) {
            // Esperar un poco para que se complete la transacción
            setTimeout(function() {
                const mensaje = data && data[0] ? data[0].mensaje : (data?.mensaje || data);
                if (window.notifySuccess && mensaje) {
                    window.notifySuccess(mensaje);
                }
            }, 100);
        });
    });
    
    const timeouts = new Map(); // Usar un mapa para trackear timeouts por input
    
    function validarInput(input) {
        if (!input || input.type !== 'number' || !input.hasAttribute('data-max-stock')) {
            return;
        }
        
        const maxValue = parseInt(input.getAttribute('data-max-stock')) || 0;
        let currentValue = input.value === '' ? 0 : parseInt(input.value) || 0;
        
        // Si el valor es negativo o excede el máximo, establecer a 0
        if (currentValue < 0 || (maxValue > 0 && currentValue > maxValue)) {
            input.value = 0;
            // Disparar evento change una sola vez para que Livewire actualice
            input.dispatchEvent(new Event('change', { bubbles: true }));
        }
    }
    
    // Validar solo cuando salga del campo (blur)
    document.addEventListener('blur', function(e) {
        if (e.target.type === 'number' && e.target.hasAttribute('data-max-stock')) {
            const inputId = e.target.getAttribute('data-insumo-id');
            if (timeouts.has(inputId)) {
                clearTimeout(timeouts.get(inputId));
                timeouts.delete(inputId);
            }
            validarInput(e.target);
        }
    }, true);
    
    // Validación al presionar Enter
    document.addEventListener('keydown', function(e) {
        if (e.target.type === 'number' && e.target.hasAttribute('data-max-stock')) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const inputId = e.target.getAttribute('data-insumo-id');
                if (timeouts.has(inputId)) {
                    clearTimeout(timeouts.get(inputId));
                    timeouts.delete(inputId);
                }
                validarInput(e.target);
                e.target.blur();
            }
        }
    }, true);
    
    // NO validar mientras escribe - solo trackear para limpiar timeout si es necesario
    document.addEventListener('input', function(e) {
        if (e.target.type === 'number' && e.target.hasAttribute('data-max-stock')) {
            const inputId = e.target.getAttribute('data-insumo-id');
            // Limpiar cualquier timeout pendiente para este input
            if (timeouts.has(inputId)) {
                clearTimeout(timeouts.get(inputId));
            }
            // NO validar aquí - solo permitir escribir libremente
        }
    }, true);
});
</script>
