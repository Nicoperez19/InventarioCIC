<div class="w-full bg-white shadow-sm rounded-lg border border-neutral-200 overflow-hidden">
    <!-- Tabla -->
    <div class="w-full overflow-x-auto">
        <table class="w-full divide-y divide-neutral-200">
        <thead class="bg-gray-50">
            <tr>
                    <th class="px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <div class="flex items-center space-x-1">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            <span>ID</span>
                        </div>
                    </th>
                    <th class="px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <div class="flex items-center space-x-1">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <span>Producto</span>
                        </div>
                    </th>
                    <th class="px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <div class="flex items-center space-x-1">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span>Unidad</span>
                        </div>
                    </th>
                    <th class="px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <div class="flex items-center space-x-1">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span>Stock Mínimo</span>
                        </div>
                    </th>
                    <th class="px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <div class="flex items-center space-x-1">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <span>Stock Actual</span>
                        </div>
                    </th>
                    <th class="px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <div class="flex items-center space-x-1">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                            </svg>
                            <span>Código Barras</span>
                        </div>
                    </th>
                    <th class="px-3 sm:px-6 py-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <div class="flex items-center justify-end space-x-1">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                            </svg>
                            <span>Acciones</span>
                        </div>
                    </th>
            </tr>
        </thead>
            <tbody class="bg-white divide-y divide-neutral-200">
            @forelse($productos as $producto)
                    <tr class="hover:bg-light-cyan/10 transition-colors duration-150">
                        <!-- ID -->
                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-neutral-500">
                                {{ $producto->id_producto }}
                            </div>
                        </td>
                        
                        <!-- Producto -->
                        <td class="px-3 sm:px-6 py-4">
                            <div class="text-sm font-medium text-neutral-900">{{ $producto->nombre_producto }}</div>
                        </td>
                        
                        <!-- Unidad -->
                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-neutral-600">{{ $producto->unidad->nombre_unidad ?? $producto->id_unidad }}</div>
                        </td>
                        
                        <!-- Stock Mínimo -->
                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-neutral-600">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $producto->stock_minimo }}
                                </span>
                            </div>
                        </td>
                        
                        <!-- Stock Actual -->
                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-neutral-600">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $producto->stock_actual <= $producto->stock_minimo ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $producto->stock_actual }}
                                </span>
                            </div>
                        </td>
                        
                        <!-- Código Barras -->
                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                            <div class="text-center">
                                @if($producto->codigo_barra)
                                    <div class="inline-flex items-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 font-mono">
                                            {{ $producto->codigo_barra }}
                                        </span>
                                        <button type="button" 
                                                class="ml-2 p-1 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded"
                                                onclick="openBarcodeModal('{{ $producto->id_producto }}', '{{ $producto->codigo_barra }}')"
                                                title="Ver código de barras">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-xs">Sin código</span>
                                @endif
                            </div>
                        </td>
                        
                        <!-- Acciones -->
                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center justify-end space-x-1 sm:space-x-3">
                                <!-- Botón Ver Código de Barras -->
                                @if($producto->codigo_barra)
                                    <a href="{{ route('barcode.show', $producto->id_producto) }}" 
                                       class="inline-flex items-center px-2 sm:px-3 py-1 sm:py-2 border border-transparent text-xs font-medium rounded-md text-purple-600 bg-purple-50 hover:bg-purple-600 hover:text-white active:bg-purple-700 active:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors duration-150"
                                       title="Ver código de barras">
                                        <svg class="w-3 h-3 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                        </svg>
                                        <span class="hidden sm:inline">Código</span>
                                    </a>
                                @endif
                                
                                <!-- Botón Editar -->
                                <a href="{{ route('productos.edit', $producto->id_producto) }}" 
                                   class="inline-flex items-center px-2 sm:px-3 py-1 sm:py-2 border border-transparent text-xs font-medium rounded-md text-blue-600 bg-blue-50 hover:bg-blue-600 hover:text-white active:bg-blue-700 active:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150">
                                    <svg class="w-3 h-3 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    <span class="hidden sm:inline">Editar</span>
                                </a>
                                
                                <!-- Botón Eliminar -->
                                <form action="{{ route('productos.destroy', $producto->id_producto) }}" 
                                      method="POST" 
                                      class="inline" 
                                      onsubmit="return confirm('¿Estás seguro de que deseas eliminar el producto \'{{ $producto->nombre_producto }}\'? Esta acción no se puede deshacer.');">
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
                        <td class="px-3 sm:px-6 py-12 text-center" colspan="7">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-neutral-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-neutral-900 mb-2">No hay productos</h3>
                                <p class="text-neutral-500">Comienza creando tu primer producto para organizar tu inventario.</p>
                            </div>
                        </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    
    <!-- Modal para mostrar código de barras -->
<div id="barcodeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Header del modal -->
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Código de Barras</h3>
                <button type="button" 
                        class="text-gray-400 hover:text-gray-600"
                        onclick="closeBarcodeModal()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Contenido del modal -->
            <div class="text-center">
                <!-- Información del producto -->
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2">Producto: <span id="modalProductName" class="font-medium"></span></p>
                    <p class="text-sm text-gray-600">Código: <span id="modalBarcode" class="font-mono font-medium text-blue-600"></span></p>
                </div>
                
                <!-- Imagen del código de barras -->
                <div class="bg-white p-4 border rounded-lg shadow-sm mb-4">
                    <img id="modalBarcodeImage" 
                         src="" 
                         alt="Código de barras"
                         class="max-w-full h-auto mx-auto"
                         style="max-height: 120px;"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <div id="modalBarcodeFallback" class="text-center text-gray-500" style="display: none;">
                        <div class="w-32 h-16 bg-gray-100 rounded border-2 border-dashed border-gray-300 flex items-center justify-center mx-auto">
                            <span class="text-xs">Error cargando imagen</span>
                        </div>
                    </div>
                </div>
                
                <!-- Botones de acción -->
                <div class="flex justify-center space-x-3">
                    <button type="button" 
                            id="modalDownloadBtn"
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Descargar PNG
                    </button>
                    <button type="button" 
                            id="modalSvgBtn"
                            class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Ver SVG
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openBarcodeModal(productoId, codigoBarra) {
    // Mostrar el modal
    document.getElementById('barcodeModal').classList.remove('hidden');
    
    // Actualizar contenido
    document.getElementById('modalBarcode').textContent = codigoBarra;
    document.getElementById('modalProductName').textContent = 'Producto ' + productoId;
    
    // Cargar imagen del código de barras
    const imageUrl = `/barcode/${productoId}/small`;
    document.getElementById('modalBarcodeImage').src = imageUrl;
    
    // Configurar botones de descarga
    document.getElementById('modalDownloadBtn').onclick = function() {
        window.open(`/barcode/${productoId}/generate`, '_blank');
    };
    
    document.getElementById('modalSvgBtn').onclick = function() {
        window.open(`/barcode/${productoId}/svg`, '_blank');
    };
}

function closeBarcodeModal() {
    document.getElementById('barcodeModal').classList.add('hidden');
}

// Cerrar modal al hacer clic fuera de él
document.getElementById('barcodeModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeBarcodeModal();
    }
});

// Cerrar modal con tecla Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeBarcodeModal();
    }
});
</script>

</div>
