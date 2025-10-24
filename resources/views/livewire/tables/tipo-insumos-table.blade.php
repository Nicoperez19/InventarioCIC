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
                            <span class="hidden sm:inline">Color</span>
                        </div>
                    </th>
                    <th class="w-3/12 px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <div class="flex items-center space-x-1 pl-6">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            <span class="hidden sm:inline">Nombre</span>
                        </div>
                    </th>
                    <th class="w-4/12 px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <div class="flex items-center space-x-1 pl-6">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                            </svg>
                            <span class="hidden sm:inline">Descripción</span>
                        </div>
                    </th>
                    <th class="w-2/12 px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <div class="flex items-center space-x-1 pl-6">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <span class="hidden sm:inline">Insumos</span>
                        </div>
                    </th>
                    <th class="w-1/12 px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <div class="flex items-center space-x-1 pl-6">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="hidden sm:inline">Estado</span>
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
            @forelse($tiposInsumo as $tipoInsumo)
                    <tr wire:key="tipo-insumo-{{ $tipoInsumo->id }}" class="hover:bg-light-cyan/10 transition-colors duration-150">
                        <td class="w-1/12 px-3 sm:px-6 py-4 whitespace-nowrap">
                            <div class="w-6 h-6 rounded-full border-2 border-gray-300" style="background-color: {{ $tipoInsumo->color }}"></div>
                        </td>
                        <td class="w-3/12 px-3 sm:px-6 py-4">
                            <div class="text-sm font-medium text-neutral-900 pl-6">{{ $tipoInsumo->nombre_tipo }}</div>
                        </td>
                        <td class="w-4/12 px-3 sm:px-6 py-4">
                            <div class="text-sm text-neutral-600 pl-6">{{ $tipoInsumo->descripcion ?? 'Sin descripción' }}</div>
                        </td>
                        <td class="w-2/12 px-3 sm:px-6 py-4">
                            <div class="text-sm text-neutral-600 pl-6">{{ $tipoInsumo->insumos_count ?? 0 }}</div>
                        </td>
                        <td class="w-1/12 px-3 sm:px-6 py-4">
                            <div class="text-sm pl-6">
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
                        <td class="w-1/12 px-3 sm:px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center justify-end space-x-1 sm:space-x-3">
                                <!-- Botón Ver -->
                                <a href="{{ route('tipo-insumos.show', $tipoInsumo->id) }}" 
                                   class="inline-flex items-center px-2 sm:px-3 py-1 sm:py-2 border border-transparent text-xs font-medium rounded-md text-green-600 bg-green-50 hover:bg-green-600 hover:text-white active:bg-green-700 active:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-150">
                                    <svg class="w-3 h-3 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <span class="hidden sm:inline">Ver</span>
                                </a>
                                
                                <!-- Botón Editar -->
                                <a href="{{ route('tipo-insumos.edit', $tipoInsumo->id) }}" 
                                   class="inline-flex items-center px-2 sm:px-3 py-1 sm:py-2 border border-transparent text-xs font-medium rounded-md text-blue-600 bg-blue-50 hover:bg-blue-600 hover:text-white active:bg-blue-700 active:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150">
                                    <svg class="w-3 h-3 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    <span class="hidden sm:inline">Editar</span>
                                </a>
                                
                                <!-- Botón Toggle Status -->
                                <form action="{{ route('tipo-insumos.toggle-status', $tipoInsumo->id) }}" 
                                      method="POST" 
                                      class="inline">
                            @csrf
                                    <button type="submit" 
                                            class="inline-flex items-center px-2 sm:px-3 py-1 sm:py-2 border border-transparent text-xs font-medium rounded-md {{ $tipoInsumo->activo ? 'text-yellow-600 bg-yellow-50 hover:bg-yellow-600 hover:text-white' : 'text-green-600 bg-green-50 hover:bg-green-600 hover:text-white' }} focus:outline-none focus:ring-2 focus:ring-offset-2 {{ $tipoInsumo->activo ? 'focus:ring-yellow-500' : 'focus:ring-green-500' }} transition-colors duration-150">
                                        <svg class="w-3 h-3 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($tipoInsumo->activo)
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            @endif
                                        </svg>
                                        <span class="hidden sm:inline">{{ $tipoInsumo->activo ? 'Desactivar' : 'Activar' }}</span>
                                    </button>
                        </form>
                                
                                <!-- Botón Eliminar -->
                                <form action="{{ route('tipo-insumos.destroy', $tipoInsumo->id) }}" 
                                      method="POST" 
                                      class="inline" 
                                      onsubmit="return confirm('¿Estás seguro de que deseas eliminar el tipo de insumo \'{{ $tipoInsumo->nombre_tipo }}\'? Esta acción no se puede deshacer.');">
                            @csrf
                            @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center px-2 sm:px-3 py-1 sm:py-2 border border-transparent text-xs font-medium rounded-md text-red-600 bg-red-50 hover:bg-red-600 hover:text-white active:bg-red-700 active:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-150">
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
                        <td class="px-3 sm:px-6 py-12 text-center" colspan="6">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-neutral-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-neutral-900 mb-2">No hay tipos de insumo</h3>
                                <p class="text-neutral-500">Comienza creando tu primer tipo de insumo para organizar tu sistema.</p>
                            </div>
                        </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    
    <!-- Paginación -->
    <div class="px-4 py-3 bg-gray-50 border-t border-neutral-200">
        {{ $tiposInsumo->links() }}
    </div>
</div>


