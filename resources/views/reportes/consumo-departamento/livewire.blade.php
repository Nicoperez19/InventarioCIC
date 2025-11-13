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
                            <form action="{{ route('reportes.consumo-departamento.exportar.excel') }}" method="POST" class="inline">
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
                            <form action="{{ route('reportes.consumo-departamento.exportar.pdf') }}" method="POST" class="inline">
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Consumido</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ number_format($estadisticas['total_consumido'] ?? 0) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Departamentos Activos</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $estadisticas['departamentos_activos'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Solicitudes</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $estadisticas['total_solicitudes'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Eficiencia Entrega</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $estadisticas['eficiencia_entrega'] ?? 0 }}%</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Consumo por Departamento -->
            <div class="w-full overflow-hidden bg-white border rounded-lg shadow-sm border-neutral-200">
                <div class="px-4 py-3 border-b bg-primary-50 border-neutral-200 sm:px-6">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <h3 class="text-sm font-semibold text-primary-800">Consumo por Departamento</h3>
                    </div>
                    <p class="mt-1 text-xs text-primary-600">Consumo de insumos por departamento en el período seleccionado</p>
                </div>
                <div class="w-full overflow-x-auto">
                    <table class="w-full divide-y table-fixed divide-neutral-200" style="min-width: 1000px;">
                        <thead class="bg-primary-100">
                            <tr>
                                <th class="w-4/12 px-3 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase sm:px-6">
                                    <div class="flex items-center pl-6 space-x-1">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        <span class="hidden sm:inline">Departamento</span>
                                    </div>
                                </th>
                                <th class="w-2/12 px-3 py-4 text-xs font-semibold tracking-wider text-right text-gray-600 uppercase sm:px-6">
                                    <div class="flex items-center justify-end pr-6 space-x-1">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                        <span class="hidden sm:inline">Total Entregado</span>
                                    </div>
                                </th>
                                <th class="w-2/12 px-3 py-4 text-xs font-semibold tracking-wider text-right text-gray-600 uppercase sm:px-6">
                                    <div class="flex items-center justify-end pr-6 space-x-1">
                                        <span class="hidden sm:inline">Total Solicitado</span>
                                    </div>
                                </th>
                                <th class="w-2/12 px-3 py-4 text-xs font-semibold tracking-wider text-right text-gray-600 uppercase sm:px-6">
                                    <div class="flex items-center justify-end pr-6 space-x-1">
                                        <span class="hidden sm:inline">Solicitudes</span>
                                    </div>
                                </th>
                                <th class="w-2/12 px-3 py-4 text-xs font-semibold tracking-wider text-right text-gray-600 uppercase sm:px-6">
                                    <div class="flex items-center justify-end pr-6 space-x-1">
                                        <span class="hidden sm:inline">Insumos Diferentes</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-neutral-200">
                            @forelse($consumoPorDepartamento as $depto)
                                <tr class="transition-colors duration-150 hover:bg-secondary-50">
                                    <td class="w-4/12 px-3 py-4 sm:px-6">
                                        <div class="pl-6 text-sm font-medium text-neutral-900">{{ $depto['nombre_depto'] }}</div>
                                    </td>
                                    <td class="w-2/12 px-3 py-4 sm:px-6 whitespace-nowrap">
                                        <div class="pr-6 text-sm font-medium text-neutral-900 text-right">{{ number_format($depto['total_entregado']) }}</div>
                                    </td>
                                    <td class="w-2/12 px-3 py-4 sm:px-6 whitespace-nowrap">
                                        <div class="pr-6 text-sm text-neutral-600 text-right">{{ number_format($depto['total_solicitado']) }}</div>
                                    </td>
                                    <td class="w-2/12 px-3 py-4 sm:px-6 whitespace-nowrap">
                                        <div class="pr-6 text-sm text-neutral-600 text-right">{{ $depto['total_solicitudes'] }}</div>
                                    </td>
                                    <td class="w-2/12 px-3 py-4 sm:px-6 whitespace-nowrap">
                                        <div class="pr-6 text-sm text-neutral-600 text-right">{{ $depto['insumos_diferentes'] }}</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="px-3 py-12 text-center sm:px-6" colspan="5">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 mb-4 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <p class="text-sm font-medium text-neutral-500">No hay datos disponibles para este período</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>

