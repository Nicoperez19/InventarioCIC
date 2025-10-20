<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-dark-teal rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h2 class="text-xl font-semibold leading-tight text-gray-800">
                        {{ __('Discrepancias de Inventario') }}
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">Revisa y corrige las diferencias entre el inventario registrado y el stock actual</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('inventario.index') }}" 
                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-dark-teal transition-colors duration-150 shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver al Inventario
                </a>
                @if($discrepancies->count() > 0)
                    <form action="{{ route('inventario.apply-all-discrepancies') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-dark-teal rounded-lg hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-dark-teal transition-colors duration-150 shadow-sm"
                                onclick="return confirm('¿Estás seguro de que deseas aplicar todas las discrepancias? Esta acción actualizará el stock de todos los productos.')">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Aplicar Todas
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
            <div class="p-4 bg-white shadow sm:p-8 sm:rounded-lg">
                @if($discrepancies->count() > 0)
                    <div class="w-full bg-white shadow-sm rounded-lg border border-neutral-200 overflow-hidden">
                        <!-- Tabla -->
                        <div class="w-full overflow-x-auto">
                            <table class="w-full table-fixed divide-y divide-neutral-200">
                            <thead class="bg-gray-50">
                                <tr>
                                        <th class="w-1/12 px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            <div class="flex items-center space-x-1">
                                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                                </svg>
                                                <span class="hidden sm:inline">ID</span>
                                            </div>
                                        </th>
                                        <th class="w-4/12 px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            <div class="flex items-center space-x-1 pl-6">
                                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                </svg>
                                                <span class="hidden sm:inline">Producto</span>
                                            </div>
                                        </th>
                                        <th class="w-2/12 px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            <div class="flex items-center space-x-1 pl-6">
                                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                                </svg>
                                                <span class="hidden sm:inline">Stock Actual</span>
                                            </div>
                                        </th>
                                        <th class="w-2/12 px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            <div class="flex items-center space-x-1 pl-6">
                                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                                </svg>
                                                <span class="hidden sm:inline">Inventario</span>
                                            </div>
                                        </th>
                                        <th class="w-2/12 px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            <div class="flex items-center space-x-1 pl-6">
                                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                                </svg>
                                                <span class="hidden sm:inline">Diferencia</span>
                                            </div>
                                        </th>
                                        <th class="w-1/12 px-3 sm:px-6 py-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            <div class="flex items-center justify-end space-x-1 pr-6">
                                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                                                </svg>
                                                <span class="hidden sm:inline">Acciones</span>
                                            </div>
                                        </th>
                                </tr>
                            </thead>
                                <tbody class="bg-white divide-y divide-neutral-200">
                                @foreach($discrepancies as $inventario)
                                        <tr class="hover:bg-neutral-200 transition-colors duration-150">
                                            <td class="w-1/12 px-3 sm:px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-neutral-500">
                                                    {{ $inventario->id_inventario }}
                                                </div>
                                            </td>
                                            <td class="w-4/12 px-3 sm:px-6 py-4">
                                                <div class="text-sm font-medium text-neutral-900 pl-6">{{ $inventario->producto->nombre_producto }}</div>
                                            </td>
                                            <td class="w-2/12 px-3 sm:px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-neutral-600 pl-6">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ $inventario->producto->stock_actual }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="w-2/12 px-3 sm:px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-neutral-600 pl-6">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        {{ $inventario->cantidad_inventario }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="w-2/12 px-3 sm:px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm pl-6">
                                                    @if($inventario->isOverstock())
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                            </svg>
                                                            +{{ $inventario->diferencia_stock }}
                                                        </span>
                                                    @elseif($inventario->isUnderstock())
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                            </svg>
                                                            {{ $inventario->diferencia_stock }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="w-1/12 px-3 sm:px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex items-center justify-end space-x-1 sm:space-x-3">
                                                    <!-- Botón Aplicar -->
                                                    <form action="{{ route('inventario.apply', $inventario->id_inventario) }}" 
                                                          method="POST" 
                                                          class="inline">
                                                @csrf
                                                        <button type="submit" 
                                                                class="inline-flex items-center px-2 sm:px-3 py-1 sm:py-2 border border-transparent text-xs font-medium rounded-md text-green-600 bg-green-50 hover:bg-green-600 hover:text-white active:bg-green-700 active:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-150"
                                                                onclick="return confirm('¿Estás seguro de que deseas aplicar esta discrepancia? El stock del producto se actualizará.')">
                                                            <svg class="w-3 h-3 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                            </svg>
                                                            <span class="hidden sm:inline">Aplicar</span>
                                                        </button>
                                            </form>
                                                </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="flex flex-col items-center">
                            <svg class="w-12 h-12 text-neutral-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-neutral-900 mb-2">No hay discrepancias</h3>
                            <p class="text-neutral-500">El inventario está actualizado y no se encontraron diferencias con el stock registrado.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
