<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center space-x-3 sm:space-x-4">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg sm:w-10 sm:h-10 bg-gradient-to-br from-secondary-400 to-secondary-500">
                        <svg class="w-4 h-4 text-white sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <h2 class="text-lg font-semibold leading-tight text-gray-800 truncate sm:text-xl">
                        {{ __('Editar insumo') }} - {{ $producto->id_producto }}
                    </h2>
                    <p class="hidden mt-1 text-xs text-gray-600 sm:text-sm sm:block">Modifica la información del insumo {{ $producto->nombre_producto }}</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('insumos.update', $producto->id_producto) }}" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Información básica -->
                    <div class="pb-6 border-b border-gray-200">
                        <h3 class="flex items-center text-lg font-semibold text-gray-900">
                            <svg class="w-5 h-5 mr-2 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            Información básica
                        </h3>
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700">ID del Producto</label>
                                <input type="text" value="{{ $producto->id_producto }}" 
                                       class="w-full px-3 py-2 text-gray-500 bg-gray-100 border border-gray-300 rounded-md shadow-sm" 
                                       disabled>
                                <p class="mt-1 text-xs text-gray-500">El ID del producto no se puede modificar</p>
                            </div>

                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700">Código del Producto</label>
                                <input type="text" name="codigo_producto" value="{{ old('codigo_producto', $producto->codigo_producto) }}" 
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400 transition-colors" 
                                       required placeholder="Ej: COD001">
                                @error('codigo_producto')
                                    <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block mb-2 text-sm font-medium text-gray-700">Nombre del Producto</label>
                                <input type="text" name="nombre_producto" value="{{ old('nombre_producto', $producto->nombre_producto) }}" 
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400 transition-colors" 
                                       required placeholder="Ej: Laptop Dell Inspiron 15">
                                @error('nombre_producto')
                                    <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Información de inventario -->
                    <div class="pb-6 border-b border-gray-200">
                        <h3 class="flex items-center text-lg font-semibold text-gray-900">
                            <svg class="w-5 h-5 mr-2 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Información de inventario
                        </h3>
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700">Stock Mínimo</label>
                                <input type="number" name="stock_minimo" value="{{ old('stock_minimo', $producto->stock_minimo) }}" 
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400 transition-colors" 
                                       required min="0" placeholder="0">
                                @error('stock_minimo')
                                    <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700">Stock Actual</label>
                                <input type="number" name="stock_actual" value="{{ old('stock_actual', $producto->stock_actual) }}" 
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400 transition-colors" 
                                       required min="0" placeholder="0">
                                @error('stock_actual')
                                    <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700">Unidad de Medida</label>
                                <select name="id_unidad" 
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400 transition-colors" 
                                        required>
                                    @foreach($unidades as $u)
                                        <option value="{{ $u->id_unidad }}" {{ old('id_unidad', $producto->id_unidad) === $u->id_unidad ? 'selected' : '' }}>
                                            {{ $u->nombre_unidad_medida }} ({{ $u->id_unidad }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_unidad')
                                    <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Observaciones -->
                    <div class="pb-6">
                        <h3 class="flex items-center text-lg font-semibold text-gray-900">
                            <svg class="w-5 h-5 mr-2 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Observaciones
                        </h3>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">Observaciones del Producto</label>
                            <textarea name="observaciones" 
                                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400 transition-colors" 
                                      rows="3" 
                                      placeholder="Información adicional sobre el producto...">{{ old('observaciones', $producto->observaciones) }}</textarea>
                            @error('observaciones')
                                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex items-center justify-end pt-6 space-x-3 border-t border-gray-200">
                        <a href="{{ route('insumos.index') }}" 
                           class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-400 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-primary-500 to-secondary-500 rounded-lg shadow-sm hover:from-primary-600 hover:to-secondary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-400 transition-all duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>


