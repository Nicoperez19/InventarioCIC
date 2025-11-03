<div>
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
                @if($search || $estadoFiltro || $departamentoFiltro || $fechaDesde || $fechaHasta)
                    <button 
                        wire:click="$set('search', ''); $set('estadoFiltro', ''); $set('departamentoFiltro', ''); $set('fechaDesde', ''); $set('fechaHasta', '')"
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
        <div class="p-4 space-y-4">
            <!-- Primera fila: Búsqueda principal -->
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
                        <svg class="w-5 h-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search" 
                        id="search"
                        placeholder="Buscar por número de solicitud o usuario..." 
                        class="block w-full pl-10 pr-3 py-2.5 border border-neutral-300 rounded-lg bg-white text-neutral-900 placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-150"
                    >
                </div>
            </div>

            <!-- Segunda fila: Filtros principales -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                <!-- Filtro por Estado -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-neutral-700">
                        <div class="flex items-center space-x-1">
                            <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Estado</span>
                        </div>
                    </label>
                    <select 
                        wire:model.live="estadoFiltro" 
                        id="estado"
                        class="w-full px-3 py-2.5 border border-neutral-300 rounded-lg bg-white text-neutral-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-150"
                    >
                        <option value="">Todos los estados</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="aprobada">Aprobada</option>
                        <option value="rechazada">Rechazada</option>
                        <option value="entregada">Entregada</option>
                    </select>
                </div>

                <!-- Filtro por Departamento -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-neutral-700">
                        <div class="flex items-center space-x-1">
                            <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span>Departamento</span>
                        </div>
                    </label>
                    <select 
                        wire:model.live="departamentoFiltro" 
                        id="departamento"
                        class="w-full px-3 py-2.5 border border-neutral-300 rounded-lg bg-white text-neutral-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-150"
                    >
                        <option value="">Todos los departamentos</option>
                        @foreach(\App\Models\Departamento::orderBy('nombre_depto')->get() as $departamento)
                            <option value="{{ $departamento->id_depto }}">{{ $departamento->nombre_depto }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro por Fecha Desde -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-neutral-700">
                        <div class="flex items-center space-x-1">
                            <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>Fecha Desde</span>
                        </div>
                    </label>
                    <input 
                        type="date" 
                        wire:model.live="fechaDesde" 
                        id="fecha-desde"
                        class="w-full px-3 py-2.5 border border-neutral-300 rounded-lg bg-white text-neutral-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-150"
                    >
                </div>

                <!-- Filtro por Fecha Hasta -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-neutral-700">
                        <div class="flex items-center space-x-1">
                            <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>Fecha Hasta</span>
                        </div>
                    </label>
                    <input 
                        type="date" 
                        wire:model.live="fechaHasta" 
                        id="fecha-hasta"
                        class="w-full px-3 py-2.5 border border-neutral-300 rounded-lg bg-white text-neutral-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-150"
                    >
                </div>
            </div>

            <!-- Indicador de filtros activos -->
            @if($search || $estadoFiltro || $departamentoFiltro || $fechaDesde || $fechaHasta)
                <div class="flex items-center justify-end pt-2 border-t border-neutral-200">
                    <div class="flex items-center space-x-2 text-sm text-neutral-600">
                        <svg class="w-4 h-4 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Filtros activos</span>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Tabla -->
    <div class="w-full overflow-hidden bg-white border rounded-lg shadow-sm border-neutral-200">
        <div class="w-full overflow-x-auto">
        <table class="w-full divide-y divide-neutral-200">
            <thead class="bg-primary-100">
                <tr>
                    <th class="px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Solicitud
                    </th>
                    <th class="px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Usuario
                    </th>
                    <th class="px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Departamento
                    </th>
                    <th class="px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Estado
                    </th>
                    <th class="px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Fecha
                    </th>
                    <th class="px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Items
                    </th>
                    <th class="px-3 sm:px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-neutral-200">
                @forelse($solicitudes as $solicitud)
                    <tr class="hover:bg-secondary-50 transition-colors duration-150">
                        <!-- Solicitud -->
                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-neutral-900">
                                {{ $solicitud->numero_solicitud }}
                            </div>
                            <div class="text-xs text-neutral-500">
                                {{ $solicitud->tipo_solicitud }}
                            </div>
                        </td>
                        
                        <!-- Usuario -->
                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-neutral-900">{{ $solicitud->user->nombre ?? 'N/A' }}</div>
                            <div class="text-xs text-neutral-500">{{ $solicitud->user->correo ?? 'N/A' }}</div>
                        </td>
                        
                        <!-- Departamento -->
                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-neutral-600">{{ $solicitud->departamento->nombre_depto ?? 'N/A' }}</div>
                        </td>
                        
                        <!-- Estado -->
                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                            @php
                                $estadoColors = [
                                    'pendiente' => 'bg-yellow-100 text-yellow-800',
                                    'aprobada' => 'bg-green-100 text-green-800',
                                    'rechazada' => 'bg-red-100 text-red-800',
                                    'entregada' => 'bg-blue-100 text-blue-800'
                                ];
                                $color = $estadoColors[$solicitud->estado] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                {{ ucfirst($solicitud->estado) }}
                            </span>
                        </td>
                        
                        <!-- Fecha -->
                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-neutral-600">
                                {{ $solicitud->fecha_solicitud->format('d/m/Y H:i') }}
                            </div>
                        </td>
                        
                        <!-- Items -->
                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-neutral-600">
                                {{ $solicitud->items->count() }} item(s)
                            </div>
                            <div class="text-xs text-neutral-500">
                                Total: {{ $solicitud->items->sum('cantidad_solicitada') }} unidades
                            </div>
                        </td>
                        
                        <!-- Acciones -->
                        <td class="px-3 sm:px-6 py-4 text-center">
                            <div class="flex items-center justify-center space-x-1 nowrap">
                                <!-- Ver detalles -->
                                <button onclick="toggleDetails({{ $solicitud->id }})"
                                        class="inline-flex items-center justify-center px-2 py-1.5 text-xs font-medium transition-all duration-150 border border-transparent rounded-md text-primary-600 bg-primary-50 hover:bg-primary-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-400"
                                        title="Ver detalles">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>

                                <!-- Descargar PDF -->
                                <a href="{{ route('solicitudes.export.pdf', $solicitud->id) }}" 
                                   target="_blank"
                                   class="inline-flex items-center justify-center px-2 py-1.5 text-xs font-medium transition-all duration-150 border border-transparent rounded-md text-danger-600 bg-danger-50 hover:bg-danger-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-danger-500"
                                   title="Descargar PDF">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                </a>

                                <!-- Descargar Excel -->
                                <a href="{{ route('solicitudes.export.excel', $solicitud->id) }}" 
                                   target="_blank"
                                   class="inline-flex items-center justify-center px-2 py-1.5 text-xs font-medium transition-all duration-150 border border-transparent rounded-md text-success-600 bg-success-50 hover:bg-success-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-success-400"
                                   title="Descargar Excel">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </a>

                                @if($solicitud->estado === 'pendiente')
                                    <!-- Aprobar -->
                                    <button wire:click="aprobarSolicitud({{ $solicitud->id }})"
                                            wire:confirm="¿Estás seguro de aprobar esta solicitud?"
                                            class="inline-flex items-center justify-center px-2 py-1.5 text-xs font-medium transition-all duration-150 border border-transparent rounded-md text-success-600 bg-success-50 hover:bg-success-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-success-400"
                                            title="Aprobar solicitud">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </button>

                                    <!-- Rechazar -->
                                    <button wire:click="rechazarSolicitud({{ $solicitud->id }})"
                                            wire:confirm="¿Estás seguro de rechazar esta solicitud?"
                                            class="inline-flex items-center justify-center px-2 py-1.5 text-xs font-medium transition-colors duration-150 border border-transparent rounded-md text-danger-600 bg-danger-50 hover:bg-danger-600 hover:text-white active:bg-danger-700 active:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-danger-500"
                                            title="Rechazar solicitud">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                @endif

                                @if($solicitud->estado === 'aprobada')
                                    <!-- Entregar -->
                                    <button wire:click="entregarSolicitud({{ $solicitud->id }})"
                                            wire:confirm="¿Marcar esta solicitud como entregada?"
                                            class="inline-flex items-center justify-center px-2 py-1.5 text-xs font-medium transition-all duration-150 border border-transparent rounded-md text-primary-600 bg-primary-50 hover:bg-primary-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-400"
                                            title="Marcar como entregada">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>

                    <!-- Detalles expandibles -->
                    <tr id="details-{{ $solicitud->id }}" class="hidden">
                        <td colspan="7" class="px-3 sm:px-6 py-4 bg-gray-50">
                            <div class="bg-white rounded-lg border p-4">
                                <h4 class="text-sm font-medium text-gray-900 mb-3">Detalles de la solicitud:</h4>
                                <div class="space-y-2">
                                    @foreach($solicitud->items as $item)
                                        <div class="flex items-center justify-between py-2 border-b border-gray-200 last:border-b-0">
                                            <div class="flex-1">
                                                <div class="text-sm font-medium text-gray-900">{{ $item->insumo->nombre_insumo }}</div>
                                                <div class="text-xs text-gray-500">{{ $item->insumo->id_insumo }}</div>
                                            </div>
                                            <div class="text-sm text-gray-600">
                                                <span class="font-medium">{{ $item->cantidad_solicitada }}</span> unidades
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @if($solicitud->observaciones)
                                    <div class="mt-3 pt-3 border-t border-gray-200">
                                        <h5 class="text-xs font-medium text-gray-700 mb-1">Observaciones:</h5>
                                        <p class="text-sm text-gray-600">{{ $solicitud->observaciones }}</p>
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-3 sm:px-6 py-12 text-center" colspan="7">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-neutral-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-neutral-900 mb-2">No hay solicitudes</h3>
                                <p class="text-neutral-500">No se encontraron solicitudes con los filtros aplicados.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    
        <!-- Paginación -->
        <div class="px-4 py-3 border-t bg-gray-50 border-neutral-200">
            {{ $solicitudes->links() }}
        </div>
    </div>
</div>

<script>
function toggleDetails(solicitudId) {
    const detailsRow = document.getElementById('details-' + solicitudId);
    if (detailsRow.classList.contains('hidden')) {
        detailsRow.classList.remove('hidden');
    } else {
        detailsRow.classList.add('hidden');
    }
}
</script>

