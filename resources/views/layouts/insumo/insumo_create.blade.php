<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center space-x-3 sm:space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-primary-400 to-primary-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                </div>
                <div class="min-w-0 flex-1">
                    <h2 class="text-lg sm:text-xl font-semibold leading-tight text-gray-800 truncate">
                        {{ __('Agregar nuevo insumo') }}
                    </h2>
                    <p class="text-xs sm:text-sm text-gray-600 mt-1 hidden sm:block">Crea un nuevo insumo en el inventario del sistema</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
            <div class="p-4 bg-white shadow sm:p-8 sm:rounded-lg">
                <form method="POST" action="{{ route('insumos.store') }}" class="space-y-6">
                    @csrf

                    <!-- Información básica -->
                    <div class="p-6 bg-gray-50 border border-neutral-200 rounded-lg shadow-sm">
                        <div class="flex items-center mb-4 pb-3 border-b border-neutral-200">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary-100">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg font-semibold text-gray-900">Información Básica</h3>
                                <p class="text-sm text-gray-500">Datos principales para identificar el insumo</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <!-- ID del Insumo -->
                            <div>
                                <label for="id_insumo" class="block mb-2 text-sm font-medium text-gray-700">
                                    ID del Insumo <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="id_insumo" name="id_insumo" value="{{ old('id_insumo') }}" 
                                       class="w-full px-3 py-2 transition-colors border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-400" 
                                       required placeholder="Ej: INS001">
                                @error('id_insumo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Código de Barras -->
                            <div>
                                <label for="codigo_barra" class="block mb-2 text-sm font-medium text-gray-700">
                                    Código de Barras
                                </label>
                                <input type="text" id="codigo_barra" name="codigo_barra" value="{{ old('codigo_barra') }}" 
                                       class="w-full px-3 py-2 transition-colors border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-400" 
                                       placeholder="Ej: 1234567890123">
                                <p class="mt-1 text-xs text-gray-500">Opcional: código de barras único del insumo</p>
                                @error('codigo_barra')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nombre del Insumo -->
                            <div class="md:col-span-2">
                                <label for="nombre_insumo" class="block mb-2 text-sm font-medium text-gray-700">
                                    Nombre del Insumo <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="nombre_insumo" name="nombre_insumo" value="{{ old('nombre_insumo') }}" 
                                       class="w-full px-3 py-2 transition-colors border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-400" 
                                       required placeholder="Ej: Papel A4 80gr">
                                @error('nombre_insumo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Información de inventario -->
                    <div class="p-6 bg-gray-50 border border-neutral-200 rounded-lg shadow-sm">
                        <div class="flex items-center mb-4 pb-3 border-b border-neutral-200">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-secondary-100">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg font-semibold text-gray-900">Información de Inventario</h3>
                                <p class="text-sm text-gray-500">Stock actual y unidad de medida del insumo</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <!-- Stock Actual -->
                            <div>
                                <label for="stock_actual" class="block mb-2 text-sm font-medium text-gray-700">
                                    Stock Actual <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="stock_actual" name="stock_actual" value="{{ old('stock_actual', 0) }}" 
                                       class="w-full px-3 py-2 transition-colors border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-400" 
                                       required min="0" placeholder="0">
                                <p class="mt-1 text-xs text-gray-500">Cantidad disponible en inventario</p>
                                @error('stock_actual')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Unidad de Medida -->
                            <div>
                                <label for="id_unidad" class="block mb-2 text-sm font-medium text-gray-700">
                                    Unidad de Medida <span class="text-red-500">*</span>
                                </label>
                                <select id="id_unidad" name="id_unidad" 
                                        class="w-full px-3 py-2 transition-colors border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-400" 
                                        required>
                                    <option value="">Seleccionar unidad...</option>
                                    @foreach($unidades as $u)
                                        <option value="{{ $u->id_unidad }}" {{ old('id_unidad') === $u->id_unidad ? 'selected' : '' }}>
                                            {{ $u->nombre_unidad_medida }} ({{ $u->id_unidad }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_unidad')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Observaciones -->
                    <div class="p-6 bg-gray-50 border border-neutral-200 rounded-lg shadow-sm">
                        <div class="flex items-center mb-4 pb-3 border-b border-neutral-200">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary-100">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg font-semibold text-gray-900">Observaciones</h3>
                                <p class="text-sm text-gray-500">Información adicional sobre el insumo</p>
                            </div>
                        </div>

                        <div>
                            <label for="observaciones" class="block mb-2 text-sm font-medium text-gray-700">
                                Observaciones del Insumo
                            </label>
                            <textarea id="observaciones" name="observaciones" 
                                      class="w-full px-3 py-2 transition-colors border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-400" 
                                      rows="4" 
                                      placeholder="Información adicional sobre el insumo...">{{ old('observaciones') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Notas, características especiales o información relevante</p>
                            @error('observaciones')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex items-center justify-end pt-6 space-x-3 bg-gray-50 -mx-6 -mb-6 px-6 py-4 rounded-b-lg">
                        <a href="{{ route('insumos.index') }}" 
                           class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-400 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-secondary-500 rounded-lg shadow-sm hover:bg-secondary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-400 transition-all duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Crear Insumo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
