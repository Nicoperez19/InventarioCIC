<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center space-x-3 sm:space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-primary-400 to-primary-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                </div>
                <div class="min-w-0 flex-1">
                    <h2 class="text-lg sm:text-xl font-semibold leading-tight text-gray-800 truncate">
                        {{ __('Gestión de Tipos de Insumo') }}
                    </h2>
                    <p class="text-xs sm:text-sm text-gray-600 mt-1 hidden sm:block">Administra y organiza los tipos de insumo del sistema</p>
                </div>
            </div>
            <div class="flex-shrink-0 w-full sm:w-auto">
                <button @click="$dispatch('open-modal', 'create-tipo-insumo')" 
                   class="inline-flex items-center justify-center w-full sm:w-auto px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-white bg-secondary-500 rounded-lg hover:bg-secondary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-400 transition-all duration-150 shadow-sm">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span class="sm:hidden">Agregar</span>
                    <span class="hidden sm:inline">Agregar Tipo de Insumo</span>
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
            <div class="p-4 bg-white shadow sm:p-8 sm:rounded-lg">
                <livewire:tables.tipo-insumos-table />
            </div>
        </div>
    </div>

    <!-- Modal para crear tipo de insumo -->
    <x-modal name="create-tipo-insumo" title="Agregar Nuevo Tipo de Insumo" maxWidth="2xl">
        <form id="create-tipo-insumo-form" method="POST" action="{{ route('tipo-insumos.store') }}" class="space-y-6">
            @csrf
            
            <div class="p-6 bg-gray-50 border border-neutral-200 rounded-lg shadow-sm">
                <div class="flex items-center mb-4 pb-3 border-b border-neutral-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary-100">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-semibold text-gray-900">Datos del Tipo de Insumo</h3>
                        <p class="text-sm text-gray-500">Define el nombre del tipo de insumo</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="nombre_tipo" class="block mb-2 text-sm font-medium text-gray-700">
                            Nombre del Tipo de Insumo <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nombre_tipo" name="nombre_tipo" required
                               class="w-full px-3 py-2 transition-colors border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-400"
                               placeholder="Ej: Medicamentos, Materiales, Equipos" maxlength="255">
                        <p class="mt-1.5 text-xs text-gray-500">Nombre descriptivo del tipo de insumo</p>
                        @error('nombre_tipo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

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
                    Crear Tipo de Insumo
                </button>
            </div>
        </form>
    </x-modal>

    <!-- Modal para editar tipo de insumo -->
    <x-modal name="edit-tipo-insumo" title="Editar Tipo de Insumo" maxWidth="2xl">
        <form id="edit-tipo-insumo-form" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <input type="hidden" id="edit-tipo-insumo-id" name="id" value="">
            
            <div class="p-6 bg-gray-50 border border-neutral-200 rounded-lg shadow-sm">
                <div class="flex items-center mb-4 pb-3 border-b border-neutral-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary-100">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-semibold text-gray-900">Datos del Tipo de Insumo</h3>
                        <p class="text-sm text-gray-500">Actualiza el nombre del tipo de insumo</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="edit-nombre_tipo" class="block mb-2 text-sm font-medium text-gray-700">
                            Nombre del Tipo de Insumo <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="edit-nombre_tipo" name="nombre_tipo" required
                               class="w-full px-3 py-2 transition-colors border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-400"
                               placeholder="Ej: Medicamentos, Materiales, Equipos" maxlength="255">
                        <p class="mt-1.5 text-xs text-gray-500">Nombre descriptivo del tipo de insumo</p>
                        @error('nombre_tipo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

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
                    Actualizar Tipo de Insumo
                </button>
            </div>
        </form>
    </x-modal>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Manejo del formulario de crear tipo de insumo
            const createForm = document.getElementById('create-tipo-insumo-form');
            if (createForm) {
                createForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(createForm);
                    const submitButton = createForm.querySelector('button[type="submit"]');
                    const originalText = submitButton.innerHTML;
                    
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Creando...';
                    
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                                     formData.get('_token');
                    
                    fetch('{{ route("tipo-insumos.store") }}', {
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
                            window.dispatchEvent(new CustomEvent('close-modal'));
                            createForm.reset();
                            submitButton.disabled = false;
                            submitButton.innerHTML = originalText;
                            
                            setTimeout(() => {
                                if (window.notifySuccess) {
                                    const nombre = data.data?.nombre_tipo || 'tipo de insumo';
                                    window.notifySuccess(`Tipo de insumo "${nombre}" creado.`);
                                }
                                setTimeout(() => {
                                    window.location.reload();
                                }, 2500);
                            }, 400);
                        } else {
                            let errorMessage = data.message || 'Error al crear el tipo de insumo';
                            if (data.errors) {
                                const errorList = Object.values(data.errors).flat().join(', ');
                                errorMessage += ': ' + errorList;
                            }
                            
                            if (window.notifyError) {
                                window.notifyError(errorMessage);
                            } else {
                                alert(errorMessage);
                            }
                            
                            submitButton.disabled = false;
                            submitButton.innerHTML = originalText;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        const errorMsg = 'Error al crear el tipo de insumo. Por favor, intenta nuevamente.';
                        
                        if (window.notifyError) {
                            window.notifyError(errorMsg);
                        } else {
                            alert(errorMsg);
                        }
                        
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalText;
                    });
                });
            }

            // Manejo del formulario de editar tipo de insumo
            const editForm = document.getElementById('edit-tipo-insumo-form');
            if (editForm) {
                editForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(editForm);
                    const tipoInsumoId = document.getElementById('edit-tipo-insumo-id').value;
                    const nombreTipoInput = document.getElementById('edit-nombre_tipo');
                    const nombreTipo = nombreTipoInput ? nombreTipoInput.value.trim() : '';
                    const submitButton = editForm.querySelector('button[type="submit"]');
                    const originalText = submitButton.innerHTML;
                    
                    if (!tipoInsumoId) {
                        alert('Error: No se pudo identificar el tipo de insumo a editar.');
                        return;
                    }
                    
                    if (!nombreTipo) {
                        alert('Por favor, ingresa el nombre del tipo de insumo.');
                        nombreTipoInput?.focus();
                        return;
                    }
                    
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                                     formData.get('_token');
                    
                    const updateData = {
                        nombre_tipo: nombreTipo
                    };
                    
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Actualizando...';
                    
                    fetch(`/tipo-insumos/${encodeURIComponent(tipoInsumoId)}`, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify(updateData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.dispatchEvent(new CustomEvent('close-modal'));
                            submitButton.disabled = false;
                            submitButton.innerHTML = originalText;
                            
                            setTimeout(() => {
                                if (window.notifySuccess) {
                                    const nombreNuevo = data.data?.nombre_tipo || nombreTipo;
                                    window.notifySuccess(`Tipo de insumo "${nombreNuevo}" actualizado.`);
                                }
                                setTimeout(() => {
                                    window.location.reload();
                                }, 2500);
                            }, 400);
                        } else {
                            let errorMessage = data.message || 'Error al actualizar el tipo de insumo';
                            if (data.errors) {
                                const errorList = Object.values(data.errors).flat().join(', ');
                                errorMessage += ': ' + errorList;
                            }
                            
                            if (window.notifyError) {
                                window.notifyError(errorMessage);
                            } else {
                                alert(errorMessage);
                            }
                            
                            submitButton.disabled = false;
                            submitButton.innerHTML = originalText;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        const errorMsg = 'Error al actualizar el tipo de insumo. Por favor, intenta nuevamente.';
                        
                        if (window.notifyError) {
                            window.notifyError(errorMsg);
                        } else {
                            alert(errorMsg);
                        }
                        
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalText;
                    });
                });
            }
        });

        // Función global para abrir el modal de editar
        function openEditTipoInsumoModal(id) {
            const idInput = document.getElementById('edit-tipo-insumo-id');
            const nombreInput = document.getElementById('edit-nombre_tipo');
            
            if (!idInput || !nombreInput) {
                console.error('Error: No se encontraron los elementos del formulario');
                alert('Error: No se pudo inicializar el formulario de edición');
                return;
            }
            
            idInput.value = '';
            nombreInput.value = '';
            
            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'edit-tipo-insumo' }));
            
            setTimeout(() => {
                fetch(`/tipo-insumos/${encodeURIComponent(id)}`, {
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
                            const tipoInsumo = data.data;
                            
                            if (idInput) idInput.value = tipoInsumo.id || id;
                            if (nombreInput) nombreInput.value = tipoInsumo.nombre_tipo || '';
                            
                            // Actualizar la acción del formulario
                            const editForm = document.getElementById('edit-tipo-insumo-form');
                            if (editForm) {
                                editForm.action = `/tipo-insumos/${encodeURIComponent(id)}`;
                            }
                        } else {
                            alert('Error al cargar los datos del tipo de insumo: ' + (data.message || 'Error desconocido'));
                            window.dispatchEvent(new CustomEvent('close-modal'));
                        }
                    })
                    .catch(error => {
                        console.error('Error completo:', error);
                        alert('Error al cargar los datos del tipo de insumo: ' + error.message);
                        window.dispatchEvent(new CustomEvent('close-modal'));
                    });
            }, 100);
        }
    </script>
</x-app-layout>




