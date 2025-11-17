<div>
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
                @if($search || $unidadFilter || $tipoInsumoFilter || $stockFilter)
                    <button 
                        wire:click="$set('search', ''); $set('unidadFilter', ''); $set('tipoInsumoFilter', ''); $set('stockFilter', '')"
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
        <div class="p-4">
            <!-- Filtros alineados en una sola fila -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                <!-- Búsqueda principal -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-neutral-700">
                        <div class="flex items-center space-x-1">
                            <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span>Buscar</span>
                        </div>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            wire:model.live="search" 
                            placeholder="Nombre, código o ID..." 
                            class="block w-full pl-9 pr-3 py-2.5 border border-neutral-300 rounded-lg bg-white text-neutral-900 placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-150 text-sm"
                        >
                    </div>
                </div>

                <!-- Filtro por Unidad de Medida -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-neutral-700">
                        <div class="flex items-center space-x-1">
                            <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span>Unidad de Medida</span>
                        </div>
                    </label>
                    <select 
                        wire:model.live="unidadFilter" 
                        class="w-full px-3 py-2.5 border border-neutral-300 rounded-lg bg-white text-neutral-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-150 text-sm"
                    >
                        <option value="">Todas las unidades</option>
                        @foreach($unidades as $unidad)
                            <option value="{{ $unidad->id_unidad }}">{{ $unidad->nombre_unidad_medida }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro por Estado de Stock -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-neutral-700">
                        <div class="flex items-center space-x-1">
                            <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span>Estado de Stock</span>
                        </div>
                    </label>
                    <select 
                        wire:model.live="stockFilter" 
                        class="w-full px-3 py-2.5 border border-neutral-300 rounded-lg bg-white text-neutral-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-150 text-sm"
                    >
                        <option value="">Todos los estados</option>
                        <option value="normal">Stock Normal</option>
                        <option value="bajo">Stock Bajo</option>
                        <option value="agotado">Agotado</option>
                    </select>
                </div>
            </div>

        </div>
    </div>

    <!-- Tabla -->
    <div class="w-full overflow-hidden bg-white border rounded-lg shadow-sm border-neutral-200">
        <div class="w-full overflow-x-auto">
        <table class="w-full divide-y table-fixed divide-neutral-200" style="min-width: 1000px;">
        <thead class="bg-primary-100">
            <tr>
                    <th class="w-1/12 px-3 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase sm:px-6">
                        <div class="flex items-center space-x-1">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            <span class="hidden sm:inline">ID</span>
                        </div>
                    </th>
                    <th class="w-3/12 px-3 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase sm:px-6">
                        <button wire:click="sortBy('nombre_insumo')" class="flex items-center pl-6 space-x-1 hover:text-primary-600 transition-colors duration-150 cursor-pointer">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <span class="hidden sm:inline">Insumo</span>
                            @if($sortField === 'nombre_insumo')
                                @if($sortDirection === 'asc')
                                    <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                @endif
                            @else
                                <svg class="w-4 h-4 text-gray-400 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                </svg>
                            @endif
                        </button>
                    </th>
                    <th class="w-2/12 px-3 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase sm:px-6">
                        <div class="flex items-center pl-6 space-x-1">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span class="hidden sm:inline">Unidad</span>
                        </div>
                    </th>
                    <th class="w-1/12 px-3 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase sm:px-6">
                        <button wire:click="sortBy('stock_actual')" class="flex items-center pl-6 space-x-1 hover:text-primary-600 transition-colors duration-150 cursor-pointer">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span class="hidden sm:inline">Stock</span>
                            @if($sortField === 'stock_actual')
                                @if($sortDirection === 'asc')
                                    <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                @endif
                            @else
                                <svg class="w-4 h-4 text-gray-400 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                </svg>
                            @endif
                        </button>
                    </th>
                    <th class="w-2/12 px-3 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase sm:px-6">
                        <div class="flex items-center pl-6 space-x-1">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                            </svg>
                            <span class="hidden sm:inline">Código Barras</span>
                        </div>
                    </th>
                    <th class="w-3/12 px-3 py-4 text-xs font-semibold tracking-wider text-gray-600 uppercase sm:px-6">
                        <div class="flex items-center justify-end pr-6 space-x-1">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                            </svg>
                            <span class="hidden sm:inline">Acciones</span>
                        </div>
                    </th>
            </tr>
        </thead>
            <tbody class="bg-white divide-y divide-neutral-200">
            @forelse($insumos as $insumo)
                    <tr wire:key="insumo-{{ $insumo->id_insumo }}" class="transition-colors duration-150 hover:bg-secondary-50">
                        <!-- ID -->
                        <td class="w-1/12 px-3 py-4 sm:px-6 whitespace-nowrap">
                            <div class="text-sm font-medium text-neutral-500">
                                {{ $insumo->id_insumo }}
                            </div>
                        </td>
                        
                        <!-- Insumo -->
                        <td class="w-3/12 px-3 py-4 sm:px-6">
                            <div class="pl-6 text-sm font-medium text-neutral-900">{{ $insumo->nombre_insumo }}</div>
                        </td>
                        
                        <!-- Unidad -->
                        <td class="w-2/12 px-3 py-4 sm:px-6 whitespace-nowrap">
                            <div class="pl-6 text-sm text-neutral-600">{{ $insumo->unidadMedida->nombre_unidad_medida ?? $insumo->id_unidad }}</div>
                        </td>
                        
                        <!-- Stock Actual -->
                        <td class="w-1/12 px-3 py-4 sm:px-6 whitespace-nowrap">
                            <div class="pl-6 text-sm font-medium">
                                @if($insumo->stock_actual <= 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        {{ $insumo->stock_actual }}
                                    </span>
                                @elseif($insumo->stock_actual <= ($insumo->stock_minimo ?? 0))
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        {{ $insumo->stock_actual }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $insumo->stock_actual }}
                                    </span>
                                @endif
                            </div>
                        </td>
                        
                        <!-- Código Barras -->
                        <td class="w-2/12 px-3 py-4 sm:px-6 whitespace-nowrap">
                            <div class="pl-6">
                                @if($insumo->codigo_barra)
                                    <div class="inline-flex items-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 font-mono">
                                            {{ $insumo->codigo_barra }}
                                        </span>
                                        <button type="button" 
                                                class="p-1 ml-2 text-blue-600 rounded hover:text-blue-800 hover:bg-blue-50"
                                                onclick="openBarcodeModal('{{ $insumo->id_insumo }}', '{{ $insumo->codigo_barra }}')"
                                                title="Ver código de barras">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">Sin código</span>
                                @endif
                            </div>
                        </td>

                        <!-- Acciones -->
                        <td class="w-3/12 px-3 py-4 text-sm font-medium sm:px-6 whitespace-nowrap">
                            <div class="flex items-center justify-end space-x-1 sm:space-x-3">
                                <!-- Botón Ver Código de Barras -->
                                @if($insumo->codigo_barra)
                                    <a href="{{ route('barcode.generate', $insumo->id_insumo) }}" 
                                       target="_blank"
                                       class="inline-flex items-center px-2 py-1 text-xs font-medium transition-all duration-150 border border-transparent rounded-md sm:px-3 sm:py-2 text-success-600 bg-success-50 hover:bg-success-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-success-400"
                                       title="Ver código de barras">
                                        <svg class="w-3 h-3 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <span class="hidden sm:inline">Ver</span>
                                    </a>
                                @endif
                                
                                <!-- Botón Editar -->
                                <a href="{{ route('insumos.edit', $insumo->id_insumo) }}" 
                                   class="inline-flex items-center px-2 py-1 text-xs font-medium transition-all duration-150 border border-transparent rounded-md sm:px-3 sm:py-2 text-primary-600 bg-primary-50 hover:bg-primary-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-400">
                                    <svg class="w-3 h-3 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    <span class="hidden sm:inline">Editar</span>
                                </a>
                                
                                <!-- Botón Eliminar -->
                                <form action="{{ route('insumos.destroy', $insumo->id_insumo) }}" 
                                      method="POST" 
                                      class="inline" 
                                      onsubmit="return confirm('¿Estás seguro de que deseas eliminar el insumo \'{{ $insumo->nombre_insumo }}\'? Esta acción no se puede deshacer.');">
                            @csrf
                            @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center px-2 py-1 text-xs font-medium transition-colors duration-150 border border-transparent rounded-md sm:px-3 sm:py-2 text-danger-600 bg-danger-50 hover:bg-danger-600 hover:text-white active:bg-danger-700 active:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-danger-500">
                                        <svg class="w-3 h-3 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        <span class="hidden sm:inline">Eliminar</span>
                                    </button>
                        </form>
                            </div>
                    </td>
                </tr>
            @empty
                <tr>
                        <td class="px-3 py-12 text-center sm:px-6" colspan="6">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 mb-4 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                @if($search || $unidadFilter || $tipoInsumoFilter || $stockFilter)
                                    <h3 class="mb-2 text-lg font-medium text-neutral-900">No se encontraron insumos</h3>
                                    <p class="text-neutral-500">Intenta ajustar los filtros de búsqueda para ver más resultados.</p>
                                @else
                                    <h3 class="mb-2 text-lg font-medium text-neutral-900">No hay insumos</h3>
                                    <p class="text-neutral-500">Comienza creando tu primer insumo para organizar tu inventario.</p>
                                @endif
                            </div>
                        </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    
    <!-- Paginación -->
    <div class="px-4 py-3 border-t bg-gray-50 border-neutral-200">
        {{ $insumos->links() }}
    </div>

    <!-- Modal para mostrar código de barras -->
<div id="barcodeModal" class="fixed inset-0 z-50 hidden w-full h-full overflow-y-auto bg-gray-600 bg-opacity-50">
    <div class="relative p-5 mx-auto bg-white border rounded-md shadow-lg top-20 w-96">
        <div class="mt-3">
            <!-- Header del modal -->
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Código de Barras</h3>
                <button type="button" 
                        class="text-gray-400 hover:text-gray-600"
                        onclick="closeBarcodeModal()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Contenido del modal -->
            <div class="text-center">
                <!-- Información del insumo -->
                <div class="mb-4">
                    <p class="mb-2 text-sm text-gray-600">Insumo: <span id="modalProductName" class="font-medium"></span></p>
                    <p class="text-sm text-gray-600">Código: <span id="modalBarcode" class="font-mono font-medium text-blue-600"></span></p>
                </div>
                
                <!-- Imagen del código de barras -->
                <div class="p-4 mb-4 bg-white border rounded-lg shadow-sm">
                    <img id="modalBarcodeImage"
                         src=""
                         alt="Código de barras"
                         class="h-auto max-w-full mx-auto"
                         style="max-height: 120px;"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <div id="modalBarcodeFallback" class="text-center text-gray-500" style="display: none;">
                        <div class="flex items-center justify-center w-32 h-16 mx-auto bg-gray-100 border-2 border-gray-300 border-dashed rounded">
                            <span class="text-xs">Error cargando imagen</span>
                        </div>
                    </div>
                </div>
                
                <!-- Botones de acción -->
                <div class="flex justify-center space-x-3">
                    <button type="button"
                            id="modalDownloadBtn"
                            class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Descargar PNG
                    </button>
                    <button type="button"
                            id="modalSvgBtn"
                            class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Ver SVG
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openBarcodeModal(insumoId, codigoBarra) {
    // Mostrar el modal
    document.getElementById('barcodeModal').classList.remove('hidden');
    
    // Actualizar contenido
    document.getElementById('modalBarcode').textContent = codigoBarra;
    document.getElementById('modalProductName').textContent = 'Insumo ' + insumoId;
    
    // Cargar imagen del código de barras
    const imageUrl = `/barcode/${insumoId}/small`;
    document.getElementById('modalBarcodeImage').src = imageUrl;
    
    // Configurar botones de descarga
    document.getElementById('modalDownloadBtn').onclick = function() {
        window.open(`/barcode/${insumoId}/generate`, '_blank');
    };
    
    document.getElementById('modalSvgBtn').onclick = function() {
        window.open(`/barcode/${insumoId}/svg`, '_blank');
    };
}

function closeBarcodeModal() {
    document.getElementById('barcodeModal').classList.add('hidden');
}

// Cerrar modal al hacer clic fuera de él
document.getElementById('barcodeModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeBarcodeModal();
    }
});

// Cerrar modal con tecla Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeBarcodeModal();
    }
});

</script>

</div>