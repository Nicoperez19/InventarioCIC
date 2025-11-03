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
                @if($tipoInsumoFiltro)
                    <button 
                        wire:click="$set('tipoInsumoFiltro', '')"
                        class="flex items-center space-x-1 text-xs font-medium transition-colors duration-150 text-primary-600 hover:text-primary-800"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span>Limpiar filtros</span>
                    </button>
                @endif
            </div>
        </div>

        <!-- Contenido de los filtros -->
        <div class="p-4 space-y-4">
            <!-- Primera fila: Filtro por Tipo de Insumo -->
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

            <!-- Indicador de filtros activos -->
            @if($tipoInsumoFiltro)
                <div class="flex items-center justify-end pt-2 border-t border-neutral-200">
                    <div class="flex items-center space-x-2 text-sm text-neutral-600">
                        <svg class="w-4 h-4 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Filtros activos</span>
                    </div>
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
        <div class="p-4 border-t border-gray-200 bg-blue-50">
            <h4 class="text-sm font-medium text-blue-900 mb-2">Resumen de la solicitud:</h4>
            <div class="text-sm text-blue-700">
                @php
                    $itemsConCantidad = collect($cantidades)->filter(fn($cantidad) => $cantidad > 0);
                @endphp
                <p>{{ $itemsConCantidad->count() }} insumo(s) seleccionado(s) con un total de {{ $itemsConCantidad->sum() }} unidades</p>
                <div class="mt-2 p-2 bg-blue-100 rounded border border-blue-200">
                    <div class="flex items-start">
                        <svg class="w-4 h-4 text-blue-600 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="text-xs text-blue-800">
                            <strong>Nota:</strong> Al crear la solicitud, el stock se reducirá automáticamente y la solicitud será aprobada inmediatamente.
                        </div>
                    </div>
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
        if (confirm('¿Crear la solicitud? El stock se reducirá automáticamente.')) {
            @this.crearSolicitud();
        }
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
