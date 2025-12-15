<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center space-x-3 sm:space-x-4">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg sm:w-10 sm:h-10 bg-gradient-to-br from-primary-400 to-primary-500">
                        <svg class="w-4 h-4 text-white sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></circle>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <h2 class="text-lg font-semibold leading-tight text-gray-800 truncate sm:text-xl">
                        {{ __('Gestión de usuarios') }}
                    </h2>
                    <p class="hidden mt-1 text-xs text-gray-600 sm:text-sm sm:block">Administra y organiza los usuarios del sistema</p>
                </div>
            </div>
            <div class="flex-shrink-0 w-full sm:w-auto flex gap-2">
                <button onclick="generateAllBarcodes()" 
                   id="generate-all-barcodes-btn"
                   class="inline-flex items-center justify-center w-full px-3 py-2 text-xs font-medium text-white transition-all duration-150 rounded-lg shadow-sm sm:w-auto sm:px-4 sm:text-sm bg-indigo-500 hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400">
                    <svg class="w-3 h-3 mr-1 sm:w-4 sm:h-4 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Generar Código</span>
                </button>
                <button @click="$dispatch('open-modal', 'create-user')" 
                   class="inline-flex items-center justify-center w-full px-3 py-2 text-xs font-medium text-white transition-all duration-150 rounded-lg shadow-sm sm:w-auto sm:px-4 sm:text-sm bg-secondary-500 hover:bg-secondary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-400">
                    <svg class="w-3 h-3 mr-1 sm:w-4 sm:h-4 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span class="sm:hidden">Agregar</span>
                    <span class="hidden sm:inline">Agregar Usuario</span>
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
            <div class="p-4 bg-white shadow sm:p-8 sm:rounded-lg">
                <livewire:tables.users-table />
            </div>
        </div>
    </div>

    <!-- Modal para crear usuario -->
    <x-modal name="create-user" title="Agregar Nuevo Usuario" maxWidth="6xl">
        <form id="create-user-form" method="POST" action="{{ route('users.store') }}" class="space-y-6">
            @csrf
            
            <!-- Información básica -->
            <div class="p-6 bg-gray-50 border border-neutral-200 rounded-lg shadow-sm">
                <div class="flex items-center mb-4 pb-3 border-b border-neutral-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary-100">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-semibold text-gray-900">Información Personal</h3>
                        <p class="text-sm text-gray-500">Datos básicos del usuario</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                    <!-- RUN -->
                    <div>
                        <x-input-label for="run" class="font-semibold text-primary-700">
                            RUN <span class="text-red-500">*</span>
                        </x-input-label>
                        <div class="relative mt-2">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input type="text" id="run" name="run" required
                                   class="block w-full py-3 pl-10 pr-3 transition-all duration-200 border shadow-sm border-neutral-300 rounded-xl placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-secondary-500 focus:border-secondary-500 hover:border-primary-300"
                                   placeholder="12.345.678-9" maxlength="20" oninput="formatRun(this)">
                        </div>
                        <x-input-error :messages="$errors->get('run')" class="mt-2" />
                    </div>

                    <!-- Nombre -->
                    <div>
                        <label for="nombre" class="block mb-2 text-sm font-medium text-gray-700">
                            Nombre Completo <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nombre" name="nombre" required
                               class="w-full px-3 py-2 transition-colors border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-400"
                               placeholder="Juan Pérez González" maxlength="255">
                        @error('nombre')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Correo -->
                    <div>
                        <label for="correo" class="block mb-2 text-sm font-medium text-gray-700">
                            Correo Electrónico <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="correo" name="correo" required
                               class="w-full px-3 py-2 transition-colors border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-400"
                               placeholder="juan.perez@empresa.com">
                        @error('correo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Departamento -->
                    <div>
                        <label for="id_depto" class="block mb-2 text-sm font-medium text-gray-700">
                            Departamento <span class="text-red-500">*</span>
                        </label>
                        <select id="id_depto" name="id_depto" required
                                class="w-full px-3 py-2 transition-colors border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-400">
                            <option value="">Seleccionar departamento</option>
                            @foreach(\App\Models\Departamento::orderByName()->get() as $departamento)
                                <option value="{{ $departamento->id_depto }}">{{ $departamento->nombre_depto }}</option>
                            @endforeach
                        </select>
                        @error('id_depto')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contraseña -->
                    <div>
                        <label for="contrasena" class="block mb-2 text-sm font-medium text-gray-700">
                            Contraseña <span class="text-red-500">*</span>
                        </label>
                        <input type="password" id="contrasena" name="contrasena" required minlength="8"
                               class="w-full px-3 py-2 transition-colors border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-400"
                               placeholder="Mínimo 8 caracteres">
                        @error('contrasena')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirmar Contraseña -->
                    <div>
                        <label for="contrasena_confirmation" class="block mb-2 text-sm font-medium text-gray-700">
                            Confirmar Contraseña <span class="text-red-500">*</span>
                        </label>
                        <input type="password" id="contrasena_confirmation" name="contrasena_confirmation" required minlength="8"
                               class="w-full px-3 py-2 transition-colors border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-400"
                               placeholder="Repetir contraseña">
                    </div>
                </div>
            </div>

            <!-- Permisos -->
            <div class="p-6 bg-gray-50 border border-neutral-200 rounded-lg shadow-sm">
                <div class="flex items-center mb-4 pb-3 border-b border-neutral-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-secondary-100">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-semibold text-gray-900">Permisos en el Sistema</h3>
                        <p class="text-sm text-gray-500">Selecciona los permisos que tendrá el usuario</p>
                    </div>
                </div>

                <div class="overflow-x-auto max-h-96 overflow-y-auto border border-gray-200 rounded-lg bg-white">
                    <table class="w-full border-collapse">
                        <thead class="sticky top-0 bg-gray-100 z-10">
                            <tr>
                                <th class="text-left p-3 text-sm font-semibold text-gray-700 border-b border-gray-300">#</th>
                                <th class="text-left p-3 text-sm font-semibold text-gray-700 border-b border-gray-300">Permiso</th>
                                <th class="text-center p-2 text-xs font-semibold text-gray-700 border-b border-gray-300 w-20">Activo</th>
                            </tr>
                        </thead>
                        <tbody id="create-permissions-body">
                            <!-- Los permisos se cargarán dinámicamente -->
                            <tr>
                                <td colspan="3" class="p-4 text-center text-gray-500">
                                    Cargando permisos...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @error('permissions')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
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
                    Crear Usuario
                </button>
            </div>
        </form>
    </x-modal>

    <!-- Modal para editar usuario -->
    <x-modal name="edit-user" title="Editar Usuario" maxWidth="6xl">
        <form id="edit-user-form" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <input type="hidden" id="edit-user-run" name="run" value="">
            
            <!-- Información básica -->
            <div class="p-6 bg-gray-50 border border-neutral-200 rounded-lg shadow-sm">
                <div class="flex items-center mb-4 pb-3 border-b border-neutral-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary-100">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-semibold text-gray-900">Información Personal</h3>
                        <p class="text-sm text-gray-500">Datos básicos del usuario</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                    <!-- RUN (deshabilitado) -->
                    <div>
                        <label for="edit-run" class="block mb-2 text-sm font-medium text-gray-700">
                            RUN <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="edit-run" name="run" required readonly
                               class="w-full px-3 py-2 transition-colors border border-gray-300 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed"
                               placeholder="12.345.678-9" maxlength="20">
                        <p class="mt-1 text-xs text-gray-500">El RUN no se puede modificar</p>
                    </div>

                    <!-- Nombre -->
                    <div>
                        <label for="edit-nombre" class="block mb-2 text-sm font-medium text-gray-700">
                            Nombre Completo <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="edit-nombre" name="nombre" required
                               class="w-full px-3 py-2 transition-colors border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-400"
                               placeholder="Juan Pérez González" maxlength="255">
                        @error('nombre')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Correo -->
                    <div>
                        <label for="edit-correo" class="block mb-2 text-sm font-medium text-gray-700">
                            Correo Electrónico <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="edit-correo" name="correo" required
                               class="w-full px-3 py-2 transition-colors border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-400"
                               placeholder="juan.perez@empresa.com">
                        @error('correo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Departamento -->
                    <div>
                        <label for="edit-id_depto" class="block mb-2 text-sm font-medium text-gray-700">
                            Departamento <span class="text-red-500">*</span>
                        </label>
                        <select id="edit-id_depto" name="id_depto" required
                                class="w-full px-3 py-2 transition-colors border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-400">
                            <option value="">Seleccionar departamento</option>
                            @foreach(\App\Models\Departamento::orderByName()->get() as $departamento)
                                <option value="{{ $departamento->id_depto }}">{{ $departamento->nombre_depto }}</option>
                            @endforeach
                        </select>
                        @error('id_depto')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contraseña -->
                    <div>
                        <label for="edit-contrasena" class="block mb-2 text-sm font-medium text-gray-700">
                            Nueva Contraseña <span class="text-gray-500 text-xs">(opcional)</span>
                        </label>
                        <input type="password" id="edit-contrasena" name="contrasena" minlength="8"
                               class="w-full px-3 py-2 transition-colors border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-400"
                               placeholder="Dejar vacío para no cambiar">
                        <p class="mt-1 text-xs text-gray-500">Deja vacío si no deseas cambiar la contraseña</p>
                        @error('contrasena')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirmar Contraseña -->
                    <div>
                        <label for="edit-contrasena_confirmation" class="block mb-2 text-sm font-medium text-gray-700">
                            Confirmar Contraseña <span class="text-gray-500 text-xs">(opcional)</span>
                        </label>
                        <input type="password" id="edit-contrasena_confirmation" name="contrasena_confirmation" minlength="8"
                               class="w-full px-3 py-2 transition-colors border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-400"
                               placeholder="Repetir contraseña">
                    </div>
                </div>
            </div>

            <!-- Permisos -->
            <div class="p-6 bg-gray-50 border border-neutral-200 rounded-lg shadow-sm">
                <div class="flex items-center mb-4 pb-3 border-b border-neutral-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-secondary-100">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-semibold text-gray-900">Permisos en el Sistema</h3>
                        <p class="text-sm text-gray-500">Selecciona los permisos que tendrá el usuario</p>
                    </div>
                </div>

                <div class="overflow-x-auto max-h-96 overflow-y-auto border border-gray-200 rounded-lg bg-white">
                    <table class="w-full border-collapse">
                        <thead class="sticky top-0 bg-gray-100 z-10">
                            <tr>
                                <th class="text-left p-3 text-sm font-semibold text-gray-700 border-b border-gray-300">#</th>
                                <th class="text-left p-3 text-sm font-semibold text-gray-700 border-b border-gray-300">Permiso</th>
                                <th class="text-center p-2 text-xs font-semibold text-gray-700 border-b border-gray-300 w-20">Activo</th>
                            </tr>
                        </thead>
                        <tbody id="edit-permissions-body">
                            <!-- Los permisos se cargarán dinámicamente -->
                            <tr>
                                <td colspan="3" class="p-4 text-center text-gray-500">
                                    Cargando permisos...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @error('permissions')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
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
                    Actualizar Usuario
                </button>
            </div>
        </form>
    </x-modal>

    <script>
        // Bandera para prevenir recursión infinita
        // Usar window para evitar redeclaración cuando Livewire navega
        if (typeof window.userFormatting === 'undefined') {
            window.userFormatting = false;
        }

        function formatRun(input) {
            // Si ya estamos formateando, salir para evitar recursión
            if (window.userFormatting) {
                return;
            }
            
            // Usar requestAnimationFrame para asegurar que el valor se capture después de que el navegador actualice el DOM
            requestAnimationFrame(() => {
                // Si ya estamos formateando, salir
                if (window.userFormatting) {
                    return;
                }
                
                // Marcar que estamos formateando
                window.userFormatting = true;
                
                try {
                    // Guardar la posición del cursor
                    const cursorPosition = input.selectionStart;
                    const oldValue = input.value;
                    
                    // Limpiar y formatear el valor - capturar el valor actual del input
                    let value = input.value.replace(/[^0-9kK]/g, '').toUpperCase();
                    
                    // Formatear según la longitud
                    let formattedValue;
                    if (value.length <= 7) {
                        formattedValue = value;
                    } else if (value.length === 8) {
                        // 8 dígitos: primeros 7 + guion + último dígito
                        formattedValue = value.substring(0, 7) + '-' + value.substring(7, 8);
                    } else if (value.length === 9) {
                        // 9 dígitos: primeros 8 + guion + último dígito
                        formattedValue = value.substring(0, 8) + '-' + value.substring(8, 9);
                    } else {
                        // Más de 9 dígitos: tomar solo los primeros 8 + guion + 9no dígito
                        formattedValue = value.substring(0, 8) + '-' + value.substring(8, 9);
                    }
                    
                    // Calcular nueva posición del cursor basándose en los dígitos
                    let newCursorPosition = cursorPosition;
                    
                    // Contar dígitos antes del cursor en el valor anterior
                    const oldDigitsOnly = oldValue.replace(/[^0-9kK]/g, '');
                    const digitsBeforeCursor = oldValue.substring(0, cursorPosition).replace(/[^0-9kK]/g, '').length;
                    
                    // Si se agregó un guion (no había guion antes pero ahora sí)
                    if (!oldValue.includes('-') && formattedValue.includes('-')) {
                        // Se agregó el guion: colocar cursor al final para que el usuario vea el último dígito
                        newCursorPosition = formattedValue.length;
                    } else if (oldValue.includes('-') && formattedValue.includes('-')) {
                        // Ya tenía guion: calcular posición basándose en dígitos antes del cursor
                        const newDashIndex = formattedValue.indexOf('-');
                        
                        if (digitsBeforeCursor <= 7) {
                            // Cursor estaba antes del guion: mantener posición relativa a los dígitos
                            newCursorPosition = Math.min(digitsBeforeCursor, newDashIndex);
                        } else {
                            // Cursor estaba después del guion: ajustar posición
                            newCursorPosition = newDashIndex + 1 + Math.min(digitsBeforeCursor - 7, formattedValue.length - newDashIndex - 1);
                        }
                    } else if (oldValue.includes('-') && !formattedValue.includes('-')) {
                        // Se eliminó el guion: mantener posición relativa a los dígitos
                        newCursorPosition = Math.min(digitsBeforeCursor, formattedValue.length);
                    } else {
                        // Sin guion en ambos: mantener posición relativa a los dígitos
                        newCursorPosition = Math.min(digitsBeforeCursor, formattedValue.length);
                    }
                    
                    // Asegurar que la posición del cursor sea válida
                    newCursorPosition = Math.max(0, Math.min(newCursorPosition, formattedValue.length));
                    
                    // Actualizar el valor
                    input.value = formattedValue;
                    
                    // Ajustar la posición del cursor
                    setTimeout(() => {
                        input.setSelectionRange(newCursorPosition, newCursorPosition);
                    }, 0);
                    
                } finally {
                    // Siempre liberar la bandera después de un breve delay
                    setTimeout(() => {
                        window.userFormatting = false;
                    }, 10);
                }
            });
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cargar permisos al iniciar
            loadPermissions();
            
            // Manejo del formulario de crear usuario
            const createForm = document.getElementById('create-user-form');
            if (createForm) {
                createForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(createForm);
                    const submitButton = createForm.querySelector('button[type="submit"]');
                    const originalText = submitButton.innerHTML;
                    
                    // Recopilar permisos seleccionados
                    const permissions = [];
                    const checkedCheckboxes = document.querySelectorAll('.permission-checkbox:checked');
                    checkedCheckboxes.forEach(cb => {
                        if (cb && cb.value) {
                            permissions.push(cb.value);
                        }
                    });
                    
                    // Agregar permisos al FormData
                    formData.delete('permissions[]');
                    permissions.forEach(perm => {
                        formData.append('permissions[]', perm);
                    });
                    
                    // Deshabilitar botón durante el envío
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Creando...';
                    
                    // Obtener token CSRF
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || formData.get('_token');
                    
                    fetch(createForm.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(data => {
                                // Si hay errores de validación, mostrarlos
                                if (response.status === 422 && data.errors) {
                                    const errorMessages = Object.values(data.errors).flat().join('\n');
                                    throw new Error(errorMessages || data.message || 'Error de validación');
                                }
                                throw new Error(data.message || 'Error en la respuesta del servidor');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Restaurar botón
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalText;
                        
                        if (data.success) {
                            // Cerrar modal
                            window.dispatchEvent(new CustomEvent('close-modal'));
                            
                            // Limpiar formulario
                            createForm.reset();
                            
                            // Desmarcar todos los permisos
                            document.querySelectorAll('.permission-checkbox').forEach(cb => {
                                if (cb) cb.checked = false;
                            });
                            
                            // Mostrar Sweet Alert de éxito
                            Swal.fire({
                                icon: 'success',
                                title: '¡Usuario creado exitosamente!',
                                text: data.message || 'El usuario ha sido creado correctamente.',
                                confirmButtonText: 'Aceptar',
                                confirmButtonColor: '#10b981',
                                timer: 3000,
                                timerProgressBar: true
                            });
                            
                            // Actualizar tabla de usuarios
                            if (window.Livewire) {
                                const usersTableComponent = Livewire.find('tables.users-table');
                                if (usersTableComponent && usersTableComponent.$wire) {
                                    usersTableComponent.$wire.$refresh();
                                }
                            }
                        } else {
                            // Mostrar error con Sweet Alert
                            Swal.fire({
                                icon: 'error',
                                title: 'Error al crear usuario',
                                text: data.message || 'Ocurrió un error al crear el usuario.',
                                confirmButtonText: 'Aceptar',
                                confirmButtonColor: '#ef4444'
                            });
                        }
                    })
                    .catch(error => {
                        // Restaurar botón
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalText;
                        
                        // Mostrar error con Sweet Alert
                        Swal.fire({
                            icon: 'error',
                            title: 'Error al crear usuario',
                            text: error.message || 'Ocurrió un error al crear el usuario. Por favor, intenta nuevamente.',
                            confirmButtonText: 'Aceptar',
                            confirmButtonColor: '#ef4444'
                        });
                    });
                });
            }
            
            // Funcionalidad para seleccionar/deseleccionar todos los permisos de un módulo
            document.querySelectorAll('.module-select-all').forEach(function(selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    const moduleName = this.dataset.module;
                    const moduleCheckboxes = document.querySelectorAll(`.permission-checkbox[data-module="${moduleName}"]`);
                    
                    moduleCheckboxes.forEach(function(checkbox) {
                        checkbox.checked = selectAllCheckbox.checked;
                    });
                });
            });

            // Funcionalidad para actualizar el estado de "Todas" cuando se cambian permisos individuales
            document.querySelectorAll('.permission-checkbox').forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const moduleName = this.dataset.module;
                    if (!moduleName) return; // Si no tiene módulo, salir
                    
                    const moduleCheckboxes = document.querySelectorAll(`.permission-checkbox[data-module="${moduleName}"]`);
                    const selectAllCheckbox = document.querySelector(`.module-select-all[data-module="${moduleName}"]`);
                    
                    if (!selectAllCheckbox) return; // Si no existe el checkbox "select all", salir
                    
                    const checkedCount = Array.from(moduleCheckboxes).filter(cb => cb && cb.checked).length;
                    const totalCount = moduleCheckboxes.length;
                    
                    if (checkedCount === 0) {
                        selectAllCheckbox.indeterminate = false;
                        selectAllCheckbox.checked = false;
                    } else if (checkedCount === totalCount) {
                        selectAllCheckbox.indeterminate = false;
                        selectAllCheckbox.checked = true;
                    } else {
                        selectAllCheckbox.indeterminate = true;
                        selectAllCheckbox.checked = false;
                    }
                });
            });

            // Funcionalidad para seleccionar/deseleccionar todos los permisos de un módulo en el modal de editar
            document.querySelectorAll('.edit-module-select-all').forEach(function(selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    const moduleName = this.dataset.module;
                    const moduleCheckboxes = document.querySelectorAll(`.edit-permission-checkbox[data-module="${moduleName}"]`);
                    
                    moduleCheckboxes.forEach(function(checkbox) {
                        checkbox.checked = selectAllCheckbox.checked;
                    });
                });
            });

            // Funcionalidad para actualizar el estado de "Todas" cuando se cambian permisos individuales en el modal de editar
            document.querySelectorAll('.edit-permission-checkbox').forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const moduleName = this.dataset.module;
                    if (!moduleName) return; // Si no tiene módulo, salir
                    
                    const moduleCheckboxes = document.querySelectorAll(`.edit-permission-checkbox[data-module="${moduleName}"]`);
                    const selectAllCheckbox = document.querySelector(`.edit-module-select-all[data-module="${moduleName}"]`);
                    
                    if (!selectAllCheckbox) return; // Si no existe el checkbox "select all", salir
                    
                    const checkedCount = Array.from(moduleCheckboxes).filter(cb => cb && cb.checked).length;
                    const totalCount = moduleCheckboxes.length;
                    
                    if (checkedCount === 0) {
                        selectAllCheckbox.indeterminate = false;
                        selectAllCheckbox.checked = false;
                    } else if (checkedCount === totalCount) {
                        selectAllCheckbox.indeterminate = false;
                        selectAllCheckbox.checked = true;
                    } else {
                        selectAllCheckbox.indeterminate = true;
                        selectAllCheckbox.checked = false;
                    }
                });
            });

            // Manejo del formulario de editar usuario
            const editForm = document.getElementById('edit-user-form');
            if (editForm) {
                editForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(editForm);
                    const run = document.getElementById('edit-user-run').value;
                    const submitButton = editForm.querySelector('button[type="submit"]');
                    const originalText = submitButton.innerHTML;
                    
                    // Asegurar que todos los campos necesarios estén presentes
                    const formDataObj = {
                        run: formData.get('run'),
                        nombre: formData.get('nombre'),
                        correo: formData.get('correo'),
                        id_depto: formData.get('id_depto'),
                        contrasena: formData.get('contrasena') ? '***' : 'vacía'
                    };
                    // Verificar que id_depto esté presente
                    if (!formDataObj.id_depto || formDataObj.id_depto === '') {
                        alert('Por favor, selecciona un departamento');
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalText;
                        return;
                    }
                    
                    // Si la contraseña está vacía, eliminarla del FormData
                    const contrasena = formData.get('contrasena');
                    if (!contrasena || contrasena.trim() === '') {
                        formData.delete('contrasena');
                        formData.delete('contrasena_confirmation');
                    }
                    
                    // Recopilar permisos seleccionados
                    const permissions = [];
                    const checkedCheckboxes = document.querySelectorAll('.edit-permission-checkbox:checked');
                    const allCheckboxes = document.querySelectorAll('.edit-permission-checkbox');
                    
                    console.log('Recopilando permisos:', {
                        totalCheckboxes: allCheckboxes.length,
                        checkedCheckboxes: checkedCheckboxes.length,
                        allCheckboxValues: Array.from(allCheckboxes).map(cb => ({ value: cb.value, checked: cb.checked }))
                    });
                    
                    checkedCheckboxes.forEach(cb => {
                        if (cb && cb.value) {
                        permissions.push(cb.value);
                            console.log('Permiso agregado:', cb.value);
                        }
                    });
                    
                    console.log('Permisos a enviar:', permissions);
                    
                    // IMPORTANTE: Siempre enviar el campo permissions, incluso si está vacío
                    // Esto permite al servidor saber que debe actualizar los permisos
                    formData.delete('permissions[]'); // Eliminar cualquier permiso previo
                    
                    // Si no hay permisos seleccionados, enviar un array vacío explícitamente
                    if (permissions.length === 0) {
                        // Enviar un campo vacío para indicar que se deben eliminar todos los permisos directos
                        formData.append('permissions', '[]');
                        console.log('No hay permisos seleccionados, enviando array vacío');
                    } else {
                    permissions.forEach(perm => {
                        formData.append('permissions[]', perm);
                    });
                    }
                    
                    // Verificar que los permisos se agregaron correctamente al FormData
                    const formDataPermissions = formData.getAll('permissions[]');
                    console.log('Permisos en FormData:', formDataPermissions);
                    
                    // Deshabilitar botón durante el envío
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Actualizando...';
                    
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
                    
                    // Convertir FormData a objeto JSON
                    const dataObj = {};
                    for (let [key, value] of formData.entries()) {
                        // Manejar arrays (como permissions[])
                        if (key.endsWith('[]')) {
                            const baseKey = key.replace('[]', '');
                            if (!dataObj[baseKey]) {
                                dataObj[baseKey] = [];
                            }
                            dataObj[baseKey].push(value);
                        } else if (key === 'permissions' && value === '[]') {
                            // Si permissions es '[]', convertirlo a array vacío
                            dataObj[key] = [];
                        } else {
                            dataObj[key] = value;
                        }
                    }
                    
                    // Asegurar que permissions siempre esté presente como array
                    if (!dataObj.hasOwnProperty('permissions')) {
                        // Si no hay permissions[], usar los permisos recopilados
                        dataObj.permissions = permissions;
                    } else if (Array.isArray(dataObj.permissions) && dataObj.permissions.length === 0) {
                        // Si permissions está vacío, mantenerlo como array vacío
                        dataObj.permissions = [];
                    }
                    
                    // Log del objeto final que se enviará
                    console.log('Datos a enviar al servidor:', {
                        ...dataObj,
                        permissions: dataObj.permissions || [],
                        permissions_count: Array.isArray(dataObj.permissions) ? dataObj.permissions.length : 0
                    });
                    
                    fetch(`/users/${run}`, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify(dataObj)
                    })
                    .then(response => {
                        // Si el token CSRF expiró (419), actualizar el token y reintentar
                        if (response.status === 419) {
                            // Obtener el nuevo token del meta tag
                            const newToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                            if (newToken) {
                                // Actualizar el token en el objeto de datos
                                dataObj._token = newToken;
                                
                                // Reintentar la petición con el nuevo token
                                return fetch(`/users/${run}`, {
                                    method: 'PUT',
                                    headers: {
                                        'X-CSRF-TOKEN': newToken,
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest',
                                    },
                                    body: JSON.stringify(dataObj)
                                }).then(retryResponse => {
                                    if (!retryResponse.ok) {
                                        return retryResponse.json().then(data => {
                                            throw new Error(data.message || 'Error en la respuesta del servidor');
                                        });
                                    }
                                    return retryResponse.json();
                                });
                            } else {
                                // Si no hay token, recargar la página
                                alert('La sesión expiró. Por favor, recarga la página.');
                                window.location.reload();
                                return Promise.reject(new Error('Sesión expirada'));
                            }
                        }
                        
                        if (!response.ok) {
                            return response.json().then(data => {
                                throw new Error(data.message || 'Error en la respuesta del servidor');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        // SIEMPRE restaurar el botón primero
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalText;
                        
                        if (data.success) {
                            // Cerrar modal
                            window.dispatchEvent(new CustomEvent('close-modal'));
                            
                            // Verificar si el usuario editado es el usuario actual
                            const currentUserRun = '{{ auth()->user()->run ?? "" }}';
                            const editedUserRun = data.data?.run || run;
                            const isCurrentUser = data.is_current_user || false;
                            const permissionsUpdated = data.permissions_updated || false;
                            const sessionUpdated = data.session_updated || false;
                            
                            // Actualizar componentes Livewire
                            if (window.Livewire) {
                                // Actualizar tabla de usuarios
                                const usersTableComponent = Livewire.find('tables.users-table');
                                if (usersTableComponent && usersTableComponent.$wire) {
                                    usersTableComponent.$wire.$refresh();
                                }
                                
                                // Si se actualizaron permisos y es el usuario actual, recargar la página para reflejar cambios
                                if (permissionsUpdated && isCurrentUser) {
                                    // Siempre recargar la página si se actualizaron permisos del usuario actual
                                    // para asegurar que el sidebar se actualice correctamente
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 500);
                                } else if (permissionsUpdated) {
                                    // Si se actualizaron permisos de otro usuario, solo refrescar la tabla
                                    // No es necesario recargar la página
                                }
                            }
                            
                            // Mostrar mensaje de éxito
                            const notification = document.createElement('div');
                            notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center space-x-2';
                            notification.innerHTML = `
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="font-medium">${data.message || 'Usuario actualizado exitosamente'}</span>
                            `;
                            document.body.appendChild(notification);
                            setTimeout(() => notification.remove(), 3000);
                        } else {
                            // Mostrar errores
                            let errorMessage = data.message || 'Error al actualizar el usuario';
                            if (data.errors) {
                                const errorList = Object.values(data.errors).flat().join('\n');
                                errorMessage += '\n\n' + errorList;
                            }
                            alert(errorMessage);
                            // El botón ya fue restaurado arriba
                        }
                    })
                    .catch(error => {
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalText;
                        alert('Error al actualizar el usuario. Por favor, intenta nuevamente.\n\n' + error.message);
                    });
                });
            }
        });
    </script>

    <script>
        // Función global para abrir el modal de editar - datos directos desde el controlador
        window.openEditModal = function(userData) {
            // Los datos vienen directamente desde el controlador (tabla)
            if (!userData || !userData.run) {
                console.error('Datos de usuario no válidos');
                return;
            }
            
            // Limpiar formulario primero
            const runInput = document.getElementById('edit-run');
            const nombreInput = document.getElementById('edit-nombre');
            const correoInput = document.getElementById('edit-correo');
            const deptoSelect = document.getElementById('edit-id_depto');

            if (runInput) runInput.value = '';
            if (nombreInput) nombreInput.value = '';
            if (correoInput) correoInput.value = '';
            if (deptoSelect) deptoSelect.value = '';

            const contrasenaInput = document.getElementById('edit-contrasena');
            const contrasenaConfInput = document.getElementById('edit-contrasena_confirmation');
            if (contrasenaInput) contrasenaInput.value = '';
            if (contrasenaConfInput) contrasenaConfInput.value = '';

            // Desmarcar todos los permisos
            document.querySelectorAll('.edit-permission-checkbox').forEach(cb => {
                if (cb) cb.checked = false;
            });
            document.querySelectorAll('.edit-module-select-all').forEach(cb => {
                if (cb) {
                    cb.checked = false;
                    cb.indeterminate = false;
                }
            });

            // Llenar campos del formulario directamente con los datos del controlador
                if (runInput) {
                    document.getElementById('edit-user-run').value = userData.run;
                    runInput.value = userData.run;
                }
                if (nombreInput) nombreInput.value = userData.nombre || '';
                if (correoInput) correoInput.value = userData.correo || '';
            
            // Establecer departamento
            if (userData.id_depto) {
                if (deptoSelect) deptoSelect.value = userData.id_depto;
            } else {
                if (deptoSelect) deptoSelect.value = '';
            }

            // Abrir modal
            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'edit-user' }));
            
            // Asegurar que los permisos estén cargados antes de marcarlos
            const editPermissionsBody = document.getElementById('edit-permissions-body');
            if (!editPermissionsBody || !editPermissionsBody.children || editPermissionsBody.children.length === 0) {
                // Si no hay checkboxes renderizados, cargar permisos primero
                loadPermissions();
            }
            
            // Marcar permisos del usuario - esperar a que los checkboxes estén renderizados
            const markPermissions = () => {
                if (userData.permissions && Array.isArray(userData.permissions) && userData.permissions.length > 0) {
                    const permissionSet = new Set(userData.permissions.map(p => String(p).trim()));
                    const checkboxes = document.querySelectorAll('.edit-permission-checkbox');
                    
                    // Verificar que tenemos la cantidad correcta de checkboxes
                    if (checkboxes.length === 0) {
                        // Si no hay checkboxes aún, esperar un poco más
                        setTimeout(markPermissions, 100);
                        return;
                    }
                    
                    let markedCount = 0;
                    checkboxes.forEach(checkbox => {
                        if (checkbox) {
                            const checkboxValue = String(checkbox.value).trim();
                            const shouldBeChecked = permissionSet.has(checkboxValue);
                            
                            // Forzar el cambio del estado del checkbox
                            checkbox.checked = shouldBeChecked;
                            
                            // Disparar eventos para asegurar que se actualice visualmente
                            checkbox.dispatchEvent(new Event('change', { bubbles: true }));
                            checkbox.dispatchEvent(new Event('input', { bubbles: true }));
                            
                            if (shouldBeChecked) {
                                markedCount++;
                            }
                        }
                    });
                    
                    // Actualizar estado de los checkboxes "select all" para cada módulo
                    document.querySelectorAll('.edit-module-select-all').forEach(function(selectAllCheckbox) {
                        if (!selectAllCheckbox) return;
                        const moduleName = selectAllCheckbox.dataset.module;
                        const moduleCheckboxes = document.querySelectorAll(`.edit-permission-checkbox[data-module="${moduleName}"]`);
                        const checkedCount = Array.from(moduleCheckboxes).filter(cb => cb && cb.checked).length;
                        const totalCount = moduleCheckboxes.length;
                        
                        if (checkedCount === 0) {
                            selectAllCheckbox.indeterminate = false;
                            selectAllCheckbox.checked = false;
                        } else if (checkedCount === totalCount) {
                            selectAllCheckbox.indeterminate = false;
                            selectAllCheckbox.checked = true;
                        } else {
                            selectAllCheckbox.indeterminate = true;
                            selectAllCheckbox.checked = false;
                        }
                    });
                }
            };
            
            // Intentar marcar permisos con múltiples intentos para asegurar que se marquen
            markPermissions();
            setTimeout(markPermissions, 200);
            setTimeout(markPermissions, 500);
        };

        // Función para cargar permisos desde el servidor
        function loadPermissions() {
            fetch('/users/permissions')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.data) {
                        renderPermissions(data.data, 'create-permissions-body', 'permission-checkbox', 'permission');
                        renderPermissions(data.data, 'edit-permissions-body', 'edit-permission-checkbox', 'edit_permission');
                        }
                })
                .catch(error => {
                    console.error('Error al cargar permisos:', error);
                });
        }

        // Función para renderizar permisos en la tabla
        function renderPermissions(permissions, tbodyId, checkboxClass, checkboxIdPrefix) {
            const tbody = document.getElementById(tbodyId);
            if (!tbody) return;

            // Mapeo de nombres de permisos a nombres legibles
            const permissionLabels = {
                'dashboard': 'Dashboard',
                'solicitudes': 'Solicitudes',
                'mantenedor de usuarios': 'Mantenedor de Usuarios',
                'mantenedor de departamentos': 'Mantenedor de Departamentos',
                'mantenedor de unidades': 'Mantenedor de Unidades',
                'insumos': 'Insumos',
                'mantenedor de tipos de insumo': 'Mantenedor de Tipos de Insumo',
                'carga masiva': 'Carga Masiva',
                'mantenedor de proveedores': 'Mantenedor de Proveedores',
                'mantenedor de facturas': 'Mantenedor de Facturas',
                'admin solicitudes': 'Admin Solicitudes',
                'reportes': 'Reportes',
                'reportes insumos': 'Reporte de Insumos',
                'reportes stock': 'Reporte de Stock Crítico',
                'reportes consumo departamento': 'Reporte de Consumo por Departamento',
                'reportes rotacion': 'Reporte de Rotación',
                'notificaciones': 'Notificaciones',
            };

            tbody.innerHTML = '';
            permissions.forEach((permission, index) => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50 transition-colors bg-white';
                
                const label = permissionLabels[permission.name] || permission.name.replace(/\b\w/g, l => l.toUpperCase());
                
                row.innerHTML = `
                    <td class="p-3 text-sm text-gray-600 border-b border-gray-200">${index + 1}</td>
                    <td class="p-3 text-sm font-medium text-gray-800 border-b border-gray-200">${label}</td>
                    <td class="text-center p-2 border-b border-gray-200">
                        <input type="checkbox" 
                               id="${checkboxIdPrefix}_${permission.id}" 
                               name="permissions[]" 
                               value="${permission.name}" 
                               class="${checkboxClass} w-4 h-4 border-gray-300 rounded text-primary-600 focus:ring-primary-500">
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        // Función para generar códigos QR para todos los usuarios
        function generateAllBarcodes() {
            const btn = document.getElementById('generate-all-barcodes-btn');
            const originalText = btn.innerHTML;
            
            if (!confirm('¿Estás seguro de generar códigos QR para todos los usuarios?\n\nEsto eliminará todas las imágenes de códigos existentes y generará nuevos códigos únicos para cada usuario.')) {
                return;
            }
            
            // Deshabilitar botón y mostrar loading
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin w-3 h-3 mr-1 sm:w-4 sm:h-4 sm:mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>Generando...</span>';
            
            // Obtener token CSRF
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            fetch('/users/generate-all-barcodes', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.message || 'Error al generar códigos QR');
                    });
                }
                return response.json();
            })
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = originalText;
                
                if (data.success) {
                    // Mostrar mensaje de éxito o advertencia
                    const hasErrors = data.data && data.data.errors && data.data.errors.length > 0;
                    const notification = document.createElement('div');
                    notification.className = `fixed top-4 right-4 ${hasErrors ? 'bg-yellow-500' : 'bg-green-500'} text-white px-6 py-3 rounded-lg shadow-lg z-50 max-w-md`;
                    
                    let errorDetails = '';
                    if (hasErrors && data.data.errors.length > 0) {
                        errorDetails = '<div class="mt-2 text-sm text-white/90"><strong>Errores:</strong><ul class="list-disc list-inside mt-1">';
                        data.data.errors.forEach((error) => {
                            errorDetails += `<li>${error.nombre || error.user}: ${error.error}</li>`;
                        });
                        errorDetails += '</ul></div>';
                    }
                    
                    notification.innerHTML = `
                        <div class="flex items-start space-x-2">
                            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${hasErrors ? 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z' : 'M5 13l4 4L19 7'}"></path>
                            </svg>
                            <div class="flex-1">
                                <span class="font-medium block">${data.message || 'Códigos QR generados exitosamente'}</span>
                                ${errorDetails}
                            </div>
                        </div>
                    `;
                    document.body.appendChild(notification);
                    setTimeout(() => notification.remove(), hasErrors ? 15000 : 5000);
                    
                    // Recargar la tabla de usuarios
                    if (window.Livewire) {
                        const usersTableComponent = Livewire.find('tables.users-table');
                        if (usersTableComponent && usersTableComponent.$wire) {
                            usersTableComponent.$wire.$refresh();
                        }
                    }
                    
                    // Recargar la página después de un breve delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    alert('Error: ' + (data.message || 'No se pudieron generar los códigos QR'));
                }
            })
            .catch(error => {
                btn.disabled = false;
                btn.innerHTML = originalText;
                console.error('Error:', error);
                alert('Error al generar códigos QR. Por favor, intenta nuevamente.');
            });
        }
    </script>
</x-app-layout>