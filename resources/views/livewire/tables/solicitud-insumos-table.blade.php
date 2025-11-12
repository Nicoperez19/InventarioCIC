<div class="relative {{ collect($cantidades)->filter(fn($cantidad) => $cantidad > 0)->count() > 0 ? 'pb-96 lg:pb-8' : '' }}">
    <!-- Mensajes de éxito/error mejorados -->
    @if (session()->has('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-5 mb-6 rounded-lg shadow-md">
            <div class="flex items-start">
                <svg class="h-6 w-6 text-green-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <p class="text-base font-semibold text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-5 mb-6 rounded-lg shadow-md">
            <div class="flex items-start">
                <svg class="h-6 w-6 text-red-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
                <p class="text-base font-semibold text-red-800">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @if (session()->has('info'))
        <div class="bg-blue-50 border-l-4 border-blue-500 p-5 mb-6 rounded-lg shadow-md">
            <div class="flex items-start">
                <svg class="h-6 w-6 text-blue-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
                <p class="text-base font-semibold text-blue-800">{{ session('info') }}</p>
            </div>
        </div>
    @endif

    <!-- Instrucciones claras al inicio -->
    <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-6 mb-6 shadow-sm">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-7 w-7 text-blue-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-bold text-blue-900 mb-2">¿Cómo solicitar insumos?</h3>
                <ol class="list-decimal list-inside space-y-2 text-base text-blue-800">
                    <li>Busca el insumo que necesitas usando el buscador o el filtro de tipo</li>
                    <li>Usa los botones <strong>+</strong> y <strong>-</strong> para indicar la cantidad que deseas</li>
                    <li>Revisa tu pedido en el resumen que aparece abajo</li>
                    <li>Haz clic en <strong>"Confirmar Solicitud"</strong> cuando estés listo</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Barra de búsqueda y filtros mejorada -->
    <div class="bg-white border-2 border-gray-200 rounded-xl shadow-md p-6 mb-6">
        <h3 class="text-xl font-bold text-gray-800 mb-5 flex items-center">
            <svg class="w-6 h-6 text-primary-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            Buscar Insumos
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- Buscador -->
            <div>
                <label class="block mb-3 text-base font-semibold text-gray-700">
                    Buscar por nombre del insumo
                </label>
                <div class="relative">
                    <input 
                        type="text"
                        wire:model.live.debounce.300ms="busqueda"
                        placeholder="Ejemplo: Papel, Lápiz, Detergente..."
                        class="w-full px-5 py-4 pl-12 text-base border-2 border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition-all"
                    >
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    @if($busqueda)
                        <button 
                            wire:click="$set('busqueda', '')"
                            class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600"
                            aria-label="Limpiar búsqueda"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    @endif
                </div>
            </div>

            <!-- Filtro por Tipo -->
            <div>
                <label class="block mb-3 text-base font-semibold text-gray-700">
                    Filtrar por tipo de insumo
                </label>
                <select 
                    wire:model.live="tipoInsumoFiltro" 
                    class="w-full px-5 py-4 text-base border-2 border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition-all"
                >
                    <option value="">Todos los tipos</option>
                    @foreach($tiposDisponibles as $tipo)
                        <option value="{{ $tipo->id }}">{{ $tipo->nombre_tipo }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @if($tipoInsumoFiltro || $busqueda)
            <div class="mt-5 pt-5 border-t-2 border-gray-200 flex items-center justify-between">
                <div class="flex items-center space-x-2 text-base text-gray-700">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-semibold">Filtros aplicados</span>
                </div>
                <button 
                    wire:click="$set('tipoInsumoFiltro', ''); $set('busqueda', '')"
                    class="flex items-center space-x-2 px-4 py-2.5 text-base font-semibold text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span>Limpiar filtros</span>
                </button>
            </div>
        @endif
    </div>

    <!-- Vista de Tarjetas tipo E-commerce (sin imágenes) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @forelse($insumos as $insumo)
            <div class="bg-white border-2 {{ ($cantidades[$insumo->id_insumo] ?? 0) > 0 ? 'border-primary-400 bg-primary-50' : 'border-gray-200' }} rounded-xl shadow-md hover:shadow-lg transition-all duration-200 overflow-hidden">
                <!-- Header de la tarjeta -->
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-5 border-b-2 border-gray-200">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900 mb-2 leading-tight">{{ $insumo->nombre_insumo }}</h3>
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-semibold bg-blue-100 text-blue-800">
                                {{ $insumo->tipoInsumo ? $insumo->tipoInsumo->nombre_tipo : 'Sin tipo' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Contenido de la tarjeta -->
                <div class="p-6">
                    <!-- Información del insumo -->
                    <div class="mb-6 space-y-4">
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-base font-medium text-gray-600">Unidad de medida:</span>
                            <span class="text-lg font-bold text-gray-900">{{ $insumo->unidadMedida ? $insumo->unidadMedida->nombre_unidad_medida : 'N/A' }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-base font-medium text-gray-600">Disponible en stock:</span>
                            <span class="inline-flex items-center px-4 py-2 rounded-lg text-lg font-bold bg-green-100 text-green-800">
                                {{ $insumo->stock_actual }} unidades
                            </span>
                        </div>
                    </div>

                    <!-- Selector de cantidad - Estilo E-commerce mejorado -->
                    <div class="mb-4">
                        <label class="block mb-4 text-base font-bold text-gray-700">
                            Cantidad a solicitar:
                        </label>
                        <div class="flex items-center justify-center space-x-4">
                            <!-- Botón disminuir -->
                            <button 
                                wire:click="actualizarCantidad('{{ $insumo->id_insumo }}', {{ max(0, ($cantidades[$insumo->id_insumo] ?? 0) - 1) }})"
                                class="w-14 h-14 flex items-center justify-center bg-gray-100 hover:bg-gray-200 active:bg-gray-300 text-gray-700 rounded-xl font-bold text-2xl transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-4 focus:ring-gray-300 disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:scale-100"
                                {{ ($cantidades[$insumo->id_insumo] ?? 0) <= 0 ? 'disabled' : '' }}
                                aria-label="Disminuir cantidad"
                            >
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4"></path>
                                </svg>
                            </button>

                            <!-- Input de cantidad -->
                            <div class="flex flex-col items-center">
                                <input 
                                    type="number" 
                                    wire:model.defer="cantidades.{{ $insumo->id_insumo }}"
                                    wire:change="actualizarCantidad('{{ $insumo->id_insumo }}', $event.target.value)"
                                    wire:blur="actualizarCantidad('{{ $insumo->id_insumo }}', $event.target.value)"
                                    class="w-28 h-14 px-4 text-2xl font-bold text-center border-2 rounded-xl transition-colors {{ isset($errores[$insumo->id_insumo]) ? 'border-red-500 bg-red-50 focus:ring-red-300' : 'border-gray-300 focus:ring-blue-300 focus:border-blue-500' }}"
                                    min="0"
                                    max="{{ $insumo->stock_actual }}"
                                    step="1"
                                    value="{{ $cantidades[$insumo->id_insumo] ?? 0 }}"
                                    placeholder="0"
                                    data-max-stock="{{ $insumo->stock_actual }}"
                                    data-insumo-id="{{ $insumo->id_insumo }}"
                                >
                                @if(isset($errores[$insumo->id_insumo]))
                                    <p class="mt-2 text-xs font-medium text-red-600 text-center max-w-full">
                                        {{ Str::limit($errores[$insumo->id_insumo], 40) }}
                                    </p>
                                @endif
                            </div>

                            <!-- Botón aumentar -->
                            <button 
                                wire:click="actualizarCantidad('{{ $insumo->id_insumo }}', {{ min($insumo->stock_actual, ($cantidades[$insumo->id_insumo] ?? 0) + 1) }})"
                                class="w-14 h-14 flex items-center justify-center bg-primary-500 hover:bg-primary-600 active:bg-primary-700 text-white rounded-xl font-bold text-2xl transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-4 focus:ring-primary-300 disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:scale-100"
                                {{ ($cantidades[$insumo->id_insumo] ?? 0) >= $insumo->stock_actual ? 'disabled' : '' }}
                                aria-label="Aumentar cantidad"
                            >
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Indicador visual de cantidad seleccionada -->
                    @if(($cantidades[$insumo->id_insumo] ?? 0) > 0)
                        <div class="mt-4 p-4 bg-primary-100 border-2 border-primary-300 rounded-xl">
                            <p class="text-center text-base font-bold text-primary-900 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                {{ $cantidades[$insumo->id_insumo] }} {{ $insumo->unidadMedida ? $insumo->unidadMedida->nombre_unidad_medida : 'unidad' }}(s) seleccionada(s)
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-white border-2 border-gray-200 rounded-xl shadow-md p-12 text-center">
                    <svg class="w-20 h-20 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">No hay insumos disponibles</h3>
                    <p class="text-lg text-gray-600">No se encontraron insumos con stock disponible para tu rol.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Carrito Flotante Fijo - Siempre visible cuando hay items -->
    @if(collect($cantidades)->filter(fn($cantidad) => $cantidad > 0)->count() > 0)
        @php
            $itemsConCantidad = collect($cantidades)->filter(fn($cantidad) => $cantidad > 0);
            $resumenPedido = $this->resumenPedido;
        @endphp
        <div class="fixed bottom-0 left-0 right-0 bg-white border-t-4 border-primary-500 shadow-2xl z-50 lg:left-auto lg:right-8 lg:bottom-8 lg:max-w-md lg:rounded-2xl lg:border-t-0 lg:border-4">
            <div class="p-6 max-h-[85vh] overflow-y-auto">
                <!-- Header del carrito -->
                <div class="flex items-center justify-between mb-5 pb-4 border-b-2 border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-14 h-14 bg-primary-500 rounded-xl flex items-center justify-center shadow-md">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-gray-900">Resumen de tu Pedido</h4>
                            <p class="text-sm text-gray-600 font-medium">{{ count($resumenPedido) }} artículo(s) seleccionado(s)</p>
                        </div>
                    </div>
                    <button 
                        wire:click="limpiarSolicitud"
                        class="p-2.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                        aria-label="Limpiar todo el pedido"
                        title="Limpiar pedido"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>

                <!-- Lista de items -->
                <div class="mb-5 space-y-3 max-h-64 overflow-y-auto pr-2">
                    @foreach($resumenPedido as $item)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border-2 border-gray-200">
                            <div class="flex-1 min-w-0">
                                <p class="text-base font-bold text-gray-900 mb-1 truncate">{{ $item['nombre'] }}</p>
                                <p class="text-sm text-gray-600">Disponible: {{ $item['stock_disponible'] }} {{ $item['unidad'] }}</p>
                            </div>
                            <div class="ml-4 text-right flex-shrink-0">
                                <p class="text-lg font-bold text-primary-600">{{ $item['cantidad'] }}</p>
                                <p class="text-sm text-gray-500">{{ $item['unidad'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Resumen total -->
                <div class="mb-5 p-5 bg-primary-50 rounded-xl border-2 border-primary-200">
                    <div class="flex items-center justify-between mb-3 pb-2 border-b-2 border-primary-200">
                        <span class="text-base font-bold text-gray-700">Total de insumos:</span>
                        <span class="text-2xl font-bold text-primary-700">{{ count($resumenPedido) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-base font-bold text-gray-700">Total de unidades:</span>
                        <span class="text-2xl font-bold text-primary-700">{{ $itemsConCantidad->sum() }}</span>
                    </div>
                </div>

                <!-- Botón de crear solicitud -->
                <button 
                    onclick="window.dispatchEvent(new CustomEvent('crear-solicitud'))"
                    class="w-full py-5 px-6 bg-secondary-500 hover:bg-secondary-600 active:bg-secondary-700 text-white text-lg font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-secondary-300 flex items-center justify-center space-x-3"
                >
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Confirmar y Crear Solicitud</span>
                </button>

                <!-- Nota informativa -->
                <p class="mt-4 text-sm text-center text-gray-600 leading-relaxed">
                    <strong>Nota:</strong> Al confirmar, el stock se reducirá automáticamente y tu solicitud será procesada.
                </p>
            </div>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Escuchar eventos desde el header
    window.addEventListener('limpiar-solicitud', function() {
        @this.limpiarSolicitud();
    });
    
    window.addEventListener('crear-solicitud', function() {
        // Obtener los insumos seleccionados desde Livewire
        @this.call('obtenerResumenPedido').then(function(resumenPedido) {
            if (!resumenPedido || resumenPedido.length === 0) {
                alert('Debe seleccionar al menos un insumo con cantidad mayor a 0');
                return;
            }

            // Construir el HTML del detalle del pedido
            let detalleHTML = '<div class="text-left space-y-3 mb-4">';
            let totalUnidades = 0;
            
            resumenPedido.forEach(function(item) {
                detalleHTML += `
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border-2 border-gray-200">
                        <div class="flex-1 min-w-0">
                            <p class="text-base font-bold text-gray-900 mb-1">${item.nombre}</p>
                            <p class="text-sm text-gray-600">Disponible: ${item.stock_disponible} ${item.unidad}</p>
                        </div>
                        <div class="ml-4 text-right flex-shrink-0">
                            <p class="text-lg font-bold text-primary-600">${item.cantidad}</p>
                            <p class="text-sm text-gray-500">${item.unidad}</p>
                        </div>
                    </div>
                `;
                totalUnidades += parseInt(item.cantidad);
            });
            
            detalleHTML += '</div>';
            detalleHTML += `
                <div class="bg-primary-50 rounded-xl p-5 mb-4 border-2 border-primary-200">
                    <div class="flex items-center justify-between mb-3 pb-2 border-b-2 border-primary-200">
                        <span class="text-base font-bold text-gray-700">Total de insumos:</span>
                        <span class="text-2xl font-bold text-primary-700">${resumenPedido.length}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-base font-bold text-gray-700">Total de unidades:</span>
                        <span class="text-2xl font-bold text-primary-700">${totalUnidades}</span>
                    </div>
                </div>
            `;
            detalleHTML += '<p class="text-base text-gray-700 text-center font-semibold">¿Deseas confirmar esta solicitud?</p>';

            // Mostrar modal de confirmación personalizado
            window.confirmAction({
                title: 'Confirmar Solicitud de Insumos',
                message: detalleHTML,
                confirmText: 'Sí, crear solicitud',
                cancelText: 'Cancelar'
            })
            .then(function() {
                @this.crearSolicitud();
            })
            .catch(function() {
                // Usuario canceló
            });
        }).catch(function(error) {
            console.error('Error al obtener resumen:', error);
            // Fallback: crear directamente con confirmación simple
            if (confirm('¿Crear la solicitud? El stock se reducirá automáticamente.')) {
                @this.crearSolicitud();
            }
        });
    });
    
    // Escuchar evento de Livewire para mostrar notificación modal centrada como en departamentos
    document.addEventListener('livewire:init', function() {
        Livewire.on('solicitud-creada-exito', function(data) {
            // Esperar un poco para que se complete la transacción
            setTimeout(function() {
                const mensaje = data && data[0] ? data[0].mensaje : (data?.mensaje || data);
                if (window.notifySuccess && mensaje) {
                    window.notifySuccess(mensaje);
                }
            }, 100);
        });
    });
    
    const timeouts = new Map(); // Usar un mapa para trackear timeouts por input
    
    function validarInput(input) {
        if (!input || input.type !== 'number' || !input.hasAttribute('data-max-stock')) {
            return;
        }
        
        const maxValue = parseInt(input.getAttribute('data-max-stock')) || 0;
        let currentValue = input.value === '' ? 0 : parseInt(input.value) || 0;
        
        // Si el valor es negativo o excede el máximo, establecer a 0
        if (currentValue < 0 || (maxValue > 0 && currentValue > maxValue)) {
            input.value = 0;
            // Disparar evento change una sola vez para que Livewire actualice
            input.dispatchEvent(new Event('change', { bubbles: true }));
        }
    }
    
    // Validar solo cuando salga del campo (blur)
    document.addEventListener('blur', function(e) {
        if (e.target.type === 'number' && e.target.hasAttribute('data-max-stock')) {
            const inputId = e.target.getAttribute('data-insumo-id');
            if (timeouts.has(inputId)) {
                clearTimeout(timeouts.get(inputId));
                timeouts.delete(inputId);
            }
            validarInput(e.target);
        }
    }, true);
    
    // Validación al presionar Enter
    document.addEventListener('keydown', function(e) {
        if (e.target.type === 'number' && e.target.hasAttribute('data-max-stock')) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const inputId = e.target.getAttribute('data-insumo-id');
                if (timeouts.has(inputId)) {
                    clearTimeout(timeouts.get(inputId));
                    timeouts.delete(inputId);
                }
                validarInput(e.target);
                e.target.blur();
            }
        }
    }, true);
    
    // NO validar mientras escribe - solo trackear para limpiar timeout si es necesario
    document.addEventListener('input', function(e) {
        if (e.target.type === 'number' && e.target.hasAttribute('data-max-stock')) {
            const inputId = e.target.getAttribute('data-insumo-id');
            // Limpiar cualquier timeout pendiente para este input
            if (timeouts.has(inputId)) {
                clearTimeout(timeouts.get(inputId));
            }
            // NO validar aquí - solo permitir escribir libremente
        }
    }, true);
});
</script>
