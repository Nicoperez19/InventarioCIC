<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center space-x-3 sm:space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-primary-400 to-primary-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                </div>
                <div class="min-w-0 flex-1">
                    <h2 class="text-lg sm:text-xl font-semibold leading-tight text-gray-800 truncate">
                        {{ __('Gestión de Proveedores') }}
                    </h2>
                    <p class="text-xs sm:text-sm text-gray-600 mt-1 hidden sm:block">Administra y organiza los proveedores del sistema</p>
                </div>
            </div>
            <div class="flex-shrink-0 w-full sm:w-auto">
                <button @click="$dispatch('open-modal', 'create-proveedor')" 
                   class="inline-flex items-center justify-center w-full sm:w-auto px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-white bg-secondary-500 rounded-lg hover:bg-secondary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-400 transition-all duration-150 shadow-sm">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span class="sm:hidden">Agregar</span>
                    <span class="hidden sm:inline">Agregar Proveedor</span>
                </button>
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
                <livewire:tables.proveedores-table />
            </div>
        </div>
    </div>

    <!-- Modal para crear proveedor -->
    <x-modal name="create-proveedor" title="Agregar Nuevo Proveedor" maxWidth="2xl">
        <form id="create-proveedor-form" method="POST" action="{{ route('proveedores.store') }}" class="space-y-6">
            @csrf
            
            <!-- Datos del Proveedor -->
            <div class="p-6 bg-gray-50 border border-neutral-200 rounded-lg shadow-sm">
                <div class="flex items-center mb-4 pb-3 border-b border-neutral-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary-100">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-semibold text-gray-900">Datos del Proveedor</h3>
                        <p class="text-sm text-gray-500">Define la información del proveedor</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <!-- RUT -->
                    <div>
                        <label for="rut" class="block mb-2 text-sm font-medium text-gray-700">
                            RUT <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="rut" name="rut" required
                               class="w-full px-3 py-2 transition-colors border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-400"
                               placeholder="12.345.678-9" oninput="formatRun(this)">
                        @error('rut')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nombre del Proveedor -->
                    <div>
                        <label for="nombre_proveedor" class="block mb-2 text-sm font-medium text-gray-700">
                            Nombre del Proveedor <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nombre_proveedor" name="nombre_proveedor" required
                               class="w-full px-3 py-2 transition-colors border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-400"
                               placeholder="Ej: Empresa XYZ S.A." maxlength="255">
                        @error('nombre_proveedor')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <label for="telefono" class="block mb-2 text-sm font-medium text-gray-700">
                            Teléfono <span class="text-gray-500 text-xs">(opcional)</span>
                        </label>
                        <input type="text" id="telefono" name="telefono"
                               class="w-full px-3 py-2 transition-colors border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-400"
                               placeholder="Ej: +56 9 1234 5678" maxlength="20">
                        @error('telefono')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="flex items-center justify-end pt-6 space-x-3 bg-gray-50 -mx-6 -mb-6 px-6 py-4 rounded-b-lg">
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
                    Crear Proveedor
                </button>
            </div>
        </form>
    </x-modal>

    <!-- Modal para editar proveedor -->
    <x-modal name="edit-proveedor" title="Editar Proveedor" maxWidth="2xl">
        <form id="edit-proveedor-form" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Datos del Proveedor -->
            <div class="p-6 bg-gray-50 border border-neutral-200 rounded-lg shadow-sm">
                <div class="flex items-center mb-4 pb-3 border-b border-neutral-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary-100">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-semibold text-gray-900">Datos del Proveedor</h3>
                        <p class="text-sm text-gray-500">Define la información del proveedor</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <!-- RUT -->
                    <div>
                        <label for="edit-rut" class="block mb-2 text-sm font-medium text-gray-700">
                            RUT <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="edit-rut" name="rut" required
                               class="w-full px-3 py-2 transition-colors border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-400"
                               placeholder="12.345.678-9" oninput="formatRun(this)">
                        @error('rut')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nombre del Proveedor -->
                    <div>
                        <label for="edit-nombre_proveedor" class="block mb-2 text-sm font-medium text-gray-700">
                            Nombre del Proveedor <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="edit-nombre_proveedor" name="nombre_proveedor" required
                               class="w-full px-3 py-2 transition-colors border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-400"
                               placeholder="Ej: Empresa XYZ S.A." maxlength="255">
                        @error('nombre_proveedor')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <label for="edit-telefono" class="block mb-2 text-sm font-medium text-gray-700">
                            Teléfono <span class="text-gray-500 text-xs">(opcional)</span>
                        </label>
                        <input type="text" id="edit-telefono" name="telefono"
                               class="w-full px-3 py-2 transition-colors border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-400"
                               placeholder="Ej: +56 9 1234 5678" maxlength="20">
                        @error('telefono')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="flex items-center justify-end pt-6 space-x-3 bg-gray-50 -mx-6 -mb-6 px-6 py-4 rounded-b-lg">
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
                    Actualizar Proveedor
                </button>
            </div>
        </form>
    </x-modal>

    <script>
        function formatRun(input) {
            let value = input.value.replace(/[^0-9kK]/g, '').toUpperCase();
            
            if (value.length <= 7) {
                input.value = value;
            } else if (value.length === 8) {
                input.value = value.substring(0, 7) + '-' + value.substring(7, 8);
            } else if (value.length === 9) {
                input.value = value.substring(0, 8) + '-' + value.substring(8, 9);
            } else {
                input.value = value.substring(0, 8) + '-' + value.substring(8, 9);
            }
            
            input.dispatchEvent(new Event('input', { bubbles: true }));
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            // Manejo del formulario de crear proveedor
            const createForm = document.getElementById('create-proveedor-form');
            if (createForm) {
                createForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(createForm);
                    const submitButton = createForm.querySelector('button[type="submit"]');
                    const originalText = submitButton.innerHTML;
                    
                    // Deshabilitar botón durante el envío
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Creando...';
                    
                    // Crear token CSRF si no existe
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                                     formData.get('_token');
                    
                    fetch('{{ route("proveedores.store") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Cerrar modal
                            window.dispatchEvent(new CustomEvent('close-modal'));
                            
                            // Limpiar formulario
                            createForm.reset();
                            
                            // Recargar la página para mostrar los cambios
                            window.location.reload();
                        } else {
                            // Mostrar errores
                            let errorMessage = data.message || 'Error al crear el proveedor';
                            if (data.errors) {
                                const errorList = Object.values(data.errors).flat().join('\n');
                                errorMessage += '\n\n' + errorList;
                            }
                            alert(errorMessage);
                            submitButton.disabled = false;
                            submitButton.innerHTML = originalText;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error al crear el proveedor. Por favor, intenta nuevamente.');
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalText;
                    });
                });
            }

            // Manejo del formulario de editar proveedor
            const editForm = document.getElementById('edit-proveedor-form');
            if (editForm) {
                editForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(editForm);
                    const submitButton = editForm.querySelector('button[type="submit"]');
                    const originalText = submitButton.innerHTML;
                    const proveedorId = editForm.dataset.proveedorId;
                    
                    // Deshabilitar botón durante el envío
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Actualizando...';
                    
                    // Crear token CSRF si no existe
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                                     formData.get('_token');
                    
                    fetch(`{{ route('proveedores.update', ':id') }}`.replace(':id', proveedorId), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Cerrar modal
                            window.dispatchEvent(new CustomEvent('close-modal'));
                            
                            // Limpiar formulario
                            editForm.reset();
                            
                            // Recargar la página para mostrar los cambios
                            window.location.reload();
                        } else {
                            // Mostrar errores
                            let errorMessage = data.message || 'Error al actualizar el proveedor';
                            if (data.errors) {
                                const errorList = Object.values(data.errors).flat().join('\n');
                                errorMessage += '\n\n' + errorList;
                            }
                            alert(errorMessage);
                            submitButton.disabled = false;
                            submitButton.innerHTML = originalText;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error al actualizar el proveedor. Por favor, intenta nuevamente.');
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalText;
                    });
                });
            }

            // Manejar apertura del modal de edición
            window.addEventListener('open-edit-modal', function(event) {
                const proveedor = event.detail;
                if (proveedor) {
                    document.getElementById('edit-rut').value = proveedor.rut || '';
                    document.getElementById('edit-nombre_proveedor').value = proveedor.nombre_proveedor || '';
                    document.getElementById('edit-telefono').value = proveedor.telefono || '';
                    document.getElementById('edit-proveedor-form').dataset.proveedorId = proveedor.id;
                    document.getElementById('edit-proveedor-form').action = `/proveedores/${proveedor.id}`;
                    
                    window.dispatchEvent(new CustomEvent('open-modal', { detail: 'edit-proveedor' }));
                }
            });
        });
    </script>
</x-app-layout>