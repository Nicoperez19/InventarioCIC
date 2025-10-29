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
            <div class="flex-shrink-0 w-full sm:w-auto">
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
            <div class="mb-6">
                <h3 class="flex items-center mb-4 text-lg font-semibold text-gray-900">
                    <svg class="w-5 h-5 mr-2 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Información Personal
                </h3>
                <p class="mb-4 text-sm text-gray-500">Datos básicos del usuario</p>

                <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                    <!-- RUN -->
                    <div>
                        <label for="run" class="block mb-2 text-sm font-medium text-gray-700">
                            RUN <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="run" name="run" required
                               class="w-full px-3 py-2 transition-colors border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-400"
                               placeholder="12.345.678-9" maxlength="20">
                        @error('run')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
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
                            @foreach(\App\Models\Departamento::orderBy('nombre_depto')->get() as $departamento)
                                <option value="{{ $departamento->id_depto }}">{{ $departamento->nombre }}</option>
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
            <div class="mb-6">
                <h3 class="flex items-center mb-4 text-lg font-semibold text-gray-900">
                    <svg class="w-5 h-5 mr-2 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    Permisos del Usuario
                </h3>
                <p class="mb-4 text-sm text-gray-500">Selecciona los permisos que tendrá el usuario</p>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5">
                    @foreach(\Spatie\Permission\Models\Permission::orderBy('name')->get() as $permission)
                        <div class="flex items-center">
                            <input type="checkbox" id="permission_{{ $permission->id }}" name="permissions[]" value="{{ $permission->name }}"
                                   class="w-4 h-4 border-gray-300 rounded text-primary-600 focus:ring-primary-500">
                            <label for="permission_{{ $permission->id }}" class="ml-2 text-sm text-gray-700">
                                {{ ucfirst(str_replace('-', ' ', $permission->name)) }}
                            </label>
                        </div>
                    @endforeach
                </div>
                @error('permissions')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Botones de acción -->
            <div class="flex items-center justify-end pt-6 mt-6 space-x-3 border-t border-gray-200">
                <button type="button" @click="$dispatch('close-modal')"
                        class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-400 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Cancelar
                </button>
                <button type="submit" 
                        class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-primary-500 to-secondary-500 rounded-lg shadow-sm hover:from-primary-600 hover:to-secondary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-400 transition-all duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Crear Usuario
                </button>
            </div>
        </form>
    </x-modal>
</x-app-layout>