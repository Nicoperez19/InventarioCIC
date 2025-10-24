@extends('layouts.app')

@section('title', 'Carga Masiva de Insumos')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Carga Masiva de Insumos</h1>
                    <p class="mt-2 text-sm text-gray-600">
                        Sube un archivo Excel (.xlsx, .xls) o CSV para crear tipos de insumo y cargar insumos automáticamente.
                    </p>
                </div>
            </div>
        </div>

        <!-- Formulario de carga -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-8">
                <form id="cargaMasivaForm" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-6">
                        <!-- Información del archivo -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Información del Archivo</h3>
                            <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-blue-800">Formato del archivo</h3>
                                        <div class="mt-2 text-sm text-blue-700">
                                            <ul class="list-disc list-inside space-y-1">
                                                <li><strong>Hojas del Excel:</strong> Cada hoja creará un "Tipo de Insumo"</li>
                                                <li><strong>Fila 4:</strong> Contiene los headers de las columnas</li>
                                                <li><strong>Columna B:</strong> Código del insumo (se genera automáticamente si está vacío)</li>
                                                <li><strong>Columna C:</strong> Nombre del insumo (obligatorio)</li>
                                                <li><strong>Columna D:</strong> Unidad de medida (se crea automáticamente si no existe)</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Selector de archivo -->
                        <div>
                            <label for="archivo" class="block text-sm font-medium text-gray-700 mb-2">
                                Seleccionar archivo
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors duration-200" 
                                 id="dropzone">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="archivo" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                            <span>Subir archivo</span>
                                            <input id="archivo" name="archivo" type="file" class="sr-only" accept=".xlsx,.xls,.csv" required>
                                        </label>
                                        <p class="pl-1">o arrastra y suelta aquí</p>
                                    </div>
                                    <p class="text-xs text-gray-500">Excel (.xlsx, .xls) o CSV hasta 10MB</p>
                                </div>
                            </div>
                        </div>

                        <!-- Botón de carga -->
                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
                                    id="btnCargar">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                Procesar Archivo
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Resultados -->
        <div id="resultados" class="mt-8 hidden">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Resultados de la Carga</h3>
                </div>
                <div class="px-6 py-4">
                    <div id="resultadosContenido"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('cargaMasivaForm');
    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('archivo');
    const btnCargar = document.getElementById('btnCargar');
    const resultados = document.getElementById('resultados');
    const resultadosContenido = document.getElementById('resultadosContenido');

    // Drag and drop
    dropzone.addEventListener('dragover', function(e) {
        e.preventDefault();
        dropzone.classList.add('border-indigo-400', 'bg-indigo-50');
    });

    dropzone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        dropzone.classList.remove('border-indigo-400', 'bg-indigo-50');
    });

    dropzone.addEventListener('drop', function(e) {
        e.preventDefault();
        dropzone.classList.remove('border-indigo-400', 'bg-indigo-50');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            updateFileDisplay(files[0]);
        }
    });

    // File input change
    fileInput.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            updateFileDisplay(e.target.files[0]);
        }
    });

    function updateFileDisplay(file) {
        const dropzoneContent = dropzone.querySelector('div');
        dropzoneContent.innerHTML = `
            <div class="flex items-center">
                <svg class="w-8 h-8 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="text-left">
                    <p class="text-sm font-medium text-gray-900">${file.name}</p>
                    <p class="text-xs text-gray-500">${(file.size / 1024 / 1024).toFixed(2)} MB</p>
                </div>
            </div>
        `;
    }

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        
        btnCargar.disabled = true;
        btnCargar.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Procesando...
        `;

        fetch('{{ route("carga-masiva.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarResultados(data.data);
            } else {
                mostrarError(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarError('Error al procesar el archivo');
        })
        .finally(() => {
            btnCargar.disabled = false;
            btnCargar.innerHTML = `
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                Procesar Archivo
            `;
        });
    });

    function mostrarResultados(data) {
        resultadosContenido.innerHTML = `
            <div class="space-y-6">
                <!-- Resumen -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-green-50 border border-green-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800">Tipos de Insumo Creados</h3>
                                <p class="mt-1 text-sm text-green-700">${data.tipos_insumo_creados} tipos de insumo procesados</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Insumos Creados</h3>
                                <p class="mt-1 text-sm text-blue-700">${data.insumos_creados} insumos procesados</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detalles de Tipos de Insumo -->
                ${data.tipos_insumo && data.tipos_insumo.length > 0 ? `
                <div>
                    <h4 class="text-lg font-medium text-gray-900 mb-3">Tipos de Insumo Procesados</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Color</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                ${data.tipos_insumo.map(tipo => `
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${tipo.nombre_tipo}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color: ${tipo.color}; color: white;">
                                                ${tipo.color}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            ${tipo.existe ? '<span class="text-yellow-600">Ya existía</span>' : '<span class="text-green-600">Creado</span>'}
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
                ` : ''}

                <!-- Detalles de Insumos (solo primeros 10) -->
                ${data.insumos && data.insumos.length > 0 ? `
                <div>
                    <h4 class="text-lg font-medium text-gray-900 mb-3">Insumos Creados (mostrando primeros 10)</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                ${data.insumos.slice(0, 10).map(insumo => `
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${insumo.codigo_barra}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${insumo.nombre_insumo}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${insumo.tipo_insumo}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                        ${data.insumos.length > 10 ? `<p class="mt-2 text-sm text-gray-500">... y ${data.insumos.length - 10} insumos más</p>` : ''}
                    </div>
                </div>
                ` : ''}
            </div>
        `;
        
        resultados.classList.remove('hidden');
        resultados.scrollIntoView({ behavior: 'smooth' });
    }

    function mostrarError(mensaje) {
        resultadosContenido.innerHTML = `
            <div class="bg-red-50 border border-red-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Error</h3>
                        <p class="mt-1 text-sm text-red-700">${mensaje}</p>
                    </div>
                </div>
            </div>
        `;
        
        resultados.classList.remove('hidden');
        resultados.scrollIntoView({ behavior: 'smooth' });
    }
});
</script>
@endsection