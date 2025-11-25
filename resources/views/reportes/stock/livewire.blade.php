<div>
    <!-- Formulario de generación de reporte -->
    <div class="mb-6 overflow-hidden bg-white border rounded-lg shadow-sm border-neutral-200">
        <div class="px-4 py-3 border-b bg-primary-50 border-neutral-200">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-sm font-semibold text-primary-800">Generar Reporte</h3>
            </div>
        </div>

        <div class="p-6">
            <p class="text-sm text-gray-600 mb-4">Este reporte muestra el estado actual del stock de todos los insumos.</p>
            <div class="flex items-center justify-end">
                <button 
                    wire:click="generar"
                    class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-white bg-primary-500 rounded-lg hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-400 transition-all duration-150 shadow-md hover:shadow-lg"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Generar Reporte
                </button>
            </div>
        </div>
    </div>

    <!-- Resultados del Reporte -->
    @if($mostrarResultados)
        <div class="space-y-6">
            <!-- Botones de Exportación -->
            <div class="overflow-hidden bg-white border rounded-lg shadow-sm border-neutral-200">
                <div class="px-4 py-3 border-b bg-primary-50 border-neutral-200 sm:px-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h3 class="text-sm font-semibold text-primary-800">Exportar Reporte</h3>
                            <p class="mt-1 text-xs text-primary-600">Generado el {{ now()->format('d/m/Y H:i:s') }}</p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <form action="{{ route('reportes.stock.exportar.excel') }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="tab" value="{{ $tabActiva }}">
                                <button 
                                    type="submit"
                                    class="inline-flex items-center px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-white bg-success-600 rounded-lg hover:bg-success-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-success-500 transition-all duration-150 shadow-sm"
                                >
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span class="hidden sm:inline">Exportar Excel</span>
                                    <span class="sm:hidden">Excel</span>
                                </button>
                            </form>
                            <form action="{{ route('reportes.stock.exportar.pdf') }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="tab" value="{{ $tabActiva }}">
                                <button 
                                    type="submit"
                                    class="inline-flex items-center px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-white bg-danger-600 rounded-lg hover:bg-danger-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-danger-500 transition-all duration-150 shadow-sm"
                                >
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="hidden sm:inline">Exportar PDF</span>
                                    <span class="sm:hidden">PDF</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas Generales -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Insumos</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $estadisticas['total_insumos'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-red-200 rounded-lg p-4 shadow-sm border-2">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Insumos que Necesitan Reposición</p>
                            <p class="text-2xl font-semibold text-red-600">{{ $estadisticas['insumos_criticos'] ?? 0 }}</p>
                            <p class="text-xs text-gray-500 mt-1">Incluye agotados y stock por debajo del mínimo</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Insumos que Necesitan Reposición -->
            @if(($totalAgotados ?? 0) > 0 || ($totalBajo ?? 0) > 0)
            <div class="w-full overflow-hidden bg-white border rounded-lg shadow-sm border-neutral-200">
                <!-- Pestañas -->
                <div class="border-b border-neutral-200">
                    <nav class="flex -mb-px" aria-label="Tabs">
                        <button 
                            wire:click="cambiarTab('agotados')"
                            class="flex-1 px-4 py-3 text-sm font-medium text-center border-b-2 transition-colors duration-150 {{ $tabActiva === 'agotados' ? 'border-red-500 text-red-600 bg-red-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                        >
                            <div class="flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <span>Agotados</span>
                                @if(($totalAgotados ?? 0) > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $tabActiva === 'agotados' ? 'bg-red-200 text-red-800' : 'bg-gray-200 text-gray-800' }}">
                                        {{ $totalAgotados }}
                                    </span>
                                @endif
                            </div>
                        </button>
                        <button 
                            wire:click="cambiarTab('bajo')"
                            class="flex-1 px-4 py-3 text-sm font-medium text-center border-b-2 transition-colors duration-150 {{ $tabActiva === 'bajo' ? 'border-orange-500 text-orange-600 bg-orange-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                        >
                            <div class="flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <span>Stock Bajo</span>
                                @if(($totalBajo ?? 0) > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $tabActiva === 'bajo' ? 'bg-orange-200 text-orange-800' : 'bg-gray-200 text-gray-800' }}">
                                        {{ $totalBajo }}
                                    </span>
                                @endif
                            </div>
                        </button>
                    </nav>
                </div>
                
                <!-- Contenido de la pestaña activa -->
                @if($insumosCriticos->total() > 0)
                <div class="px-4 py-3 border-b bg-gray-50 border-neutral-200 sm:px-6">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 {{ $tabActiva === 'agotados' ? 'text-red-600' : 'text-orange-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <h3 class="text-sm font-semibold {{ $tabActiva === 'agotados' ? 'text-red-800' : 'text-orange-800' }}">
                            @if($tabActiva === 'agotados')
                                Insumos Agotados
                            @else
                                Insumos con Stock Bajo
                            @endif
                        </h3>
                    </div>
                    <p class="mt-1 text-xs text-gray-600">
                        @if($tabActiva === 'agotados')
                            Insumos sin stock disponible que requieren reposición inmediata
                        @else
                            Insumos con stock por debajo del mínimo requerido
                        @endif
                    </p>
                </div>
                <div class="w-full overflow-x-auto">
                    <table class="w-full divide-y divide-neutral-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-xs font-semibold tracking-wider text-center text-gray-600 uppercase sm:px-6">
                                    <span>Estado</span>
                                </th>
                                <th class="px-4 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase sm:px-6">
                                    <div class="flex items-center space-x-1">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                        <span>Insumo</span>
                                    </div>
                                </th>
                                <th class="px-4 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase sm:px-6">
                                    <div class="flex items-center space-x-1">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                        <span>Tipo</span>
                                    </div>
                                </th>
                                <th class="px-4 py-3 text-xs font-semibold tracking-wider text-right text-gray-600 uppercase sm:px-6">
                                    <div class="flex items-center justify-end space-x-1">
                                        <span>Stock Actual</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-neutral-200">
                            @foreach($insumosCriticos as $insumo)
                                <tr class="transition-colors duration-150 hover:bg-gray-50">
                                    <td class="px-4 py-4 sm:px-6 text-center">
                                        @if($insumo['estado'] === 'agotado')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Agotado
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                Crítico
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 sm:px-6">
                                        <div class="text-sm font-medium text-neutral-900">{{ $insumo['nombre_insumo'] }}</div>
                                    </td>
                                    <td class="px-4 py-4 sm:px-6 whitespace-nowrap">
                                        <div class="text-sm text-neutral-600">{{ $insumo['tipo_insumo']['nombre_tipo'] ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-4 py-4 sm:px-6 whitespace-nowrap">
                                        <div class="text-sm font-medium {{ $insumo['estado'] === 'agotado' ? 'text-red-600' : 'text-orange-600' }} text-right">
                                            {{ $insumo['stock_actual'] }}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Paginación -->
                <div class="px-4 py-3 border-t border-neutral-200 bg-white sm:px-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center gap-2">
                            <label class="text-sm text-gray-700">Mostrar:</label>
                            <select wire:model.live="perPage" class="px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-400">
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <span class="text-sm text-gray-600">por página</span>
                        </div>
                        <div class="text-sm text-gray-700">
                            Mostrando {{ $insumosCriticos->firstItem() ?? 0 }} a {{ $insumosCriticos->lastItem() ?? 0 }} de {{ $insumosCriticos->total() }} insumos
                        </div>
                    </div>
                    <div class="mt-4">
                        {{ $insumosCriticos->links() }}
                    </div>
                </div>
                @else
                <div class="px-4 py-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">
                        @if($tabActiva === 'agotados')
                            No hay insumos agotados
                        @else
                            No hay insumos con stock bajo
                        @endif
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        @if($tabActiva === 'agotados')
                            Todos los insumos tienen stock disponible
                        @else
                            Todos los insumos están por encima del mínimo requerido
                        @endif
                    </p>
                </div>
                @endif
            </div>
            @else
            <div class="w-full overflow-hidden bg-white border rounded-lg shadow-sm border-green-200">
                <div class="px-4 py-3 border-b bg-green-50 border-green-200 sm:px-6">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-sm font-semibold text-green-800">¡Excelente!</h3>
                    </div>
                    <p class="mt-1 text-xs text-green-600">No hay insumos con stock crítico. Todos los insumos están en niveles adecuados.</p>
                </div>
            </div>
            @endif
        </div>
    @endif
</div>

