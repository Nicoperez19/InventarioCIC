<div class="w-full bg-white shadow-sm rounded-lg border border-neutral-200 overflow-hidden">
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

    <!-- Filtros y controles -->
    <div class="p-4 border-b border-gray-200 bg-gray-50">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <!-- Filtro por tipo -->
            <div class="flex items-center space-x-4">
                <label for="tipo-filtro" class="text-sm font-medium text-gray-700">Filtrar por tipo:</label>
                <select wire:model.live="tipoInsumoFiltro" id="tipo-filtro" 
                        class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos los tipos</option>
                    @foreach($tiposDisponibles as $tipo)
                        <option value="{{ $tipo->id }}">{{ $tipo->nombre_tipo }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Botones de acción -->
            <div class="flex items-center space-x-3">
                <button wire:click="limpiarSolicitud" 
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Limpiar
                </button>
                    <button wire:click="crearSolicitud" 
                            wire:confirm="¿Crear la solicitud? El stock se reducirá automáticamente."
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Crear Solicitud
                    </button>
            </div>
        </div>
    </div>

    <!-- Tabla de insumos -->
    <div class="w-full overflow-x-auto">
        <table class="w-full divide-y divide-neutral-200">
            <thead class="bg-gray-50">
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
                    <tr class="hover:bg-blue-50/30 transition-colors duration-150">
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
                            <div class="flex items-center justify-center">
                                <input type="number" 
                                       wire:model.live.debounce.200ms="cantidades.{{ $insumo->id_insumo }}"
                                       wire:change="actualizarCantidad('{{ $insumo->id_insumo }}', $event.target.value)"
                                       class="w-20 px-2 py-1 text-xs font-medium text-center border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       min="0"
                                       max="{{ $insumo->stock_actual }}"
                                       step="1"
                                       value="{{ $cantidades[$insumo->id_insumo] ?? 0 }}">
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
