<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-dark-teal rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    {{ __('Agregar nueva unidad') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">Crea una nueva unidad de medida en el sistema</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('unidades.store') }}" class="p-6 space-y-6">
                    @csrf

                    <!-- Información básica -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Información básica</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">ID de Unidad</label>
                                <input type="text" name="id_unidad" value="{{ old('id_unidad') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-dark-teal focus:border-dark-teal" 
                                       required placeholder="Ej: KG, L, M">
                                @error('id_unidad')
                                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nombre de la Unidad</label>
                                <input type="text" name="nombre_unidad" value="{{ old('nombre_unidad') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-dark-teal focus:border-dark-teal" 
                                       required placeholder="Ej: Kilogramo, Litro, Metro">
                                @error('nombre_unidad')
                                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('unidades') }}" 
                           class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-dark-teal">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-dark-teal border border-transparent rounded-md shadow-sm hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-dark-teal">
                            Crear Unidad
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>


