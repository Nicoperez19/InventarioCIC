<div class="w-full bg-white shadow-sm rounded-lg border border-neutral-200 overflow-hidden">
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

    <!-- Filtros -->
    <div class="p-4 border-b border-gray-200 bg-gray-50">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Búsqueda -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       id="search"
                       placeholder="Número o usuario..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Estado -->
            <div>
                <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <select wire:model.live="estadoFiltro" 
                        id="estado"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos los estados</option>
                    <option value="pendiente">Pendiente</option>
                    <option value="aprobada">Aprobada</option>
                    <option value="rechazada">Rechazada</option>
                    <option value="entregada">Entregada</option>
                </select>
            </div>

            <!-- Departamento -->
            <div>
                <label for="departamento" class="block text-sm font-medium text-gray-700 mb-1">Departamento</label>
                <select wire:model.live="departamentoFiltro" 
                        id="departamento"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos los departamentos</option>
                    @foreach(\App\Models\Departamento::orderBy('nombre_depto')->get() as $departamento)
                        <option value="{{ $departamento->id_depto }}">{{ $departamento->nombre_depto }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Fecha desde -->
            <div>
                <label for="fecha-desde" class="block text-sm font-medium text-gray-700 mb-1">Desde</label>
                <input type="date" 
                       wire:model.live="fechaDesde" 
                       id="fecha-desde"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Fecha hasta -->
            <div>
                <label for="fecha-hasta" class="block text-sm font-medium text-gray-700 mb-1">Hasta</label>
                <input type="date" 
                       wire:model.live="fechaHasta" 
                       id="fecha-hasta"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
    </div>

    <!-- Tabla de solicitudes -->
    <div class="w-full overflow-x-auto">
        <table class="w-full divide-y divide-neutral-200">
            <thead class="bg-gray-50">
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
                    <tr class="hover:bg-blue-50/30 transition-colors duration-150">
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
                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center space-x-2 flex-wrap gap-1">
                                <!-- Ver detalles -->
                                <button onclick="toggleDetails({{ $solicitud->id }})"
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-600 bg-blue-100 rounded hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Ver
                                </button>

                                <!-- Descargar PDF -->
                                <a href="{{ route('solicitudes.export.pdf', $solicitud->id) }}" 
                                   target="_blank"
                                   class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-600 bg-red-100 rounded hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-500"
                                   title="Descargar PDF">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                </a>

                                <!-- Descargar Excel -->
                                <a href="{{ route('solicitudes.export.excel', $solicitud->id) }}" 
                                   target="_blank"
                                   class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-600 bg-green-100 rounded hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-green-500"
                                   title="Descargar Excel">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </a>

                                @if($solicitud->estado === 'pendiente')
                                    <!-- Aprobar -->
                                    <button wire:click="aprobarSolicitud({{ $solicitud->id }})"
                                            wire:confirm="¿Estás seguro de aprobar esta solicitud?"
                                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-600 bg-green-100 rounded hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-green-500">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Aprobar
                                    </button>

                                    <!-- Rechazar -->
                                    <button wire:click="rechazarSolicitud({{ $solicitud->id }})"
                                            wire:confirm="¿Estás seguro de rechazar esta solicitud?"
                                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-600 bg-red-100 rounded hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-500">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Rechazar
                                    </button>
                                @endif

                                @if($solicitud->estado === 'aprobada')
                                    <!-- Entregar -->
                                    <button wire:click="entregarSolicitud({{ $solicitud->id }})"
                                            wire:confirm="¿Marcar esta solicitud como entregada?"
                                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-600 bg-blue-100 rounded hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                        </svg>
                                        Entregar
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
    <div class="px-4 py-3 border-t border-gray-200">
        {{ $solicitudes->links() }}
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

