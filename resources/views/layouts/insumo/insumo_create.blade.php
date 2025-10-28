<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-dark-teal rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    {{ __('Agregar nuevo insumo') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">Crea un nuevo insumo en el inventario del sistema</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('insumos.store') }}" class="p-6 space-y-6">
                    @csrf

                    <!-- Información básica -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Información básica</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">ID del Insumo</label>
                                <input type="text" name="id_insumo" value="{{ old('id_insumo') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-dark-teal focus:border-dark-teal" 
                                       required placeholder="Ej: INS001">
                                @error('id_insumo')
                                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Código de Barras</label>
                                <input type="text" name="codigo_barra" value="{{ old('codigo_barra') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-dark-teal focus:border-dark-teal" 
                                       placeholder="Ej: 1234567890123">
                                @error('codigo_barra')
                                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del Insumo</label>
                                <input type="text" name="nombre_insumo" value="{{ old('nombre_insumo') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-dark-teal focus:border-dark-teal" 
                                       required placeholder="Ej: Papel A4 80gr">
                                @error('nombre_insumo')
                                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Información de inventario -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Información de inventario</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Stock Actual</label>
                                <input type="number" name="stock_actual" value="{{ old('stock_actual', 0) }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-dark-teal focus:border-dark-teal" 
                                       required min="0" placeholder="0">
                                @error('stock_actual')
                                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Unidad de Medida</label>
                                <select name="id_unidad" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-dark-teal focus:border-dark-teal" 
                                        required>
                                    <option value="">Seleccione una unidad...</option>
                                    @foreach($unidades as $u)
                                        <option value="{{ $u->id_unidad }}" {{ old('id_unidad') === $u->id_unidad ? 'selected' : '' }}>
                                            {{ $u->nombre_unidad_medida }} ({{ $u->id_unidad }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_unidad')
                                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Observaciones -->
                    <div class="pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Observaciones</h3>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Observaciones del Insumo</label>
                            <textarea name="observaciones" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-dark-teal focus:border-dark-teal" 
                                      rows="3" 
                                      placeholder="Información adicional sobre el insumo...">{{ old('observaciones') }}</textarea>
                            @error('observaciones')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('insumos.index') }}" 
                           class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-dark-teal">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-dark-teal border border-transparent rounded-md shadow-sm hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-dark-teal">
                            Crear Insumo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
