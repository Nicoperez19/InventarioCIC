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
                        {{ __('Gestión de departamentos') }}
                    </h2>
                    <p class="text-xs sm:text-sm text-gray-600 mt-1 hidden sm:block">Administra y organiza los departamentos del sistema</p>
                </div>
            </div>
            <div class="flex-shrink-0 w-full sm:w-auto">
                <button @click="$dispatch('open-modal', 'create-departamento')" 
                   class="inline-flex items-center justify-center w-full sm:w-auto px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-white bg-secondary-500 rounded-lg hover:bg-secondary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-400 transition-all duration-150 shadow-sm">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span class="sm:hidden">Agregar</span>
                    <span class="hidden sm:inline">Agregar Departamento</span>
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
            <div class="p-4 bg-white shadow sm:p-8 sm:rounded-lg">
                <livewire:tables.departamentos-table />
            </div>
        </div>
    </div>

    <!-- Modal para crear departamento -->
    <x-modal name="create-departamento" title="Agregar Nuevo Departamento" maxWidth="2xl">
        <form id="create-departamento-form" method="POST" action="{{ route('departamentos.store') }}" class="space-y-6">
            @csrf
            
            <!-- Datos del Departamento -->
            <div class="p-6 bg-gray-50 border border-neutral-200 rounded-lg shadow-sm">
                <div class="flex items-center mb-4 pb-3 border-b border-neutral-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary-100">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-semibold text-gray-900">Datos del Departamento</h3>
                        <p class="text-sm text-gray-500">Define el identificador y nombre del departamento</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <!-- ID del Departamento -->
                    <div>
                        <label for="id_depto" class="block mb-2 text-sm font-medium text-gray-700">
                            ID del Departamento <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="id_depto" name="id_depto" required
                               class="w-full px-3 py-2 transition-colors border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-400"
                               placeholder="Ej: CIC_admin, DEPT_001" maxlength="20">
                        <p class="mt-1.5 text-xs text-gray-500">Código único para identificar el departamento</p>
                        @error('id_depto')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nombre del Departamento -->
                    <div>
                        <label for="nombre_depto" class="block mb-2 text-sm font-medium text-gray-700">
                            Nombre del Departamento <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nombre_depto" name="nombre_depto" required
                               class="w-full px-3 py-2 transition-colors border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-400"
                               placeholder="Ej: Administración, Recursos Humanos" maxlength="255">
                        <p class="mt-1.5 text-xs text-gray-500">Nombre descriptivo completo</p>
                        @error('nombre_depto')
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
                    Crear Departamento
                </button>
            </div>
        </form>
    </x-modal>

    <!-- Modal para editar departamento -->
    <x-modal name="edit-departamento" title="Editar Departamento" maxWidth="2xl">
        <form id="edit-departamento-form" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <input type="hidden" id="edit-departamento-id" name="id_depto" value="">
            
            <!-- Datos del Departamento -->
            <div class="p-6 bg-gray-50 border border-neutral-200 rounded-lg shadow-sm">
                <div class="flex items-center mb-4 pb-3 border-b border-neutral-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary-100">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-semibold text-gray-900">Datos del Departamento</h3>
                        <p class="text-sm text-gray-500">Actualiza el identificador y nombre del departamento</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <!-- ID del Departamento (deshabilitado) -->
                    <div>
                        <label for="edit-id_depto" class="block mb-2 text-sm font-medium text-gray-700">
                            ID del Departamento <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="edit-id_depto" name="id_depto" required readonly
                               class="w-full px-3 py-2 transition-colors border border-gray-300 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed"
                               placeholder="Ej: CIC_admin, DEPT_001" maxlength="20">
                        <p class="mt-1.5 text-xs text-gray-500">El ID del departamento no se puede modificar</p>
                    </div>

                    <!-- Nombre del Departamento -->
                    <div>
                        <label for="edit-nombre_depto" class="block mb-2 text-sm font-medium text-gray-700">
                            Nombre del Departamento <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="edit-nombre_depto" name="nombre_depto" required
                               class="w-full px-3 py-2 transition-colors border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-400"
                               placeholder="Ej: Administración, Recursos Humanos" maxlength="255">
                        <p class="mt-1.5 text-xs text-gray-500">Nombre descriptivo completo</p>
                        @error('nombre_depto')
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
                    Actualizar Departamento
                </button>
            </div>
        </form>
    </x-modal>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Manejo del formulario de crear departamento
            const createForm = document.getElementById('create-departamento-form');
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
                    
                    fetch('{{ route("departamentos.store") }}', {
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
                            let errorMessage = data.message || 'Error al crear el departamento';
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
                        alert('Error al crear el departamento. Por favor, intenta nuevamente.');
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalText;
                    });
                });
            }

            // Manejo del formulario de editar departamento
            const editForm = document.getElementById('edit-departamento-form');
            if (editForm) {
                editForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(editForm);
                    const idDepto = document.getElementById('edit-departamento-id').value;
                    const submitButton = editForm.querySelector('button[type="submit"]');
                    const originalText = submitButton.innerHTML;
                    
                    // Deshabilitar botón durante el envío
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Actualizando...';
                    
                    // Crear token CSRF si no existe
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                                     formData.get('_token');
                    
                    fetch(`/departamentos/${idDepto}`, {
                        method: 'PUT',
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
                            
                            // Recargar la página para mostrar los cambios
                            window.location.reload();
                        } else {
                            // Mostrar errores
                            let errorMessage = data.message || 'Error al actualizar el departamento';
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
                        alert('Error al actualizar el departamento. Por favor, intenta nuevamente.');
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalText;
                    });
                });
            }
        });

        // Función global para abrir el modal de editar
        function openEditModal(idDepto) {
            // Verificar que los elementos existan
            const idInput = document.getElementById('edit-id_depto');
            const nombreInput = document.getElementById('edit-nombre_depto');
            const hiddenInput = document.getElementById('edit-departamento-id');
            
            if (!idInput || !nombreInput || !hiddenInput) {
                console.error('Error: No se encontraron los elementos del formulario');
                alert('Error: No se pudo inicializar el formulario de edición');
                return;
            }
            
            // Limpiar formulario
            idInput.value = '';
            nombreInput.value = '';
            hiddenInput.value = '';
            
            // Abrir modal
            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'edit-departamento' }));
            
            // Esperar un momento para que el modal se abra antes de cargar los datos
            setTimeout(() => {
                // Cargar datos del departamento
                fetch(`/departamentos/${encodeURIComponent(idDepto)}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            return response.text().then(text => {
                                try {
                                    return JSON.parse(text);
                                } catch {
                                    throw new Error(`HTTP error! status: ${response.status}, response: ${text}`);
                                }
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success && data.data) {
                            const departamento = data.data;
                            
                            // Llenar campos del formulario
                            if (hiddenInput) hiddenInput.value = departamento.id_depto || '';
                            if (idInput) idInput.value = departamento.id_depto || '';
                            if (nombreInput) nombreInput.value = departamento.nombre_depto || '';
                        } else {
                            alert('Error al cargar los datos del departamento: ' + (data.message || 'Error desconocido'));
                            window.dispatchEvent(new CustomEvent('close-modal'));
                        }
                    })
                    .catch(error => {
                        console.error('Error completo:', error);
                        alert('Error al cargar los datos del departamento: ' + error.message);
                        window.dispatchEvent(new CustomEvent('close-modal'));
                    });
            }, 100);
        }
    </script>
</x-app-layout>


