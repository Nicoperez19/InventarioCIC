<div class="relative {{ collect($cantidades)->filter(fn($cantidad) => $cantidad > 0)->count() > 0 ? 'pb-96 lg:pb-0' : '' }}">
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

    <!-- Barra de búsqueda y filtros mejorada -->
    <div class="bg-white border-2 border-gray-200 rounded-xl shadow-md p-6 mb-6">
        <h3 class="text-xl font-bold text-gray-800 mb-5 flex items-center">
            <svg class="w-6 h-6 text-primary-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            Buscar Insumos
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
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

            <!-- Ordenamiento -->
            <div>
                <label class="block mb-3 text-base font-semibold text-gray-700">
                    Ordenar por
                </label>
                <select 
                    wire:model.live="ordenamiento" 
                    class="w-full px-5 py-4 text-base border-2 border-gray-300 rounded-lg bg-white text-gray-900 focus:outline-none focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition-all"
                >
                    <option value="nombre_asc">Nombre (A-Z)</option>
                    <option value="nombre_desc">Nombre (Z-A)</option>
                    <option value="stock_desc">Cantidad (Mayor a Menor)</option>
                    <option value="stock_asc">Cantidad (Menor a Mayor)</option>
                </select>
            </div>
        </div>

        @if($tipoInsumoFiltro || $busqueda || $ordenamiento != 'nombre_asc')
            <div class="mt-5 pt-5 border-t-2 border-gray-200 flex items-center justify-between">
                <div class="flex items-center space-x-2 text-base text-gray-700">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-semibold">Filtros y ordenamiento aplicados</span>
                </div>
                <button 
                    wire:click="$set('tipoInsumoFiltro', ''); $set('busqueda', ''); $set('ordenamiento', 'nombre_asc')"
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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-8">
        @forelse($insumos as $insumo)
            <div class="bg-white border-2 {{ ($cantidades[$insumo->id_insumo] ?? 0) > 0 ? 'border-primary-500 shadow-lg ring-2 ring-primary-200' : 'border-gray-200' }} rounded-lg shadow-md hover:shadow-lg transition-all duration-200 {{ isset($errores[$insumo->id_insumo]) ? 'overflow-visible' : 'overflow-hidden' }} flex flex-col">
                <!-- Header de la tarjeta -->
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-3 border-b-2 border-gray-200">
                    <h3 class="text-base font-bold text-gray-900 mb-2 leading-tight line-clamp-2">{{ $insumo->nombre_insumo }}</h3>
                    @php
                        $tipoNombre = $insumo->tipoInsumo ? trim(strtolower($insumo->tipoInsumo->nombre_tipo)) : 'sin tipo';
                        // Normalizar: quitar acentos y espacios extra
                        $tipoNormalizado = str_replace(['á', 'é', 'í', 'ó', 'ú', 'ñ'], ['a', 'e', 'i', 'o', 'u', 'n'], $tipoNombre);
                        $tipoNormalizado = preg_replace('/\s+/', ' ', $tipoNormalizado);
                        
                        // Asignar colores basándose en palabras clave (orden de prioridad)
                        $colorClase = 'bg-gray-100 text-gray-800'; // Por defecto
                        
                        // Verificar primero tipos específicos que pueden contener otras palabras
                        if (strpos($tipoNormalizado, 'imprenta') !== false) {
                            $colorClase = 'bg-rose-100 text-rose-800';
                        } elseif (strpos($tipoNormalizado, 'informatica') !== false) {
                            $colorClase = 'bg-blue-100 text-blue-800';
                        } elseif (strpos($tipoNormalizado, 'aseo') !== false) {
                            $colorClase = 'bg-green-100 text-green-800';
                        } elseif (strpos($tipoNormalizado, 'oficina') !== false) {
                            $colorClase = 'bg-purple-100 text-purple-800';
                        } elseif (strpos($tipoNormalizado, 'limpieza') !== false) {
                            $colorClase = 'bg-emerald-100 text-emerald-800';
                        } elseif (strpos($tipoNormalizado, 'papeleria') !== false || strpos($tipoNormalizado, 'papel') !== false) {
                            $colorClase = 'bg-orange-100 text-orange-800';
                        } elseif (strpos($tipoNormalizado, 'tecnologia') !== false) {
                            $colorClase = 'bg-indigo-100 text-indigo-800';
                        } elseif (strpos($tipoNormalizado, 'mobiliario') !== false) {
                            $colorClase = 'bg-amber-100 text-amber-800';
                        } elseif (strpos($tipoNormalizado, 'equipo') !== false) {
                            $colorClase = 'bg-cyan-100 text-cyan-800';
                        } elseif (strpos($tipoNormalizado, 'herramienta') !== false) {
                            $colorClase = 'bg-pink-100 text-pink-800';
                        } elseif (strpos($tipoNormalizado, 'material') !== false) {
                            $colorClase = 'bg-teal-100 text-teal-800';
                        } else {
                            // Si no coincide con ninguna palabra clave, asignar color basado en hash del nombre
                            $hash = crc32($tipoNombre);
                            $coloresAlternativos = [
                                'bg-red-100 text-red-800',
                                'bg-yellow-100 text-yellow-800',
                                'bg-lime-100 text-lime-800',
                                'bg-sky-100 text-sky-800',
                                'bg-violet-100 text-violet-800',
                                'bg-fuchsia-100 text-fuchsia-800',
                                'bg-slate-100 text-slate-800',
                            ];
                            $colorClase = $coloresAlternativos[abs($hash) % count($coloresAlternativos)];
                        }
                    @endphp
                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-semibold {{ $colorClase }}">
                        {{ $insumo->tipoInsumo ? $insumo->tipoInsumo->nombre_tipo : 'Sin tipo' }}
                    </span>
                </div>

                <!-- Contenido de la tarjeta -->
                <div class="p-4 flex-1 flex flex-col">
                    <!-- Información del insumo -->
                    <div class="mb-4 space-y-2 flex-grow">
                        <div class="flex items-center justify-between py-1.5 border-b border-gray-100">
                            <span class="text-xs font-medium text-gray-600">Unidad:</span>
                            <span class="text-sm font-bold text-gray-900">{{ $insumo->unidadMedida ? $insumo->unidadMedida->nombre_unidad_medida : 'N/A' }}</span>
                        </div>
                        <div class="flex items-center justify-between py-1.5">
                            <span class="text-xs font-medium text-gray-600">Stock:</span>
                            <span class="inline-flex items-center px-2 py-1 rounded-md text-sm font-bold bg-green-100 text-green-800">
                                {{ $insumo->stock_actual }}
                            </span>
                        </div>
                    </div>

                    <!-- Selector de cantidad - Input principal -->
                    <div class="mb-3 relative">
                        <label class="block mb-2.5 text-sm font-bold text-gray-800">
                            Ingrese la cantidad:
                        </label>
                        <div class="flex justify-center">
                            <!-- Input de cantidad (PRINCIPAL) -->
                            <div class="flex flex-col items-center w-full max-w-[140px] relative">
                                <div class="flex items-center gap-2 w-full">
                                    <!-- Botón izquierda (Disminuir) -->
                                    <button 
                                        type="button"
                                        wire:click="actualizarCantidad('{{ $insumo->id_insumo }}', {{ max(0, (int)($cantidades[$insumo->id_insumo] ?? 0) - 1) }})"
                                        class="w-10 h-10 flex items-center justify-center bg-secondary-500 hover:bg-secondary-600 active:bg-secondary-700 text-white rounded-lg transition-all duration-150 shadow-md hover:shadow-lg disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:shadow-md"
                                        {{ (int)($cantidades[$insumo->id_insumo] ?? 0) <= 0 ? 'disabled' : '' }}
                                        aria-label="Disminuir"
                                    >
                                        <span class="text-2xl font-bold leading-none">−</span>
                                    </button>
                                    
                                    <!-- Input -->
                                    <input 
                                        type="number" 
                                        wire:input.debounce.300ms="actualizarCantidad('{{ $insumo->id_insumo }}', $event.target.value)"
                                        wire:blur="actualizarCantidad('{{ $insumo->id_insumo }}', $event.target.value)"
                                        wire:keydown.enter="actualizarCantidad('{{ $insumo->id_insumo }}', $event.target.value); $event.target.blur()"
                                        class="w-16 h-10 px-2 text-lg font-bold text-center border-2 rounded-lg transition-all duration-200 focus:outline-none focus:ring-3 focus:ring-offset-1 {{ isset($errores[$insumo->id_insumo]) ? 'border-red-400 bg-red-50 focus:ring-red-400 focus:border-red-500' : 'border-gray-300 bg-white focus:ring-blue-200 focus:border-blue-500 hover:border-gray-400' }} shadow-md hover:shadow-lg focus:shadow-xl [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                        min="0"
                                        max="{{ $insumo->stock_actual }}"
                                        step="1"
                                        value="{{ $cantidades[$insumo->id_insumo] ?? 0 }}"
                                        placeholder="0"
                                        data-max-stock="{{ $insumo->stock_actual }}"
                                        data-insumo-id="{{ $insumo->id_insumo }}"
                                    >
                                    
                                    <!-- Botón derecha (Aumentar) -->
                                    <button 
                                        type="button"
                                        wire:click="actualizarCantidad('{{ $insumo->id_insumo }}', {{ min($insumo->stock_actual, (int)($cantidades[$insumo->id_insumo] ?? 0) + 1) }})"
                                        class="w-10 h-10 flex items-center justify-center bg-secondary-500 hover:bg-secondary-600 active:bg-secondary-700 text-white rounded-lg transition-all duration-150 shadow-md hover:shadow-lg disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:shadow-md"
                                        {{ (int)($cantidades[$insumo->id_insumo] ?? 0) >= $insumo->stock_actual ? 'disabled' : '' }}
                                        aria-label="Aumentar"
                                    >
                                        <span class="text-2xl font-bold leading-none">+</span>
                                    </button>
                                </div>
                                
                                <!-- Mensaje de error flotante - Fuera del contenedor flex -->
                                @if(isset($errores[$insumo->id_insumo]))
                                    <div class="absolute top-full left-1/2 transform -translate-x-1/2 mt-3 z-[100] min-w-[220px] max-w-[280px] pointer-events-none">
                                        <div class="bg-red-600 text-white px-4 py-3 rounded-lg shadow-2xl border-2 border-red-700 relative">
                                            <div class="flex items-start space-x-2">
                                                <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                </svg>
                                                <p class="text-sm font-semibold leading-relaxed">
                                                    {{ $errores[$insumo->id_insumo] }}
                                                </p>
                                            </div>
                                            <!-- Flecha del tooltip -->
                                            <div class="absolute -top-2 left-1/2 transform -translate-x-1/2">
                                                <div class="w-4 h-4 bg-red-600 border-l-2 border-t-2 border-red-700 transform rotate-45"></div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Indicador visual de cantidad seleccionada -->
                    @if(($cantidades[$insumo->id_insumo] ?? 0) > 0)
                        <div class="mt-2 p-2 bg-primary-50 border border-primary-300 rounded-md">
                            <p class="text-center text-xs font-semibold text-primary-700 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                {{ $cantidades[$insumo->id_insumo] }} {{ $insumo->unidadMedida ? $insumo->unidadMedida->nombre_unidad_medida : 'unidad' }}(s)
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

    <!-- Paginación -->
    <div class="px-4 py-3 border-t bg-gray-50 border-neutral-200 rounded-lg mb-8">
        {{ $insumos->links() }}
    </div>

    <!-- Detalle de Solicitud - Fijo al costado en desktop -->
    @if(collect($cantidades)->filter(fn($cantidad) => $cantidad > 0)->count() > 0)
        @php
            $itemsConCantidad = collect($cantidades)->filter(fn($cantidad) => $cantidad > 0);
            $resumenPedido = $this->resumenPedido;
        @endphp
        <div class="fixed bottom-0 left-0 right-0 bg-white shadow-2xl z-50 lg:left-auto lg:right-0 lg:top-0 lg:bottom-0 lg:w-96 lg:max-w-none lg:rounded-none lg:shadow-2xl flex flex-col border-l border-gray-200">
            <div class="p-5 flex-1 flex flex-col overflow-hidden bg-gradient-to-b from-gray-50 to-white">
                <!-- Header del detalle -->
                <div class="flex items-center justify-between mb-5 pb-4 border-b-2 border-gray-300 bg-white rounded-lg p-3 shadow-sm">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-primary-600 rounded-lg flex items-center justify-center shadow-md">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-gray-900">Detalle de Solicitud</h4>
                            <p class="text-sm text-gray-600 font-medium">{{ count($resumenPedido) }} artículo(s)</p>
                        </div>
                    </div>
                    <button 
                        wire:click="limpiarSolicitud"
                        class="p-2.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                        aria-label="Limpiar todo el pedido"
                        title="Limpiar pedido"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>

                <!-- Lista de items -->
                <div class="mb-5 space-y-3 flex-1 overflow-y-auto pr-2 min-h-0">
                    @foreach($resumenPedido as $item)
                        <div class="flex items-center justify-between p-4 bg-white rounded-lg border-2 border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-900 mb-1.5 truncate">{{ $item['nombre'] }}</p>
                                <p class="text-xs text-gray-500">Stock disponible: <span class="font-semibold text-gray-700">{{ $item['stock_disponible'] }}</span> {{ $item['unidad'] }}</p>
                            </div>
                            <div class="ml-4 text-right flex-shrink-0">
                                <div class="bg-primary-100 rounded-lg px-3 py-2">
                                    <p class="text-lg font-bold text-primary-700">{{ $item['cantidad'] }}</p>
                                    <p class="text-xs text-primary-600 font-medium">{{ $item['unidad'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Resumen total -->
                <div class="mb-5 p-4 bg-gradient-to-r from-primary-50 to-primary-100 rounded-lg border-2 border-primary-300 shadow-md">
                    <div class="flex items-center justify-between mb-3 pb-3 border-b-2 border-primary-300">
                        <span class="text-sm font-bold text-gray-800">Total insumos:</span>
                        <span class="text-xl font-bold text-primary-800">{{ count($resumenPedido) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-gray-800">Total unidades:</span>
                        <span class="text-xl font-bold text-primary-800">{{ $itemsConCantidad->sum() }}</span>
                    </div>
                </div>

                <!-- Botón de crear solicitud -->
                <button 
                    onclick="window.dispatchEvent(new CustomEvent('crear-solicitud'))"
                    class="w-full py-4 px-5 bg-gradient-to-r from-secondary-500 to-secondary-600 hover:from-secondary-600 hover:to-secondary-700 active:from-secondary-700 active:to-secondary-800 text-white text-base font-bold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-secondary-300 flex items-center justify-center space-x-2"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Confirmar Solicitud</span>
                </button>

                <!-- Nota informativa -->
                <p class="mt-4 text-xs text-center text-gray-600 leading-relaxed">
                    <strong class="text-gray-700">Nota:</strong> Al confirmar, el stock se reducirá automáticamente.
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
                title: 'Detalle de Solicitud',
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
    
    // Validar cuando salga del campo (blur) - llamar directamente a Livewire
    document.addEventListener('blur', function(e) {
        if (e.target.type === 'number' && e.target.hasAttribute('data-max-stock') && e.target.hasAttribute('data-insumo-id')) {
            const insumoId = e.target.getAttribute('data-insumo-id');
            const valor = e.target.value;
            // Llamar al método de Livewire para validar
            @this.call('actualizarCantidad', insumoId, valor);
        }
    }, true);
    
    // Permitir escribir libremente, la validación se hará al salir del campo
    document.addEventListener('input', function(e) {
        if (e.target.type === 'number' && e.target.hasAttribute('data-max-stock')) {
            const inputId = e.target.getAttribute('data-insumo-id');
            // Limpiar cualquier timeout pendiente para este input
            if (timeouts.has(inputId)) {
                clearTimeout(timeouts.get(inputId));
            }
        }
    }, true);
    
    // Borrar el "0" cuando se selecciona el input
    document.addEventListener('focus', function(e) {
        if (e.target.type === 'number' && e.target.hasAttribute('data-max-stock')) {
            if (e.target.value === '0' || e.target.value === 0) {
                e.target.value = '';
                e.target.select();
            }
        }
    }, true);
    
    // También manejar el evento focusin para mejor compatibilidad
    document.addEventListener('focusin', function(e) {
        if (e.target.type === 'number' && e.target.hasAttribute('data-max-stock')) {
            if (e.target.value === '0' || e.target.value === 0) {
                e.target.value = '';
                e.target.select();
            }
        }
    }, true);
});
</script>
