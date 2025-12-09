<div class="w-full overflow-hidden bg-white border rounded-lg shadow-sm border-neutral-200">
    <!-- Tabla -->
    <div class="w-full overflow-x-auto">
        <table class="w-full divide-y table-fixed divide-neutral-200" style="min-width: 1000px;">
        <thead class="bg-primary-100">
            <tr>
                    <th class="w-2/12 px-3 py-4 text-xs font-semibold tracking-wider text-center text-gray-600 uppercase sm:px-6">
                        <div class="flex items-center justify-center space-x-1">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            <span class="hidden sm:inline">Nombre</span>
                        </div>
                    </th>
                    <th class="w-2/12 px-3 py-4 text-xs font-semibold tracking-wider text-center text-gray-600 uppercase sm:px-6">
                        <div class="flex items-center justify-center space-x-1">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                            </svg>
                            <span class="hidden sm:inline">Descripción</span>
                        </div>
                    </th>
                    <th class="w-2/12 px-3 py-4 text-xs font-semibold tracking-wider text-center text-gray-600 uppercase sm:px-6">
                        <div class="flex items-center justify-center space-x-1">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <span class="hidden sm:inline">Insumos</span>
                        </div>
                    </th>
                    <th class="w-1/12 px-3 py-4 text-xs font-semibold tracking-wider text-center text-gray-600 uppercase sm:px-6">
                        <div class="flex items-center justify-center space-x-1">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="hidden sm:inline">Estado</span>
                        </div>
                    </th>
                    <th class="w-3/12 px-3 py-4 text-xs font-semibold tracking-wider text-center text-gray-600 uppercase sm:px-6">
                        <div class="flex items-center justify-center space-x-1">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                            </svg>
                            <span class="hidden sm:inline">Acciones</span>
                        </div>
                    </th>
            </tr>
        </thead>
            <tbody class="bg-white divide-y divide-neutral-200">
            @forelse($tiposInsumo as $tipoInsumo)
                    <tr wire:key="tipo-insumo-{{ $tipoInsumo->id }}" class="transition-colors duration-150 hover:bg-secondary-50">
                        <td class="w-2/12 px-3 py-4 sm:px-6 text-center">
                            <div class="text-sm font-medium text-neutral-900">{{ $tipoInsumo->nombre_tipo }}</div>
                        </td>
                        <td class="w-2/12 px-3 py-4 sm:px-6 text-center">
                            <div class="text-sm text-neutral-600 truncate" title="{{ $tipoInsumo->descripcion ?? 'Sin descripción' }}">{{ $tipoInsumo->descripcion ?? 'Sin descripción' }}</div>
                        </td>
                        <td class="w-2/12 px-3 py-4 sm:px-6 text-center">
                            <div class="text-sm text-neutral-600">{{ $tipoInsumo->insumos_count ?? 0 }}</div>
                        </td>
                        <td class="w-1/12 px-3 py-4 sm:px-6 text-center">
                            <div class="text-sm">
                                @if($tipoInsumo->activo)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Inactivo
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="w-3/12 px-3 py-4 text-sm font-medium sm:px-6 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center gap-2">
                                <!-- Botón Ver PDF -->
                                <a href="{{ route('tipo-insumos.pdf', $tipoInsumo->id) }}" 
                                   target="_blank"
                                   class="inline-flex items-center px-2 py-1 text-xs font-medium transition-all duration-150 border border-transparent rounded-md sm:px-3 sm:py-2 text-success-600 bg-success-50 hover:bg-success-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-success-400"
                                   title="Generar PDF con los insumos de este tipo">
                                    <svg class="w-3 h-3 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="hidden sm:inline">PDF</span>
                                </a>
                                
                                <!-- Botón Editar -->
                                <button type="button" 
                                        onclick="openEditTipoInsumoModal({{ $tipoInsumo->id }})"
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium transition-all duration-150 border border-transparent rounded-md sm:px-3 sm:py-2 text-primary-600 bg-primary-50 hover:bg-primary-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-400"
                                        title="Editar tipo de insumo">
                                    <svg class="w-3 h-3 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    <span class="hidden sm:inline">Editar</span>
                                </button>
                                
                                <!-- Botón Eliminar -->
                                <form action="{{ route('tipo-insumos.destroy', $tipoInsumo->id) }}" 
                                      method="POST" 
                                      class="inline-block"
                                      onsubmit="return confirm('¿Estás seguro de que deseas eliminar el tipo de insumo \'{{ $tipoInsumo->nombre_tipo }}\'? Esta acción no se puede deshacer.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center px-2 py-1 text-xs font-medium transition-colors duration-150 border border-transparent rounded-md sm:px-3 sm:py-2 text-danger-600 bg-danger-50 hover:bg-danger-600 hover:text-white active:bg-danger-700 active:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-danger-500"
                                            title="Eliminar tipo de insumo">
                                        <svg class="w-3 h-3 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        <span class="hidden sm:inline">Eliminar</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                </tr>
            @empty
                <tr>
                        <td class="px-3 py-12 text-center sm:px-6" colspan="5">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 mb-4 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                <h3 class="mb-2 text-lg font-medium text-neutral-900">No hay tipos de insumo</h3>
                                <p class="text-neutral-500">Comienza creando tu primer tipo de insumo para organizar tu sistema.</p>
                            </div>
                        </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    
    <!-- Paginación -->
    <div class="px-4 py-3 border-t bg-gray-50 border-neutral-200">
        {{ $tiposInsumo->links() }}
    </div>
</div>




