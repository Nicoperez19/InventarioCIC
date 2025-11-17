<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center space-x-3 sm:space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-primary-400 to-primary-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="min-w-0 flex-1">
                    <h2 class="text-lg sm:text-xl font-semibold leading-tight text-gray-800 truncate">
                        {{ __('Gestión de unidades') }}
                    </h2>
                    <p class="text-xs sm:text-sm text-gray-600 mt-1 hidden sm:block">Administra y organiza las unidades de medida del sistema</p>
                </div>
            </div>
            <div class="flex-shrink-0 w-full sm:w-auto">
                <button @click="$dispatch('open-modal', 'create-unidad')" 
                   class="inline-flex items-center justify-center w-full sm:w-auto px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-white bg-secondary-500 rounded-lg hover:bg-secondary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-400 transition-all duration-150 shadow-sm">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span class="sm:hidden">Agregar</span>
                    <span class="hidden sm:inline">Agregar Unidad</span>
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
            <div class="p-4 bg-white shadow sm:p-8 sm:rounded-lg">
                <livewire:tables.unidades-table />
            </div>
        </div>
    </div>

    <!-- Modal para crear unidad -->
    <x-modal name="create-unidad" title="Agregar Nueva Unidad de Medida" maxWidth="2xl">
        <form id="create-unidad-form" method="POST" action="{{ route('unidades.store') }}" class="space-y-6">
            @csrf
            
            <!-- Datos de la Unidad -->
            <div class="p-6 bg-gray-50 border border-neutral-200 rounded-lg shadow-sm">
                <div class="flex items-center mb-4 pb-3 border-b border-neutral-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary-100">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-semibold text-gray-900">Datos de la Unidad</h3>
                        <p class="text-sm text-gray-500">Define el identificador y nombre de la unidad de medida</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <!-- ID de Unidad -->
                    <div>
                        <label for="id_unidad" class="block mb-2 text-sm font-medium text-gray-700">
                            ID de Unidad <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="id_unidad" name="id_unidad" required
                               class="w-full px-3 py-2 transition-colors border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-400"
                               placeholder="Ej: KG, L, M" maxlength="20">
                        <p class="mt-1.5 text-xs text-gray-500">Código corto para identificar la unidad</p>
                        @error('id_unidad')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nombre de la Unidad -->
                    <div>
                        <label for="nombre_unidad_medida" class="block mb-2 text-sm font-medium text-gray-700">
                            Nombre de la Unidad <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nombre_unidad_medida" name="nombre_unidad_medida" required
                               class="w-full px-3 py-2 transition-colors border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-400"
                               placeholder="Ej: Kilogramo, Litro, Metro" maxlength="255">
                        <p class="mt-1.5 text-xs text-gray-500">Nombre descriptivo completo</p>
                        @error('nombre_unidad_medida')
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
                    Crear Unidad
                </button>
            </div>
        </form>
    </x-modal>

    <!-- Modal para editar unidad -->
    <x-modal name="edit-unidad" title="Editar Unidad de Medida" maxWidth="2xl">
        <form id="edit-unidad-form" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <input type="hidden" id="edit-unidad-id" name="id_unidad" value="">
            
            <!-- Datos de la Unidad -->
            <div class="p-6 bg-gray-50 border border-neutral-200 rounded-lg shadow-sm">
                <div class="flex items-center mb-4 pb-3 border-b border-neutral-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary-100">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-semibold text-gray-900">Datos de la Unidad</h3>
                        <p class="text-sm text-gray-500">Actualiza el identificador y nombre de la unidad de medida</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <!-- ID de Unidad (deshabilitado) -->
                    <div>
                        <label for="edit-id_unidad" class="block mb-2 text-sm font-medium text-gray-700">
                            ID de Unidad <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="edit-id_unidad" name="id_unidad" required readonly
                               class="w-full px-3 py-2 transition-colors border border-gray-300 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed"
                               placeholder="Ej: KG, L, M" maxlength="20">
                        <p class="mt-1.5 text-xs text-gray-500">El ID de la unidad no se puede modificar</p>
                    </div>

                    <!-- Nombre de la Unidad -->
                    <div>
                        <label for="edit-nombre_unidad_medida" class="block mb-2 text-sm font-medium text-gray-700">
                            Nombre de la Unidad <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="edit-nombre_unidad_medida" name="nombre_unidad_medida" required
                               class="w-full px-3 py-2 transition-colors border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-400"
                               placeholder="Ej: Kilogramo, Litro, Metro" maxlength="255">
                        <p class="mt-1.5 text-xs text-gray-500">Nombre descriptivo completo</p>
                        @error('nombre_unidad_medida')
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
                    Actualizar Unidad
                </button>
            </div>
        </form>
    </x-modal>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Manejo del formulario de crear unidad
            const createForm = document.getElementById('create-unidad-form');
            if (createForm) {
                createForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(createForm);
                    const submitButton = createForm.querySelector('button[type="submit"]');
                    const originalText = submitButton.innerHTML;
                    
                    // Deshabilitar botón durante el envío
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Creando...';
                    
                    // Obtener token CSRF del meta tag (siempre actualizado)
                    let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    
                    // Si no hay token en el meta tag, intentar obtenerlo del formulario
                    if (!csrfToken) {
                        csrfToken = formData.get('_token');
                    }
                    
                    // Si aún no hay token, mostrar error
                    if (!csrfToken) {
                        alert('Error: No se pudo obtener el token de seguridad. Por favor, recarga la página.');
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalText;
                        return;
                    }
                    
                    fetch('{{ route("unidades.store") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    })
                    .then(response => {
                        // Si el token CSRF expiró (419), actualizar el token y reintentar
                        if (response.status === 419) {
                            const newToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                            if (newToken) {
                                return fetch('{{ route("unidades.store") }}', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': newToken,
                                        'Accept': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest'
                                    },
                                    body: formData
                                });
                            } else {
                                alert('La sesión expiró. Por favor, recarga la página.');
                                window.location.reload();
                                return Promise.reject(new Error('Sesión expirada'));
                            }
                        }
                        return response;
                    })
                    .then(response => response.json())
                    .then(data => {
                        // SIEMPRE restaurar el botón primero
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalText;
                        
                        if (data.success) {
                            // Cerrar modal
                            window.dispatchEvent(new CustomEvent('close-modal'));
                            
                            // Limpiar formulario
                            createForm.reset();
                            
                            // Actualizar tabla Livewire en lugar de recargar página
                            if (window.Livewire) {
                                const tableComponent = Livewire.find('tables.unidades-table');
                                if (tableComponent) {
                                    tableComponent.$wire.$refresh();
                                }
                            }
                            
                            // Mostrar notificación de éxito
                            setTimeout(() => {
                                if (window.notifySuccess) {
                                    const nombre = data.data?.nombre_unidad_medida || 'unidad';
                                    window.notifySuccess(`Unidad "${nombre}" creada.`);
                                }
                            }, 400);
                        } else {
                            let errorMessage = data.message || 'Error al crear la unidad';
                            if (data.errors) {
                                const errorList = Object.values(data.errors).flat().join(', ');
                                errorMessage += ': ' + errorList;
                            }
                            
                            if (window.notifyError) {
                                window.notifyError(errorMessage);
                            } else {
                                alert(errorMessage);
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // SIEMPRE restaurar el botón en caso de error
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalText;
                        
                        if (error.message !== 'Sesión expirada') {
                            const errorMsg = 'Error al crear la unidad. Por favor, intenta nuevamente.\n\n' + error.message;
                            if (window.notifyError) {
                                window.notifyError(errorMsg);
                            } else {
                                alert(errorMsg);
                            }
                        }
                    });
                });
            }

            // Manejo del formulario de editar unidad
            const editForm = document.getElementById('edit-unidad-form');
            if (editForm) {
                editForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(editForm);
                    const idUnidad = document.getElementById('edit-unidad-id').value;
                    const nombreUnidadInput = document.getElementById('edit-nombre_unidad_medida');
                    const nombreUnidad = nombreUnidadInput ? nombreUnidadInput.value.trim() : '';
                    const submitButton = editForm.querySelector('button[type="submit"]');
                    const originalText = submitButton.innerHTML;
                    
                    // Validar que tengamos los datos necesarios
                    if (!idUnidad) {
                        alert('Error: No se pudo identificar la unidad a editar.');
                        return;
                    }
                    
                    if (!nombreUnidad) {
                        alert('Por favor, ingresa el nombre de la unidad.');
                        nombreUnidadInput?.focus();
                        return;
                    }
                    
                    // Obtener token CSRF del meta tag (siempre actualizado)
                    let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    
                    // Si no hay token en el meta tag, intentar obtenerlo del formulario
                    if (!csrfToken) {
                        csrfToken = formData.get('_token');
                    }
                    
                    // Si aún no hay token, mostrar error
                    if (!csrfToken) {
                        alert('Error: No se pudo obtener el token de seguridad. Por favor, recarga la página.');
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalText;
                        return;
                    }
                    
                    // Preparar datos para enviar
                    const updateData = {
                        nombre_unidad_medida: nombreUnidad
                    };
                    
                    // Deshabilitar botón durante el envío
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Actualizando...';
                    
                    fetch(`/unidades/${encodeURIComponent(idUnidad)}`, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify(updateData)
                    })
                    .then(response => {
                        // Si el token CSRF expiró (419), actualizar el token y reintentar
                        if (response.status === 419) {
                            const newToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                            if (newToken) {
                                return fetch(`/unidades/${encodeURIComponent(idUnidad)}`, {
                                    method: 'PUT',
                                    headers: {
                                        'X-CSRF-TOKEN': newToken,
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest'
                                    },
                                    body: JSON.stringify(updateData)
                                });
                            } else {
                                alert('La sesión expiró. Por favor, recarga la página.');
                                window.location.reload();
                                return Promise.reject(new Error('Sesión expirada'));
                            }
                        }
                        return response;
                    })
                    .then(response => response.json())
                    .then(data => {
                        // SIEMPRE restaurar el botón primero
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalText;
                        
                        if (data.success) {
                            // Cerrar modal
                            window.dispatchEvent(new CustomEvent('close-modal'));
                            
                            // Actualizar tabla Livewire en lugar de recargar página
                            if (window.Livewire) {
                                const tableComponent = Livewire.find('tables.unidades-table');
                                if (tableComponent) {
                                    tableComponent.$wire.$refresh();
                                }
                            }
                            
                            // Mostrar notificación de éxito
                            setTimeout(() => {
                                if (window.notifySuccess) {
                                    const nombreAnterior = data.nombre_anterior || '';
                                    const nombreNuevo = data.nombre_nuevo || data.data?.nombre_unidad_medida || '';
                                    
                                    let mensaje = '';
                                    if (nombreAnterior && nombreAnterior !== nombreNuevo) {
                                        mensaje = `La unidad "${nombreAnterior}" fue renombrada a "${nombreNuevo}".`;
                                    } else {
                                        mensaje = `Unidad "${nombreNuevo}" actualizada.`;
                                    }
                                    
                                    window.notifySuccess(mensaje);
                                }
                            }, 400);
                        } else {
                            // Mostrar errores con notificación
                            let errorMessage = data.message || 'Error al actualizar la unidad';
                            if (data.errors) {
                                const errorList = Object.values(data.errors).flat().join(', ');
                                errorMessage += ': ' + errorList;
                            }
                            
                            if (window.notifyError) {
                                window.notifyError(errorMessage);
                            } else {
                                alert(errorMessage);
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // SIEMPRE restaurar el botón en caso de error
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalText;
                        
                        if (error.message !== 'Sesión expirada') {
                            const errorMsg = 'Error al actualizar la unidad. Por favor, intenta nuevamente.\n\n' + error.message;
                            if (window.notifyError) {
                                window.notifyError(errorMsg);
                            } else {
                                alert(errorMsg);
                            }
                        }
                    });
                });
            }
        });

        // Función global para abrir el modal de editar
        function openEditModal(idUnidad) {
            // Verificar que los elementos existan
            const idInput = document.getElementById('edit-id_unidad');
            const nombreInput = document.getElementById('edit-nombre_unidad_medida');
            const hiddenInput = document.getElementById('edit-unidad-id');
            
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
            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'edit-unidad' }));
            
            // Esperar un momento para que el modal se abra antes de cargar los datos
            setTimeout(() => {
                // Cargar datos de la unidad
                fetch(`/unidades/${encodeURIComponent(idUnidad)}`, {
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
                            const unidad = data.data;
                            
                            // Llenar campos del formulario
                            if (hiddenInput) hiddenInput.value = unidad.id_unidad || '';
                            if (idInput) idInput.value = unidad.id_unidad || '';
                            if (nombreInput) nombreInput.value = unidad.nombre_unidad_medida || '';
                        } else {
                            alert('Error al cargar los datos de la unidad: ' + (data.message || 'Error desconocido'));
                            window.dispatchEvent(new CustomEvent('close-modal'));
                        }
                    })
                    .catch(error => {
                        console.error('Error completo:', error);
                        alert('Error al cargar los datos de la unidad: ' + error.message);
                        window.dispatchEvent(new CustomEvent('close-modal'));
                    });
            }, 100);
        }
    </script>
</x-app-layout>


