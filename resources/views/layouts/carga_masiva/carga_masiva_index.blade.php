<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center space-x-3 sm:space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-primary-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                        </svg>
                    </div>
                </div>
                <div class="min-w-0 flex-1">
                    <h2 class="text-lg sm:text-xl font-semibold leading-tight text-gray-800 truncate">
                        {{ __('Gestión de Carga Masiva') }}
                    </h2>
                    <p class="text-xs sm:text-sm text-gray-600 mt-1 hidden sm:block">Importa insumos desde archivos Excel o CSV</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="mx-auto space-y-4 max-w-7xl sm:px-6 lg:px-8">
            
            <!-- Mensajes de éxito/error -->
            @if (session('success'))
                <div class="p-3 bg-white border-l-4 border-secondary-500 rounded-lg shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-secondary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="p-3 bg-white border-l-4 border-danger-500 rounded-lg shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-danger-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-800">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Formulario de carga -->
            <div class="p-4 bg-white shadow sm:p-6 sm:rounded-lg">
                <form action="{{ route('carga-masiva.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    
                    <!-- Grid con Instrucciones y Plantilla lado a lado -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <!-- Instrucciones -->
                        <div class="border border-primary-200 rounded-lg p-4 bg-primary-50">
                            <h3 class="text-base font-semibold text-gray-900 flex items-center mb-3">
                                <svg class="w-5 h-5 text-primary-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Instrucciones para la Carga Masiva
                            </h3>
                            <div class="space-y-3 text-xs text-gray-700">
                                <div class="flex items-start">
                                    <svg class="w-4 h-4 text-primary-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <p class="font-semibold text-gray-900 mb-1">Formato del archivo:</p>
                                        <p class="text-gray-600">Excel (.xlsx, .xls) o CSV</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <svg class="w-4 h-4 text-primary-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <div>
                                        <p class="font-semibold text-gray-900 mb-1">Estructura requerida:</p>
                                        <ul class="ml-0 space-y-1 list-none text-gray-600">
                                            <li class="flex items-start">
                                                <span class="text-primary-500 mr-1">•</span>
                                                <span>Los <strong class="text-gray-900">nombres de las hojas</strong> se convertirán en <strong class="text-gray-900">Tipos de Insumo</strong></span>
                                            </li>
                                            <li class="flex items-start">
                                                <span class="text-primary-500 mr-1">•</span>
                                                <span>Los datos de insumos deben comenzar desde la <strong class="text-gray-900">fila 4</strong></span>
                                            </li>
                                            <li class="flex items-start">
                                                <span class="text-primary-500 mr-1">•</span>
                                                <span><strong class="text-gray-900">Columna B:</strong> Código del insumo (opcional, se genera automáticamente si está vacío)</span>
                                            </li>
                                            <li class="flex items-start">
                                                <span class="text-primary-500 mr-1">•</span>
                                                <span><strong class="text-gray-900">Columna C:</strong> Nombre del insumo <strong class="text-red-600">(obligatorio)</strong></span>
                                            </li>
                                            <li class="flex items-start">
                                                <span class="text-primary-500 mr-1">•</span>
                                                <span><strong class="text-gray-900">Columna D:</strong> Unidad de medida (opcional, se crea automáticamente si no existe)</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Descarga de plantilla -->
                        <div class="border border-secondary-300 rounded-lg p-5 bg-gradient-to-br from-secondary-50 to-secondary-100 shadow-sm hover:shadow-md transition-shadow duration-200">
                            <div class="flex flex-col h-full">
                                <div class="flex items-center mb-3">
                                    <div class="flex items-center justify-center w-10 h-10 bg-secondary-500 rounded-lg mr-3">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-base font-semibold text-gray-900">Plantilla de Ejemplo</h3>
                                        <p class="text-xs text-gray-600 mt-0.5">Estructura lista para usar</p>
                                    </div>
                                </div>
                                <div class="mt-auto">
                                    <p class="text-xs text-gray-700 mb-3 leading-relaxed">
                                        Descarga la plantilla Excel con la estructura correcta y formato predefinido para facilitar tu carga masiva de insumos.
                                    </p>
                                    <a href="{{ route('carga-masiva.template') }}" 
                                       class="inline-flex items-center justify-center w-full px-4 py-2.5 text-sm font-semibold text-white bg-secondary-500 rounded-lg hover:bg-secondary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-400 transition-all duration-150 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                        Descargar Plantilla Excel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Selección de archivo -->
                    <div>
                        <h3 class="text-base font-semibold text-gray-900 flex items-center mb-2">
                            <svg class="w-4 h-4 text-primary-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                            </svg>
                            Seleccionar Archivo
                        </h3>
                        <p class="text-xs text-gray-500 mb-2">Arrastra y suelta tu archivo o haz clic para seleccionarlo</p>
                        
                        <div class="relative">
                            <label for="archivo" class="cursor-pointer">
                                <div id="drop-zone" class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-primary-400 hover:bg-primary-50 transition-all duration-200 bg-gray-50">
                                    <input type="file" 
                                           id="archivo" 
                                           name="archivo" 
                                           accept=".xlsx,.xls,.csv"
                                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                           required>
                                    <div class="space-y-3">
                                        <div class="flex justify-center">
                                            <div class="flex items-center justify-center w-12 h-12 bg-primary-100 rounded-full">
                                                <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700">
                                                <span class="text-primary-600">Haz clic para seleccionar</span> o arrastra y suelta
                                            </p>
                                            <p id="file-name" class="mt-1 text-xs text-gray-500 hidden">
                                                Archivo: <span class="font-medium text-gray-700"></span>
                                            </p>
                                        </div>
                                        <div class="flex items-center justify-center space-x-3 text-xs text-gray-400">
                                            <span>.xlsx</span>
                                            <span>•</span>
                                            <span>.xls</span>
                                            <span>•</span>
                                            <span>.csv</span>
                                            <span class="text-gray-500 ml-2">(máx. 10MB)</span>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end pt-3 border-t border-gray-200 space-y-2 sm:space-y-0 sm:space-x-3">
                        <a href="{{ route('insumos.index') }}" 
                           class="inline-flex items-center justify-center px-5 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition-all duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center justify-center px-6 py-2 text-sm font-medium text-white bg-primary-500 rounded-lg shadow-sm hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-400 transition-all duration-150">
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('archivo');
            const dropZone = document.getElementById('drop-zone');
            const fileName = document.getElementById('file-name');
            const fileNameSpan = fileName.querySelector('span');

            // Mostrar nombre del archivo cuando se selecciona
            fileInput.addEventListener('change', function(e) {
                if (e.target.files.length > 0) {
                    const file = e.target.files[0];
                    fileNameSpan.textContent = file.name;
                    fileName.classList.remove('hidden');
                    dropZone.classList.remove('border-gray-300');
                    dropZone.classList.add('border-primary-500', 'bg-primary-50');
                }
            });

            // Efectos de drag and drop
            dropZone.addEventListener('dragover', function(e) {
                e.preventDefault();
                dropZone.classList.remove('border-gray-300');
                dropZone.classList.add('border-primary-500', 'bg-primary-100', 'border-solid');
            });

            dropZone.addEventListener('dragleave', function(e) {
                e.preventDefault();
                if (!fileInput.files.length) {
                    dropZone.classList.remove('border-primary-500', 'bg-primary-100', 'border-solid');
                    dropZone.classList.add('border-gray-300');
                }
            });

            dropZone.addEventListener('drop', function(e) {
                e.preventDefault();
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(files[0]);
                    fileInput.files = dataTransfer.files;
                    const event = new Event('change', { bubbles: true });
                    fileInput.dispatchEvent(event);
                }
                dropZone.classList.remove('border-primary-500', 'bg-primary-100', 'border-solid');
                if (fileInput.files.length) {
                    dropZone.classList.add('border-primary-500', 'bg-primary-50');
                } else {
                    dropZone.classList.add('border-gray-300');
                }
            });
        });
    </script>
</x-app-layout>