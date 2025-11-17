<div>
    <!-- Mensajes -->
    @if (session()->has('success'))
        <div class="mb-4 bg-green-50 border-l-4 border-green-400 p-4 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Formulario de generación de reporte -->
    <div class="overflow-hidden bg-white rounded-lg shadow">
        <div class="px-4 py-3 border-b border-gray-200 bg-gray-50 sm:px-6">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-sm font-semibold text-gray-900">Configurar Reporte</h3>
            </div>
        </div>

        <div class="p-4 sm:p-6 space-y-4">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <!-- Tipo de Período -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">
                        Tipo de Período <span class="text-red-500">*</span>
                    </label>
                    <select 
                        wire:model="tipoPeriodo" 
                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-150"
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
                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-150"
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
                    class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-white bg-secondary-500 rounded-lg hover:bg-secondary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-400 transition-all duration-150 shadow-sm"
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
                            <form action="{{ route('reportes.insumos.exportar.excel') }}" method="POST" class="inline">
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
                            <form action="{{ route('reportes.insumos.exportar.pdf') }}" method="POST" class="inline">
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
                <div class="overflow-hidden bg-white rounded-lg shadow">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 w-0 ml-5">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Solicitudes</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $estadisticas['total_solicitudes'] ?? 0 }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden bg-white rounded-lg shadow">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div class="flex-1 w-0 ml-5">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Insumos Solicitados</dt>
                                    <dd class="text-lg font-medium text-green-600">{{ number_format($estadisticas['total_insumos_solicitados'] ?? 0) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden bg-white rounded-lg shadow">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 w-0 ml-5">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Insumos Únicos</dt>
                                    <dd class="text-lg font-medium text-purple-600">{{ $estadisticas['insumos_unicos_solicitados'] ?? 0 }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden bg-white rounded-lg shadow">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 w-0 ml-5">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">No Solicitados</dt>
                                    <dd class="text-lg font-medium text-orange-600">{{ $estadisticas['insumos_no_solicitados'] ?? 0 }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Insumos Más Solicitados -->
            <div class="w-full overflow-hidden bg-white border rounded-lg shadow-sm border-neutral-200">
                <div class="px-4 py-3 border-b bg-primary-50 border-neutral-200 sm:px-6">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <h3 class="text-sm font-semibold text-primary-800">Insumos Más Solicitados</h3>
                    </div>
                    <p class="mt-1 text-xs text-primary-600">Top 10 de insumos con mayor demanda en el período</p>
                </div>
                <div class="w-full overflow-x-auto">
                    <table class="w-full divide-y table-fixed divide-neutral-200" style="min-width: 1000px;">
                        <thead class="bg-primary-100">
                            <tr>
                                <th class="w-1/12 px-3 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase sm:px-6">
                                    <div class="flex items-center space-x-1">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                        <span class="hidden sm:inline">#</span>
                                    </div>
                                </th>
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
                                <th class="w-2/12 px-3 py-4 text-xs font-semibold tracking-wider text-right text-gray-600 uppercase sm:px-6">
                                    <div class="flex items-center justify-end pr-6 space-x-1">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                        <span class="hidden sm:inline">Total Solicitado</span>
                                    </div>
                                </th>
                                <th class="w-2/12 px-3 py-4 text-xs font-semibold tracking-wider text-right text-gray-600 uppercase sm:px-6">
                                    <div class="flex items-center justify-end pr-6 space-x-1">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                        <span class="hidden sm:inline">Veces Solicitado</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-neutral-200">
                            @forelse($insumosMasSolicitados as $index => $insumo)
                                <tr class="transition-colors duration-150 hover:bg-secondary-50">
                                    <td class="w-1/12 px-3 py-4 sm:px-6 whitespace-nowrap">
                                        <div class="text-sm font-medium text-neutral-500">
                                            {{ $index + 1 }}
                                        </div>
                                    </td>
                                    <td class="w-4/12 px-3 py-4 sm:px-6">
                                        <div class="pl-6 text-sm font-medium text-neutral-900">{{ $insumo['nombre_insumo'] }}</div>
                                    </td>
                                    <td class="w-2/12 px-3 py-4 sm:px-6 whitespace-nowrap">
                                        <div class="pl-6 text-sm text-neutral-600">{{ $insumo['nombre_tipo'] ?? 'N/A' }}</div>
                                    </td>
                                    <td class="w-2/12 px-3 py-4 sm:px-6 whitespace-nowrap">
                                        <div class="pr-6 text-sm font-medium text-neutral-900 text-right">{{ number_format($insumo['total_solicitado']) }}</div>
                                    </td>
                                    <td class="w-2/12 px-3 py-4 sm:px-6 whitespace-nowrap">
                                        <div class="pr-6 text-sm text-neutral-600 text-right">{{ $insumo['veces_solicitado'] }}</div>
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

            <!-- Insumos No Solicitados -->
            <div class="w-full overflow-hidden bg-white border rounded-lg shadow-sm border-neutral-200">
                <div class="px-4 py-3 border-b bg-primary-50 border-neutral-200 sm:px-6">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <h3 class="text-sm font-semibold text-primary-800">Insumos No Solicitados</h3>
                    </div>
                    <p class="mt-1 text-xs text-primary-600">Insumos que no fueron solicitados en este período</p>
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
                                <th class="w-2/12 px-3 py-4 text-xs font-semibold tracking-wider text-right text-gray-600 uppercase sm:px-6">
                                    <div class="flex items-center justify-end pr-6 space-x-1">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                        <span class="hidden sm:inline">Stock Actual</span>
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
                            @forelse($insumosNoSolicitados as $insumo)
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
                            @empty
                                <tr>
                                    <td class="px-3 py-12 text-center sm:px-6" colspan="4">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 mb-4 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <p class="text-sm font-medium text-neutral-500">Todos los insumos fueron solicitados en este período</p>
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
