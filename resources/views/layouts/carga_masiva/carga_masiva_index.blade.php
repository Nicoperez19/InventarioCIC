<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-dark-teal">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h2 class="text-xl font-semibold leading-tight text-gray-800">
                        {{ __('Carga Masiva de Insumos') }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-600">Importa insumos desde archivos Excel o CSV</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
            
            <!-- Mensajes de éxito/error -->
            @if (session('success'))
                <div class="p-4 border border-green-200 rounded-lg bg-green-50">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 border border-red-200 rounded-lg bg-red-50">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Formulario de carga -->
            <div class="p-4 bg-white shadow sm:p-8 sm:rounded-lg">
                <form action="{{ route('carga-masiva.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <!-- Instrucciones -->
                    <div class="mb-6">
                        <h3 class="flex items-center text-lg font-semibold text-gray-900">
                            <svg class="w-5 h-5 mr-2 text-light-cyan" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Instrucciones para la Carga Masiva
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">Formato y estructura requerida del archivo</p>
                    </div>

                    <div class="p-4 mb-6 border border-blue-200 rounded-lg bg-blue-50">
                        <div class="space-y-2 text-blue-700">
                            <p><strong>Formato del archivo:</strong> Excel (.xlsx, .xls) o CSV</p>
                                    <p><strong>Estructura requerida:</strong></p>
                                    <ul class="ml-4 space-y-1 list-disc list-inside">
                                        <li>Los nombres de las hojas se convertirán en <strong>Tipos de Insumo</strong></li>
                                        <li>Los datos de insumos deben comenzar desde la <strong>fila 4</strong></li>
                                        <li><strong>Columna B:</strong> Código del insumo (opcional, se genera automáticamente si está vacío)</li>
                                        <li><strong>Columna C:</strong> Nombre del insumo (obligatorio)</li>
                                        <li><strong>Columna D:</strong> Unidad de medida (opcional, se crea automáticamente si no existe)</li>
                                        <li><strong>Columna E:</strong> Stock actual (opcional, se establece en 0 si está vacío o no es numérico)</li>
                                    </ul>
                        </div>
                    </div>

                    <!-- Descarga de plantilla -->
                    <div class="mb-6">
                        <h3 class="flex items-center mb-4 text-lg font-semibold text-gray-900">
                            <svg class="w-5 h-5 mr-2 text-light-cyan" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Plantilla de Ejemplo
                        </h3>
                        
                        <div class="p-4 border border-green-200 rounded-lg bg-green-50">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-green-700">Descarga la plantilla de ejemplo</p>
                                    <p class="mt-1 text-sm text-green-600">Archivo Excel con la estructura correcta para carga masiva</p>
                                </div>
                                <a href="{{ route('carga-masiva.template') }}" 
                                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition-colors duration-150 bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Descargar Plantilla
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Selección de archivo -->
                    <div class="mb-6">
                        <h3 class="flex items-center mb-4 text-lg font-semibold text-gray-900">
                            <svg class="w-5 h-5 mr-2 text-light-cyan" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                            </svg>
                            Seleccionar Archivo
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="archivo" class="block mb-2 text-sm font-medium text-gray-700">
                                    Archivo Excel o CSV
                                </label>
                                <input type="file" 
                                       id="archivo" 
                                       name="archivo" 
                                       accept=".xlsx,.xls,.csv"
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                       required>
                                <p class="mt-1 text-sm text-gray-500">
                                    Formatos soportados: .xlsx, .xls, .csv (máximo 10MB)
                                </p>
                            </div>
                        </div>
                    </div>


                    <!-- Botones -->
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('insumos.index') }}" 
                           class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 transition-colors duration-150 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-2 text-sm font-medium text-white transition-colors duration-150 rounded-lg shadow-sm bg-dark-teal hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-dark-teal">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                            </svg>
                            Procesar Archivo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>