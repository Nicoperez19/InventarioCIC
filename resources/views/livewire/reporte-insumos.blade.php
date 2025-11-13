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
                    <label class="block mb-2 text-sm font-medium text-neutral-700">
                        Tipo de Período <span class="text-red-500">*</span>
                    </label>
                    <select 
                        wire:model="tipoPeriodo" 
                        class="block w-full px-3 py-2.5 border border-neutral-300 rounded-lg bg-white text-neutral-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-150"
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
                    <label class="block mb-2 text-sm font-medium text-neutral-700">
                        Fecha de Referencia (Opcional)
                    </label>
                    <input 
                        type="date" 
                        wire:model="fechaReferencia"
                        class="block w-full px-3 py-2.5 border border-neutral-300 rounded-lg bg-white text-neutral-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-150"
                    >
                    <p class="mt-1 text-xs text-neutral-500">Si no se especifica, se usará la fecha actual</p>
                    @error('fechaReferencia')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-neutral-200">
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
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-blue-800">Período del Reporte</h3>
                        <p class="mt-1 text-sm text-blue-600">
                            {{ \Carbon\Carbon::createFromFormat('Y-m-d', $fechaInicio)->format('d/m/Y') }} - 
                            {{ \Carbon\Carbon::createFromFormat('Y-m-d', $fechaFin)->format('d/m/Y') }}
                        </p>
                    </div>
                    <div class="flex space-x-2">
                        <form action="{{ route('reportes.exportar.excel') }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="tipo_periodo" value="{{ $tipoPeriodo }}">
                            @if($fechaReferencia)
                                <input type="hidden" name="fecha_referencia" value="{{ $fechaReferencia }}">
                            @endif
                            <button 
                                type="submit"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Exportar Excel
                            </button>
                        </form>
                        <form action="{{ route('reportes.exportar.pdf') }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="tipo_periodo" value="{{ $tipoPeriodo }}">
                            @if($fechaReferencia)
                                <input type="hidden" name="fecha_referencia" value="{{ $fechaReferencia }}">
                            @endif
                            <button 
                                type="submit"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                Exportar PDF
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Estadísticas Generales -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                <div class="bg-white border border-neutral-200 rounded-lg p-4 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-neutral-600">Total Solicitudes</p>
                            <p class="text-2xl font-semibold text-neutral-900">{{ $estadisticas['total_solicitudes'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-neutral-200 rounded-lg p-4 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-neutral-600">Total Insumos Solicitados</p>
                            <p class="text-2xl font-semibold text-neutral-900">{{ number_format($estadisticas['total_insumos_solicitados'] ?? 0) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-neutral-200 rounded-lg p-4 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-neutral-600">Insumos Únicos</p>
                            <p class="text-2xl font-semibold text-neutral-900">{{ $estadisticas['insumos_unicos_solicitados'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-neutral-200 rounded-lg p-4 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-neutral-600">No Solicitados</p>
                            <p class="text-2xl font-semibold text-neutral-900">{{ $estadisticas['insumos_no_solicitados'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Insumos Más Solicitados -->
            <div class="bg-white border border-neutral-200 rounded-lg shadow-sm overflow-hidden">
                <div class="px-4 py-3 bg-green-50 border-b border-neutral-200">
                    <h3 class="text-lg font-semibold text-green-800">Insumos Más Solicitados</h3>
                    <p class="text-sm text-green-600 mt-1">Top 10 de insumos con mayor demanda</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-neutral-200">
                        <thead class="bg-neutral-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">#</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Insumo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">Total Solicitado</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">Veces Solicitado</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-neutral-200">
                            @forelse($insumosMasSolicitados as $index => $insumo)
                                <tr class="hover:bg-neutral-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900">
                                        {{ $insumo['nombre_insumo'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                                        {{ $insumo['nombre_tipo'] ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900 text-right font-semibold">
                                        {{ number_format($insumo['total_solicitado']) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600 text-right">
                                        {{ $insumo['veces_solicitado'] }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-neutral-500">
                                        No hay datos disponibles para este período
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Insumos No Solicitados -->
            <div class="bg-white border border-neutral-200 rounded-lg shadow-sm overflow-hidden">
                <div class="px-4 py-3 bg-orange-50 border-b border-neutral-200">
                    <h3 class="text-lg font-semibold text-orange-800">Insumos No Solicitados</h3>
                    <p class="text-sm text-orange-600 mt-1">Insumos que no fueron solicitados en este período</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-neutral-200">
                        <thead class="bg-neutral-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Insumo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">Stock Actual</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Unidad</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-neutral-200">
                            @forelse($insumosNoSolicitados as $insumo)
                                <tr class="hover:bg-neutral-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900">
                                        {{ $insumo['nombre_insumo'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                                        {{ $insumo['tipo_insumo']['nombre_tipo'] ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900 text-right font-semibold">
                                        {{ number_format($insumo['stock_actual']) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                                        {{ $insumo['unidad_medida']['nombre_unidad_medida'] ?? 'N/A' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-sm text-neutral-500">
                                        Todos los insumos fueron solicitados en este período
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

