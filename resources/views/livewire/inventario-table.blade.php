
<div>
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <div class="flex-1 max-w-md">
                <input wire:model.live="search" type="text" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                       placeholder="Buscar producto...">
            </div>

            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        class="flex items-center px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Filtros
                    <svg class="w-4 h-4 ml-2" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="open" @click.away="open = false" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-96 max-w-sm bg-white rounded-lg shadow-xl border border-gray-200 z-50"
                     style="max-height: 80vh; overflow-y: auto;">
                    
                    <div class="p-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Filtros de Inventario</h3>
                        
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Productos</h4>
                            <div class="max-h-40 overflow-y-auto space-y-2 border border-gray-200 rounded p-2">
                                @forelse($productos as $producto)
                                    <label class="flex items-center hover:bg-gray-50 p-1 rounded">
                                        <input type="checkbox" 
                                               wire:model.live="productoFilter" 
                                               value="{{ $producto->id_producto }}"
                                               class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                                        <span class="ml-2 text-sm text-gray-700 truncate">{{ $producto->nombre_producto }}</span>
                                    </label>
                                @empty
                                    <p class="text-sm text-gray-500">No hay productos disponibles</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Unidades</h4>
                            <div class="space-y-2 border border-gray-200 rounded p-2">
                                @forelse($unidades as $unidad)
                                    <label class="flex items-center hover:bg-gray-50 p-1 rounded">
                                        <input type="checkbox" 
                                               wire:model.live="unidadFilter" 
                                               value="{{ $unidad->id_unidad }}"
                                               class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                                        <span class="ml-2 text-sm text-gray-700">{{ $unidad->nombre_unidad }}</span>
                                    </label>
                                @empty
                                    <p class="text-sm text-gray-500">No hay unidades disponibles</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Departamentos</h4>
                            <div class="space-y-2 border border-gray-200 rounded p-2">
                                @forelse($departamentos as $departamento)
                                    <label class="flex items-center hover:bg-gray-50 p-1 rounded">
                                        <input type="checkbox" 
                                               wire:model.live="departamentoFilter" 
                                               value="{{ $departamento->id_depto }}"
                                               class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                                        <span class="ml-2 text-sm text-gray-700">{{ $departamento->nombre_depto }}</span>
                                    </label>
                                @empty
                                    <p class="text-sm text-gray-500">No hay departamentos disponibles</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="flex justify-between pt-4 border-t border-gray-200">
                            <button wire:click="$set('productoFilter', []); $set('unidadFilter', []); $set('departamentoFilter', [])"
                                    class="px-3 py-1 text-sm text-gray-600 hover:text-gray-800 underline">
                                Limpiar Todo
                            </button>
                            <button @click="open = false" 
                                    class="px-4 py-2 bg-teal-600 text-white text-sm rounded-md hover:bg-teal-700">
                                Aplicar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unidad</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad Actual</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($inventarios as $inventario)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $inventario->producto->nombre_producto }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $inventario->producto->unidad->nombre_unidad }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $inventario->cantidad_inventario }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <button wire:click="decrementarCantidad({{ $inventario->id_inventario }})"
                                        class="inline-flex items-center px-2 py-1 border border-red-300 rounded-md text-red-700 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-500"
                                        @if($inventario->cantidad_inventario <= 0) disabled @endif>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                </button>

                                <span class="px-3 py-1 bg-gray-100 rounded-md text-sm font-medium min-w-[3rem] text-center">
                                    {{ $inventario->cantidad_inventario }}
                                </span>

                                <button wire:click="incrementarCantidad({{ $inventario->id_inventario }})"
                                        class="inline-flex items-center px-2 py-1 border border-green-300 rounded-md text-green-700 bg-green-50 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                            No se encontraron productos en inventario.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4">
        {{ $inventarios->links() }}
    </div>
</div>
