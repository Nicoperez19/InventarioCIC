<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center space-x-3 sm:space-x-4">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg sm:w-10 sm:h-10 bg-gradient-to-br from-primary-400 to-primary-500">
                        <svg class="w-4 h-4 text-white sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <h2 class="text-lg font-semibold leading-tight text-gray-800 truncate sm:text-xl">
                        {{ __('Gestión de Facturas') }}
                    </h2>
                    <p class="hidden mt-1 text-xs text-gray-600 sm:text-sm sm:block">Administra y organiza las facturas del sistema</p>
                </div>
            </div>
            <div class="flex-shrink-0 w-full sm:w-auto">
                <div class="flex flex-col gap-2 sm:flex-row">
                    <button @click="$dispatch('open-modal', 'upload-factura')" 
                            class="inline-flex items-center justify-center w-full px-3 py-2 text-xs font-medium text-white transition-all duration-150 rounded-lg shadow-sm sm:w-auto sm:px-4 sm:text-sm bg-primary-500 hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-400">
                        <svg class="w-3 h-3 mr-1 sm:w-4 sm:h-4 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <span class="sm:hidden">Subir</span>
                        <span class="hidden sm:inline">Subir Factura</span>
                    </button>
                 
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
            <!-- Mensajes de éxito/error -->
            @if (session('success'))
                <div class="p-4 bg-white border-l-4 rounded-lg shadow-sm border-green-500">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <div class="p-4 bg-white border-l-4 rounded-lg shadow-sm border-red-500">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-800">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="p-4 bg-white shadow sm:p-8 sm:rounded-lg">
                <livewire:tables.facturas-table />
            </div>
        </div>
    </div>

    <!-- Modal para subir factura -->
    <x-modal name="upload-factura" title="Subir Factura" maxWidth="2xl">
        <form id="upload-factura-form" method="POST" action="{{ route('facturas.upload') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="p-6 border rounded-lg shadow-sm bg-gray-50 border-neutral-200">
                <div class="flex items-center pb-3 mb-4 border-b border-neutral-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary-100">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-semibold text-gray-900">Información de la Factura</h3>
                        <p class="text-sm text-gray-500">Selecciona el proveedor y sube el archivo</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- Proveedor -->
                    <div>
                        <label for="proveedor_id" class="block mb-2 text-sm font-medium text-gray-700">
                            Proveedor <span class="text-red-500">*</span>
                        </label>
                        <select id="proveedor_id" name="proveedor_id" required
                                class="w-full px-3 py-2 transition-colors border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-400">
                            <option value="">Seleccionar proveedor</option>
                            @foreach(\App\Models\Proveedor::orderBy('nombre_proveedor')->get() as $proveedor)
                                <option value="{{ $proveedor->id }}">{{ $proveedor->nombre_proveedor }}</option>
                            @endforeach
                        </select>
                        @error('proveedor_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Archivo -->
                    <div>
                        <label for="archivo" class="block mb-2 text-sm font-medium text-gray-700">
                            Archivo <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center justify-center w-full">
                            <label for="archivo" class="flex flex-col items-center justify-center w-full h-32 transition-colors border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500">
                                        <span class="font-semibold">Click para seleccionar</span> o arrastra el archivo aquí
                                    </p>
                                    <p class="text-xs text-gray-500">PDF o DOC (MAX. 10MB)</p>
                                </div>
                                <input type="file" id="archivo" name="archivo" accept=".pdf,.doc,.docx" required
                                       class="hidden" />
                            </label>
                        </div>
                        <p id="nombre-archivo" class="hidden mt-2 text-sm text-gray-600"></p>
                        @error('archivo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Observaciones -->
                    <div>
                        <label for="observaciones" class="block mb-2 text-sm font-medium text-gray-700">
                            Observaciones
                        </label>
                        <textarea id="observaciones" name="observaciones" rows="3"
                                  class="w-full px-3 py-2 transition-colors border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-400"
                                  placeholder="Observaciones adicionales (opcional)"></textarea>
                        @error('observaciones')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="flex items-center justify-end px-6 py-4 pt-6 -mx-6 -mb-6 space-x-3 rounded-b-lg bg-gray-50">
                <button type="button" @click="$dispatch('close-modal')"
                        class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-400 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Cancelar
                </button>
                <button type="submit" 
                        class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-secondary-500 rounded-lg shadow-sm hover:bg-secondary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-400 transition-all duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Subir Factura
                </button>
            </div>
        </form>
    </x-modal>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const archivoInput = document.getElementById('archivo');
            const nombreArchivo = document.getElementById('nombre-archivo');

            if (archivoInput && nombreArchivo) {
                archivoInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        nombreArchivo.textContent = 'Archivo seleccionado: ' + file.name;
                        nombreArchivo.classList.remove('hidden');
                    } else {
                        nombreArchivo.classList.add('hidden');
                    }
                });
            }
        });
    </script>
</x-app-layout>