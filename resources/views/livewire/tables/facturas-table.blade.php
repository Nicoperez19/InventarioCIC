<div class="w-full bg-white shadow-sm rounded-lg border border-neutral-200 overflow-hidden">
    <!-- Tabla -->
    <div class="w-full overflow-x-auto">
        <table class="w-full table-fixed divide-y divide-neutral-200">
        <thead class="bg-gray-50">
            <tr>
                    <th class="w-2/12 px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <div class="flex items-center space-x-1">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="hidden sm:inline">Número</span>
                        </div>
                    </th>
                    <th class="w-3/12 px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <div class="flex items-center space-x-1 pl-6">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span class="hidden sm:inline">Proveedor</span>
                        </div>
                    </th>
                    <th class="w-2/12 px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <div class="flex items-center space-x-1 pl-6">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            <span class="hidden sm:inline">Monto</span>
                        </div>
                    </th>
                    <th class="w-2/12 px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <div class="flex items-center space-x-1 pl-6">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="hidden sm:inline">Fecha</span>
                        </div>
                    </th>
                  
                    <th class="w-2/12 px-3 sm:px-6 py-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">
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
            @forelse($facturas as $factura)
                    <tr wire:key="factura-{{ $factura->id }}" class="hover:bg-light-cyan/10 transition-colors duration-150">
                        <td class="w-2/12 px-3 sm:px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-neutral-900">
                                {{ $factura->numero_factura }}
                            </div>
                        </td>
                        <td class="w-3/12 px-3 sm:px-6 py-4">
                            <div class="text-sm font-medium text-neutral-900 pl-6">{{ $factura->proveedor->nombre_proveedor ?? 'Sin proveedor' }}</div>
                        </td>
                        <td class="w-2/12 px-3 sm:px-6 py-4">
                            <div class="text-sm text-neutral-600 pl-6">${{ number_format($factura->monto_total, 0, ',', '.') }}</div>
                        </td>
                        <td class="w-2/12 px-3 sm:px-6 py-4">
                            <div class="text-sm text-neutral-600 pl-6">{{ $factura->fecha_factura->format('d/m/Y') }}</div>
                        </td>
                    
                        <td class="w-2/12 px-3 sm:px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center justify-end space-x-1 sm:space-x-3">
                                <!-- Botón Ver -->
                                <a href="{{ route('facturas.show', $factura->id) }}" 
                                   class="inline-flex items-center px-2 sm:px-3 py-1 sm:py-2 border border-transparent text-xs font-medium rounded-md text-green-600 bg-green-50 hover:bg-green-600 hover:text-white active:bg-green-700 active:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-150">
                                    <svg class="w-3 h-3 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <span class="hidden sm:inline">Ver</span>
                                </a>
                                
                                @if($factura->tieneArchivo())
                                <!-- Botón Descargar -->
                                <a href="{{ route('facturas.download', $factura->id) }}" 
                                   class="inline-flex items-center px-2 sm:px-3 py-1 sm:py-2 border border-transparent text-xs font-medium rounded-md text-blue-600 bg-blue-50 hover:bg-blue-600 hover:text-white active:bg-blue-700 active:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150">
                                    <svg class="w-3 h-3 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span class="hidden sm:inline">Descargar</span>
                                </a>
                                @endif
                                
                                <!-- Botón Editar -->
                                <a href="{{ route('facturas.edit', $factura->id) }}" 
                                   class="inline-flex items-center px-2 sm:px-3 py-1 sm:py-2 border border-transparent text-xs font-medium rounded-md text-yellow-600 bg-yellow-50 hover:bg-yellow-600 hover:text-white active:bg-yellow-700 active:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-150">
                                    <svg class="w-3 h-3 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    <span class="hidden sm:inline">Editar</span>
                                </a>
                                
                                <!-- Botón Eliminar -->
                                <form action="{{ route('facturas.destroy', $factura->id) }}" 
                                      method="POST" 
                                      class="inline" 
                                      onsubmit="return confirm('¿Estás seguro de que deseas eliminar la factura \'{{ $factura->numero_factura }}\'? Esta acción no se puede deshacer.');">
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-neutral-900 mb-2">No hay facturas</h3>
                                <p class="text-neutral-500">Comienza creando tu primera factura para organizar tu sistema.</p>
                            </div>
                        </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    
    <!-- Paginación -->
    <div class="px-4 py-3 bg-gray-50 border-t border-neutral-200">
        {{ $facturas->links() }}
    </div>
</div>




