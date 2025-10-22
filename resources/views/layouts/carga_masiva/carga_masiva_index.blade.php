<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-dark-teal rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h2 class="text-xl font-semibold leading-tight text-gray-800">
                        {{ __('Carga Masiva de Productos') }}
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">Importa múltiples productos desde archivos Excel o CSV</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
            <!-- Alertas -->
                @if (session('status'))
                <div class="mb-6 bg-success-500/10 border-l-4 border-success-500 p-4 rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-success-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm font-medium text-success-600">{{ session('status') }}</p>
                    </div>
                </div>
            @endif

            @if (session('errors_details'))
                <div class="mb-6 bg-secondary-500/10 border-l-4 border-secondary-500 p-4 rounded-lg shadow-sm">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-secondary-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-secondary-600 mb-2">Se encontraron algunos errores:</p>
                            <ul class="list-disc list-inside text-sm text-secondary-600 space-y-1">
                                @foreach (session('errors_details') as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif

            <!-- Formulario de Carga -->
            <div class="p-4 bg-white shadow sm:p-8 sm:rounded-lg">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 text-light-cyan mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Subir Archivo
                    </h3>
                </div>

                <div>
                    <form method="POST" action="{{ route('carga-masiva.upload') }}" enctype="multipart/form-data" id="upload-form">
                    @csrf
                        
                        <!-- Zona de arrastre de archivos -->
                        <div class="mb-6">
                            <div id="drop-zone" class="relative border-2 border-dashed border-gray-300 rounded-lg p-12 text-center hover:border-light-cyan transition-colors duration-200 bg-gray-50 hover:bg-light-cyan/10">
                                <input id="archivo" name="file" type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept=".xlsx,.xls,.csv" required />
                                
                                <div class="pointer-events-none">
                                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <div class="mt-4 flex flex-col items-center">
                                        <p class="text-base font-medium text-gray-700" id="file-name">
                                            Arrastra tu archivo aquí o haz clic para seleccionar
                                        </p>
                                        <p class="text-sm text-gray-500 mt-2">
                                            Formatos soportados: .xlsx, .xls, .csv
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            @error('file')
                                <div class="mt-2 flex items-center text-sm text-red-600">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Botones de acción -->
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-600">
                                <span id="file-info" class="hidden"></span>
                    </div>
                            <button type="submit" class="inline-flex items-center px-6 py-2.5 border border-transparent text-sm font-medium rounded-md text-white bg-light-cyan hover:bg-dark-teal focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-light-cyan transition-colors duration-150 shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                Cargar Archivo
                            </button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Manejo de archivo seleccionado
        const fileInput = document.getElementById('archivo');
        const fileNameDisplay = document.getElementById('file-name');
        const fileInfoDisplay = document.getElementById('file-info');
        const dropZone = document.getElementById('drop-zone');

        fileInput.addEventListener('change', function(e) {
            handleFileSelect(e.target.files[0]);
        });

        // Drag and drop
        dropZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropZone.classList.add('border-light-cyan', 'bg-light-cyan/10');
        });

        dropZone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropZone.classList.remove('border-light-cyan', 'bg-light-cyan/10');
        });

        dropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropZone.classList.remove('border-light-cyan', 'bg-light-cyan/10');
            
            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                handleFileSelect(e.dataTransfer.files[0]);
            }
        });

        function handleFileSelect(file) {
            if (file) {
                const fileName = file.name;
                const fileSize = (file.size / 1024).toFixed(2); // KB
                
                fileNameDisplay.innerHTML = `
                    <svg class="inline w-4 h-4 text-success-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    ${fileName}
                `;
                fileNameDisplay.classList.add('text-success-500', 'font-medium');
                
                fileInfoDisplay.innerHTML = `
                    <svg class="inline w-4 h-4 text-gray-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    ${fileSize} KB
                `;
                fileInfoDisplay.classList.remove('hidden');
            } else {
                fileNameDisplay.textContent = 'Arrastra tu archivo aquí o haz clic para seleccionar';
                fileNameDisplay.classList.remove('text-success-500', 'font-medium');
                fileInfoDisplay.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>
