<div>
    <!-- Formulario de generación de reporte -->
    <div class="mb-6 overflow-hidden bg-white border rounded-lg shadow-sm border-neutral-200">
        <div class="px-4 py-3 border-b bg-primary-50 border-neutral-200">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-sm font-semibold text-primary-800">Configurar Reporte</h3>
            </div>
        </div>

        <div class="p-6 space-y-4">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <!-- Tipo de Período -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">
                        Tipo de Período <span class="text-red-500">*</span>
                    </label>
                    <select 
                        wire:model="tipoPeriodo" 
                        class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-150"
                    >
                        <option value="semanal">Semanal</option>
                        <option value="mensual">Mensual</option>
                        <option value="semestral">Semestral</option>
                        <option value="anual">Anual</option>
                    </select>
                    @error('tipoPeriodo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fecha de Referencia -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">
                        Fecha de Referencia (Opcional)
                    </label>
                    <input 
                        type="date" 
                        wire:model="fechaReferencia"
                        class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-150"
                    >
                    <p class="mt-1 text-xs text-gray-500">Si no se especifica, se usará la fecha actual</p>
                    @error('fechaReferencia')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
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
            <!-- Información del Período -->
            <div class="overflow-hidden bg-white border rounded-lg shadow-sm border-neutral-200">
                <div class="px-4 py-3 border-b bg-primary-50 border-neutral-200 sm:px-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h3 class="text-sm font-semibold text-primary-800">Período del Reporte</h3>
                            <p class="mt-1 text-xs text-primary-600">
                                {{ \Carbon\Carbon::createFromFormat('Y-m-d', $fechaInicio)->format('d/m/Y') }} - 
                                {{ \Carbon\Carbon::createFromFormat('Y-m-d', $fechaFin)->format('d/m/Y') }}
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <form action="{{ route('reportes.rotacion.exportar.excel') }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="tipo_periodo" value="{{ $tipoPeriodo }}">
                                @if($fechaReferencia)
                                    <input type="hidden" name="fecha_referencia" value="{{ $fechaReferencia }}">
                                @endif
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
                            <form action="{{ route('reportes.rotacion.exportar.pdf') }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="tipo_periodo" value="{{ $tipoPeriodo }}">
                                @if($fechaReferencia)
                                    <input type="hidden" name="fecha_referencia" value="{{ $fechaReferencia }}">
                                @endif
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
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Alta Rotación</p>
                            <p class="text-2xl font-semibold text-green-600">{{ $estadisticas['alta_rotacion'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Baja Rotación</p>
                            <p class="text-2xl font-semibold text-orange-600">{{ $estadisticas['baja_rotacion'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Sin Rotación</p>
                            <p class="text-2xl font-semibold text-red-600">{{ $estadisticas['insumos_sin_rotacion'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Insumos con Alta Rotación -->
            @if(count($altaRotacion) > 0)
            <div class="w-full overflow-hidden bg-white border rounded-lg shadow-sm border-neutral-200">
                <div class="px-4 py-3 border-b bg-primary-50 border-neutral-200 sm:px-6">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        <h3 class="text-sm font-semibold text-primary-800">Insumos con Alta Rotación</h3>
                    </div>
                    <p class="mt-1 text-xs text-primary-600">Insumos con rotación mayor o igual a 2 veces en el período</p>
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
                                        <span class="hidden sm:inline">Stock</span>
                                    </div>
                                </th>
                                <th class="w-1/12 px-3 py-4 text-xs font-semibold tracking-wider text-right text-gray-600 uppercase sm:px-6">
                                    <div class="flex items-center justify-end pr-6 space-x-1">
                                        <span class="hidden sm:inline">Consumo</span>
                                    </div>
                                </th>
                                <th class="w-1/12 px-3 py-4 text-xs font-semibold tracking-wider text-right text-gray-600 uppercase sm:px-6">
                                    <div class="flex items-center justify-end pr-6 space-x-1">
                                        <span class="hidden sm:inline">Rotación</span>
                                    </div>
                                </th>
                                <th class="w-1/12 px-3 py-4 text-xs font-semibold tracking-wider text-right text-gray-600 uppercase sm:px-6">
                                    <div class="flex items-center justify-end pr-6 space-x-1">
                                        <span class="hidden sm:inline">Días</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-neutral-200">
                            @foreach($altaRotacion as $item)
                                <tr class="transition-colors duration-150 hover:bg-secondary-50">
                                    <td class="w-4/12 px-3 py-4 sm:px-6">
                                        <div class="pl-6 text-sm font-medium text-neutral-900">{{ $item['nombre_insumo'] }}</div>
                                    </td>
                                    <td class="w-2/12 px-3 py-4 sm:px-6 whitespace-nowrap">
                                        <div class="pl-6 text-sm text-neutral-600">{{ $item['tipo_insumo'] }}</div>
                                    </td>
                                    <td class="w-1/12 px-3 py-4 sm:px-6 whitespace-nowrap">
                                        <div class="pr-6 text-sm text-neutral-600 text-right">{{ $item['stock_actual'] }}</div>
                                    </td>
                                    <td class="w-1/12 px-3 py-4 sm:px-6 whitespace-nowrap">
                                        <div class="pr-6 text-sm text-neutral-600 text-right">{{ number_format($item['consumo_total']) }}</div>
                                    </td>
                                    <td class="w-1/12 px-3 py-4 sm:px-6 whitespace-nowrap">
                                        <div class="pr-6 text-sm font-semibold text-green-600 text-right">{{ $item['rotacion'] }}</div>
                                    </td>
                                    <td class="w-1/12 px-3 py-4 sm:px-6 whitespace-nowrap">
                                        <div class="pr-6 text-sm text-neutral-600 text-right">{{ $item['dias_rotacion'] }}</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Insumos con Baja Rotación -->
            @if(count($bajaRotacion) > 0)
            <div class="w-full overflow-hidden bg-white border rounded-lg shadow-sm border-neutral-200">
                <div class="px-4 py-3 border-b bg-primary-50 border-neutral-200 sm:px-6">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <h3 class="text-sm font-semibold text-primary-800">Insumos con Baja Rotación</h3>
                    </div>
                    <p class="mt-1 text-xs text-primary-600">Insumos con rotación menor a 0.5 y más de 60 días de rotación</p>
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
                                        <span class="hidden sm:inline">Stock</span>
                                    </div>
                                </th>
                                <th class="w-1/12 px-3 py-4 text-xs font-semibold tracking-wider text-right text-gray-600 uppercase sm:px-6">
                                    <div class="flex items-center justify-end pr-6 space-x-1">
                                        <span class="hidden sm:inline">Consumo</span>
                                    </div>
                                </th>
                                <th class="w-1/12 px-3 py-4 text-xs font-semibold tracking-wider text-right text-gray-600 uppercase sm:px-6">
                                    <div class="flex items-center justify-end pr-6 space-x-1">
                                        <span class="hidden sm:inline">Rotación</span>
                                    </div>
                                </th>
                                <th class="w-1/12 px-3 py-4 text-xs font-semibold tracking-wider text-right text-gray-600 uppercase sm:px-6">
                                    <div class="flex items-center justify-end pr-6 space-x-1">
                                        <span class="hidden sm:inline">Días</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-neutral-200">
                            @foreach($bajaRotacion as $item)
                                <tr class="transition-colors duration-150 hover:bg-secondary-50">
                                    <td class="w-4/12 px-3 py-4 sm:px-6">
                                        <div class="pl-6 text-sm font-medium text-neutral-900">{{ $item['nombre_insumo'] }}</div>
                                    </td>
                                    <td class="w-2/12 px-3 py-4 sm:px-6 whitespace-nowrap">
                                        <div class="pl-6 text-sm text-neutral-600">{{ $item['tipo_insumo'] }}</div>
                                    </td>
                                    <td class="w-1/12 px-3 py-4 sm:px-6 whitespace-nowrap">
                                        <div class="pr-6 text-sm text-neutral-600 text-right">{{ $item['stock_actual'] }}</div>
                                    </td>
                                    <td class="w-1/12 px-3 py-4 sm:px-6 whitespace-nowrap">
                                        <div class="pr-6 text-sm text-neutral-600 text-right">{{ number_format($item['consumo_total']) }}</div>
                                    </td>
                                    <td class="w-1/12 px-3 py-4 sm:px-6 whitespace-nowrap">
                                        <div class="pr-6 text-sm font-semibold text-orange-600 text-right">{{ $item['rotacion'] }}</div>
                                    </td>
                                    <td class="w-1/12 px-3 py-4 sm:px-6 whitespace-nowrap">
                                        <div class="pr-6 text-sm text-neutral-600 text-right">{{ $item['dias_rotacion'] }}</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Insumos Sin Rotación -->
            @if(count($sinRotacion) > 0)
            <div class="w-full overflow-hidden bg-white border rounded-lg shadow-sm border-neutral-200">
                <div class="px-4 py-3 border-b bg-primary-50 border-neutral-200 sm:px-6">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <h3 class="text-sm font-semibold text-primary-800">Insumos Sin Rotación</h3>
                    </div>
                    <p class="mt-1 text-xs text-primary-600">Insumos que no fueron solicitados en el período pero tienen stock</p>
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
                                <th class="w-2/12 px-3 py-4 text-xs font-semibold tracking-wider text-right text-gray-600 uppercase sm:px-6">
                                    <div class="flex items-center justify-end pr-6 space-x-1">
                                        <span class="hidden sm:inline">Stock Actual</span>
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
                            @foreach($sinRotacion as $insumo)
                                <tr class="transition-colors duration-150 hover:bg-secondary-50">
                                    <td class="w-4/12 px-3 py-4 sm:px-6">
                                        <div class="pl-6 text-sm font-medium text-neutral-900">{{ $insumo['nombre_insumo'] }}</div>
                                    </td>
                                    <td class="w-2/12 px-3 py-4 sm:px-6 whitespace-nowrap">
                                        <div class="pl-6 text-sm text-neutral-600">{{ $insumo['tipo_insumo']['nombre_tipo'] ?? 'N/A' }}</div>
                                    </td>
                                    <td class="w-2/12 px-3 py-4 sm:px-6 whitespace-nowrap">
                                        <div class="pr-6 text-sm font-medium text-neutral-900 text-right">{{ number_format($insumo['stock_actual']) }}</div>
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

