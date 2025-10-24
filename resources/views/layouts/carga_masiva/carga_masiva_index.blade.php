<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-dark-teal rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h2 class="text-xl font-semibold leading-tight text-gray-800">
                        {{ __('Carga Masiva de Insumos') }}
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">Importa insumos desde archivos Excel o CSV</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
            
            <!-- Mensajes de éxito/error -->
            @if (session('success'))
                <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 text-light-cyan mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Instrucciones para la Carga Masiva
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Formato y estructura requerida del archivo</p>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <div class="text-blue-700 space-y-2">
                            <p><strong>Formato del archivo:</strong> Excel (.xlsx, .xls) o CSV</p>
                            <p><strong>Estructura requerida:</strong></p>
                            <ul class="list-disc list-inside ml-4 space-y-1">
                                <li>Los nombres de las hojas se convertirán en <strong>Tipos de Insumo</strong></li>
                                <li>Los datos de insumos deben comenzar desde la <strong>fila 4</strong></li>
                                <li><strong>Columna B:</strong> Código del insumo (opcional, se genera automáticamente si está vacío)</li>
                                <li><strong>Columna C:</strong> Nombre del insumo (obligatorio)</li>
                                <li><strong>Columna D:</strong> Unidad de medida (opcional, se crea automáticamente si no existe)</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Selección de archivo -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center mb-4">
                            <svg class="w-5 h-5 text-light-cyan mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                            </svg>
                            Seleccionar Archivo
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="archivo" class="block text-sm font-medium text-gray-700 mb-2">
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

                    <!-- Ejemplo de estructura -->
                    <div class="mb-6">
                        <h4 class="text-md font-semibold text-gray-900 flex items-center mb-3">
                            <svg class="w-4 h-4 text-light-cyan mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Ejemplo de Estructura del Archivo
                        </h4>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="text-yellow-700 text-sm">
                                <p><strong>Hoja 1:</strong> "Medicamentos"</p>
                                <p><strong>Hoja 2:</strong> "Materiales de Oficina"</p>
                                <p><strong>Hoja 3:</strong> "Equipos Médicos"</p>
                                <div class="mt-2">
                                    <p><strong>Datos en cada hoja (desde fila 4):</strong></p>
                                    <table class="mt-2 border border-yellow-300 text-xs">
                                        <tr class="bg-yellow-100">
                                            <td class="border border-yellow-300 px-2 py-1"><strong>Columna B</strong></td>
                                            <td class="border border-yellow-300 px-2 py-1"><strong>Columna C</strong></td>
                                            <td class="border border-yellow-300 px-2 py-1"><strong>Columna D</strong></td>
                                        </tr>
                                        <tr>
                                            <td class="border border-yellow-300 px-2 py-1">MED001</td>
                                            <td class="border border-yellow-300 px-2 py-1">Paracetamol 500mg</td>
                                            <td class="border border-yellow-300 px-2 py-1">Caja</td>
                                        </tr>
                                        <tr>
                                            <td class="border border-yellow-300 px-2 py-1"></td>
                                            <td class="border border-yellow-300 px-2 py-1">Ibuprofeno 400mg</td>
                                            <td class="border border-yellow-300 px-2 py-1">Caja</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('insumos.index') }}" 
                           class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-2 text-sm font-medium text-white bg-dark-teal rounded-lg hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-dark-teal transition-colors duration-150 shadow-sm">
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