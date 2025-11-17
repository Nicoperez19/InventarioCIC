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
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
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

                <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Stock Crítico</p>
                            <p class="text-2xl font-semibold text-red-600">{{ $estadisticas['stock_critico'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Stock Agotado</p>
                            <p class="text-2xl font-semibold text-orange-600">{{ $estadisticas['stock_agotado'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Stock Normal</p>
                            <p class="text-2xl font-semibold text-green-600">{{ $estadisticas['stock_normal'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Insumos que Necesitan Reposición -->
            @if(count($necesitanReposicion) > 0)
            <div class="w-full overflow-hidden bg-white border rounded-lg shadow-sm border-neutral-200">
                <div class="px-4 py-3 border-b bg-primary-50 border-neutral-200 sm:px-6">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <h3 class="text-sm font-semibold text-primary-800">Insumos que Necesitan Reposición</h3>
                    </div>
                    <p class="mt-1 text-xs text-primary-600">Insumos con stock por debajo del mínimo requerido</p>
                </div>
                <div class="w-full overflow-x-auto">
                    <table class="w-full divide-y table-fixed divide-neutral-200" style="min-width: 1000px;">
                        <thead class="bg-primary-100">
                            <tr>
                                <th class="w-4/12 px-3 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase sm:px-6">
                                    <div class="flex items-center pl-6 space-x-1">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                        <span class="hidden sm:inline">Insumo</span>
                                    </div>
                                </th>
                                <th class="w-2/12 px-3 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase sm:px-6">
                                    <div class="flex items-center pl-6 space-x-1">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                        <span class="hidden sm:inline">Tipo</span>
                                    </div>
                                </th>
                                <th class="w-1/12 px-3 py-4 text-xs font-semibold tracking-wider text-right text-gray-600 uppercase sm:px-6">
                                    <div class="flex items-center justify-end pr-6 space-x-1">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                        <span class="hidden sm:inline">Stock Actual</span>
                                    </div>
                                </th>
                                <th class="w-1/12 px-3 py-4 text-xs font-semibold tracking-wider text-right text-gray-600 uppercase sm:px-6">
                                    <div class="flex items-center justify-end pr-6 space-x-1">
                                        <span class="hidden sm:inline">Stock Mínimo</span>
                                    </div>
                                </th>
                                <th class="w-1/12 px-3 py-4 text-xs font-semibold tracking-wider text-right text-gray-600 uppercase sm:px-6">
                                    <div class="flex items-center justify-end pr-6 space-x-1">
                                        <span class="hidden sm:inline">Faltante</span>
                                    </div>
                                </th>
                                <th class="w-2/12 px-3 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase sm:px-6">
                                    <div class="flex items-center pl-6 space-x-1">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        <span class="hidden sm:inline">Unidad</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-neutral-200">
                            @foreach($necesitanReposicion as $insumo)
                                <tr class="transition-colors duration-150 hover:bg-secondary-50">
                                    <td class="w-4/12 px-3 py-4 sm:px-6">
                                        <div class="pl-6 text-sm font-medium text-neutral-900">{{ $insumo['nombre_insumo'] }}</div>
                                    </td>
                                    <td class="w-2/12 px-3 py-4 sm:px-6 whitespace-nowrap">
                                        <div class="pl-6 text-sm text-neutral-600">{{ $insumo['tipo_insumo']['nombre_tipo'] ?? 'N/A' }}</div>
                                    </td>
                                    <td class="w-1/12 px-3 py-4 sm:px-6 whitespace-nowrap">
                                        <div class="pr-6 text-sm font-medium text-red-600 text-right">{{ $insumo['stock_actual'] }}</div>
                                    </td>
                                    <td class="w-1/12 px-3 py-4 sm:px-6 whitespace-nowrap">
                                        <div class="pr-6 text-sm text-neutral-600 text-right">{{ $insumo['stock_minimo'] }}</div>
                                    </td>
                                    <td class="w-1/12 px-3 py-4 sm:px-6 whitespace-nowrap">
                                        <div class="pr-6 text-sm font-semibold text-red-600 text-right">{{ $insumo['cantidad_faltante'] }}</div>
                                    </td>
                                    <td class="w-2/12 px-3 py-4 sm:px-6 whitespace-nowrap">
                                        <div class="pl-6 text-sm text-neutral-600">{{ $insumo['unidad_medida']['nombre_unidad_medida'] ?? 'N/A' }}</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Insumos Agotados -->
            @if(count($stockAgotado) > 0)
            <div class="w-full overflow-hidden bg-white border rounded-lg shadow-sm border-neutral-200">
                <div class="px-4 py-3 border-b bg-primary-50 border-neutral-200 sm:px-6">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <h3 class="text-sm font-semibold text-primary-800">Insumos con Stock Agotado</h3>
                    </div>
                    <p class="mt-1 text-xs text-primary-600">Insumos sin stock disponible</p>
                </div>
                <div class="w-full overflow-x-auto">
                    <table class="w-full divide-y table-fixed divide-neutral-200" style="min-width: 1000px;">
                        <thead class="bg-primary-100">
                            <tr>
                                <th class="w-4/12 px-3 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase sm:px-6">
                                    <div class="flex items-center pl-6 space-x-1">
                                        <span class="hidden sm:inline">Insumo</span>
                                    </div>
                                </th>
                                <th class="w-2/12 px-3 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase sm:px-6">
                                    <div class="flex items-center pl-6 space-x-1">
                                        <span class="hidden sm:inline">Tipo</span>
                                    </div>
                                </th>
                                <th class="w-1/12 px-3 py-4 text-xs font-semibold tracking-wider text-right text-gray-600 uppercase sm:px-6">
                                    <div class="flex items-center justify-end pr-6 space-x-1">
                                        <span class="hidden sm:inline">Stock Mínimo</span>
                                    </div>
                                </th>
                                <th class="w-2/12 px-3 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase sm:px-6">
                                    <div class="flex items-center pl-6 space-x-1">
                                        <span class="hidden sm:inline">Unidad</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-neutral-200">
                            @foreach($stockAgotado as $insumo)
                                <tr class="transition-colors duration-150 hover:bg-secondary-50">
                                    <td class="w-4/12 px-3 py-4 sm:px-6">
                                        <div class="pl-6 text-sm font-medium text-neutral-900">{{ $insumo['nombre_insumo'] }}</div>
                                    </td>
                                    <td class="w-2/12 px-3 py-4 sm:px-6 whitespace-nowrap">
                                        <div class="pl-6 text-sm text-neutral-600">{{ $insumo['tipo_insumo']['nombre_tipo'] ?? 'N/A' }}</div>
                                    </td>
                                    <td class="w-1/12 px-3 py-4 sm:px-6 whitespace-nowrap">
                                        <div class="pr-6 text-sm text-neutral-600 text-right">{{ $insumo['stock_minimo'] }}</div>
                                    </td>
                                    <td class="w-2/12 px-3 py-4 sm:px-6 whitespace-nowrap">
                                        <div class="pl-6 text-sm text-neutral-600">{{ $insumo['unidad_medida']['nombre_unidad_medida'] ?? 'N/A' }}</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    @endif
</div>

