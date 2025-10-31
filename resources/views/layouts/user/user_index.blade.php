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
                            @foreach(\App\Models\Departamento::whereNull('deleted_at')->orderByName()->get() as $departamento)
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
                        <h3 class="text-lg font-semibold text-gray-900">Permisos del Usuario</h3>
                        <p class="text-sm text-gray-500">Selecciona los permisos que tendrá el usuario</p>
                    </div>
                </div>

                <div class="overflow-x-auto max-h-64 overflow-y-auto border border-gray-200 rounded-lg bg-secondary-50">
                    <table class="w-full border-collapse">
                        <thead class="sticky top-0 bg-secondary-100 z-10">
                            <tr class="bg-secondary-100">
                                <th class="text-left p-3 text-sm font-semibold text-gray-700 border-b border-gray-200">Módulo</th>
                                <th class="text-center p-3 text-sm font-semibold text-gray-700 border-b border-gray-200">Ver</th>
                                <th class="text-center p-3 text-sm font-semibold text-gray-700 border-b border-gray-200">Agregar</th>
                                <th class="text-center p-3 text-sm font-semibold text-gray-700 border-b border-gray-200">Editar</th>
                                <th class="text-center p-3 text-sm font-semibold text-gray-700 border-b border-gray-200">Eliminar</th>
                                <th class="text-center p-3 text-sm font-semibold text-gray-700 border-b border-gray-200">Todas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $permissions = \Spatie\Permission\Models\Permission::all();
                                $groupedPermissions = [];
                                
                                // Agrupar permisos por módulo
                                foreach($permissions as $permission) {
                                    $parts = explode('.', $permission->name);
                                    if(count($parts) >= 2) {
                                        $module = $parts[0];
                                        $action = $parts[1];
                                        
                                        if(!isset($groupedPermissions[$module])) {
                                            $groupedPermissions[$module] = [];
                                        }
                                        
                                        $groupedPermissions[$module][$action] = $permission;
                                    }
                                }
                                
                                // Traducir nombres de módulos
                                $moduleTranslations = [
                                    'usuarios' => 'Usuarios',
                                    'departamentos' => 'Departamentos', 
                                    'insumos' => 'Insumos',
                                    'tipos_insumos' => 'Tipos de Insumos',
                                    'unidades_medida' => 'Unidades de Medida',
                                    'proveedores' => 'Proveedores',
                                    'facturas' => 'Facturas',
                                    'solicitudes' => 'Solicitudes',
                                    'roles' => 'Roles',
                                    'permisos' => 'Permisos',
                                    'reportes' => 'Reportes',
                                    'configuracion' => 'Configuración'
                                ];
                                
                                // Definir acciones en orden
                                $actions = ['ver', 'crear', 'editar', 'eliminar'];
                                $actionLabels = [
                                    'ver' => 'Ver',
                                    'crear' => 'Agregar',
                                    'editar' => 'Editar', 
                                    'eliminar' => 'Eliminar'
                                ];
                            @endphp
                            
                            @foreach($groupedPermissions as $moduleKey => $modulePermissions)
                                <tr class="hover:bg-white transition-colors bg-secondary-50">
                                    <td class="p-3 text-sm font-medium text-gray-800 border-b border-gray-200">
                                        {{ $moduleTranslations[$moduleKey] ?? ucfirst($moduleKey) }}
                                    </td>
                                    
                                    @foreach($actions as $action)
                                        <td class="text-center p-3 border-b border-gray-200">
                                            @if(isset($modulePermissions[$action]))
                                                <input type="checkbox" 
                                                       id="permission_{{ $modulePermissions[$action]->name }}" 
                                                       name="permissions[]" 
                                                       value="{{ $modulePermissions[$action]->name }}"
                                                       class="permission-checkbox w-4 h-4 border-gray-300 rounded text-primary-600 focus:ring-primary-500"
                                                       data-module="{{ $moduleKey }}">
                                            @else
                                                <span class="text-gray-300">-</span>
                                            @endif
                                        </td>
                                    @endforeach
                                    
                                    <td class="text-center p-3 border-b border-gray-200">
                                        <input type="checkbox" 
                                               class="module-select-all w-4 h-4 border-gray-300 rounded text-primary-600 focus:ring-primary-500"
                                               data-module="{{ $moduleKey }}">
                                    </td>
                                </tr>
                    @endforeach
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
                            @foreach(\App\Models\Departamento::whereNull('deleted_at')->orderByName()->get() as $departamento)
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
                        <h3 class="text-lg font-semibold text-gray-900">Permisos del Usuario</h3>
                        <p class="text-sm text-gray-500">Selecciona los permisos que tendrá el usuario</p>
                    </div>
                </div>

                <div class="overflow-x-auto max-h-64 overflow-y-auto border border-gray-200 rounded-lg bg-secondary-50">
                    <table class="w-full border-collapse">
                        <thead class="sticky top-0 bg-secondary-100 z-10">
                            <tr class="bg-secondary-100">
                                <th class="text-left p-3 text-sm font-semibold text-gray-700 border-b border-gray-200">Módulo</th>
                                <th class="text-center p-3 text-sm font-semibold text-gray-700 border-b border-gray-200">Ver</th>
                                <th class="text-center p-3 text-sm font-semibold text-gray-700 border-b border-gray-200">Agregar</th>
                                <th class="text-center p-3 text-sm font-semibold text-gray-700 border-b border-gray-200">Editar</th>
                                <th class="text-center p-3 text-sm font-semibold text-gray-700 border-b border-gray-200">Eliminar</th>
                                <th class="text-center p-3 text-sm font-semibold text-gray-700 border-b border-gray-200">Todas</th>
                            </tr>
                        </thead>
                        <tbody id="edit-permissions-body">
                            @php
                                $permissions = \Spatie\Permission\Models\Permission::all();
                                $groupedPermissions = [];
                                
                                // Agrupar permisos por módulo
                                foreach($permissions as $permission) {
                                    $parts = explode('.', $permission->name);
                                    if(count($parts) >= 2) {
                                        $module = $parts[0];
                                        $action = $parts[1];
                                        
                                        if(!isset($groupedPermissions[$module])) {
                                            $groupedPermissions[$module] = [];
                                        }
                                        
                                        $groupedPermissions[$module][$action] = $permission;
                                    }
                                }
                                
                                // Traducir nombres de módulos
                                $moduleTranslations = [
                                    'usuarios' => 'Usuarios',
                                    'departamentos' => 'Departamentos', 
                                    'insumos' => 'Insumos',
                                    'tipos_insumos' => 'Tipos de Insumos',
                                    'unidades_medida' => 'Unidades de Medida',
                                    'proveedores' => 'Proveedores',
                                    'facturas' => 'Facturas',
                                    'solicitudes' => 'Solicitudes',
                                    'roles' => 'Roles',
                                    'permisos' => 'Permisos',
                                    'reportes' => 'Reportes',
                                    'configuracion' => 'Configuración'
                                ];
                                
                                // Definir acciones en orden
                                $actions = ['ver', 'crear', 'editar', 'eliminar'];
                                $actionLabels = [
                                    'ver' => 'Ver',
                                    'crear' => 'Agregar',
                                    'editar' => 'Editar', 
                                    'eliminar' => 'Eliminar'
                                ];
                            @endphp
                            
                            @foreach($groupedPermissions as $moduleKey => $modulePermissions)
                                <tr class="hover:bg-white transition-colors bg-secondary-50">
                                    <td class="p-3 text-sm font-medium text-gray-800 border-b border-gray-200">
                                        {{ $moduleTranslations[$moduleKey] ?? ucfirst($moduleKey) }}
                                    </td>
                                    
                                    @foreach($actions as $action)
                                        <td class="text-center p-3 border-b border-gray-200">
                                            @if(isset($modulePermissions[$action]))
                                                <input type="checkbox" 
                                                       id="edit_permission_{{ $modulePermissions[$action]->name }}" 
                                                       name="permissions[]" 
                                                       value="{{ $modulePermissions[$action]->name }}"
                                                       class="edit-permission-checkbox w-4 h-4 border-gray-300 rounded text-primary-600 focus:ring-primary-500"
                                                       data-module="{{ $moduleKey }}">
                                            @else
                                                <span class="text-gray-300">-</span>
                                            @endif
                                        </td>
                                    @endforeach
                                    
                                    <td class="text-center p-3 border-b border-gray-200">
                                        <input type="checkbox" 
                                               class="edit-module-select-all w-4 h-4 border-gray-300 rounded text-primary-600 focus:ring-primary-500"
                                               data-module="{{ $moduleKey }}">
                                    </td>
                                </tr>
                    @endforeach
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
        document.addEventListener('DOMContentLoaded', function() {
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
                    const moduleCheckboxes = document.querySelectorAll(`.permission-checkbox[data-module="${moduleName}"]`);
                    const selectAllCheckbox = document.querySelector(`.module-select-all[data-module="${moduleName}"]`);
                    
                    const checkedCount = Array.from(moduleCheckboxes).filter(cb => cb.checked).length;
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
                    const moduleCheckboxes = document.querySelectorAll(`.edit-permission-checkbox[data-module="${moduleName}"]`);
                    const selectAllCheckbox = document.querySelector(`.edit-module-select-all[data-module="${moduleName}"]`);
                    
                    const checkedCount = Array.from(moduleCheckboxes).filter(cb => cb.checked).length;
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
                    
                    // Si la contraseña está vacía, eliminarla del FormData
                    const contrasena = formData.get('contrasena');
                    if (!contrasena || contrasena.trim() === '') {
                        formData.delete('contrasena');
                        formData.delete('contrasena_confirmation');
                    }
                    
                    // Deshabilitar botón durante el envío
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Actualizando...';
                    
                    // Crear token CSRF si no existe
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                                     formData.get('_token');
                    
                    fetch(`/users/${run}`, {
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
                            let errorMessage = data.message || 'Error al actualizar el usuario';
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
                        alert('Error al actualizar el usuario. Por favor, intenta nuevamente.');
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalText;
                    });
                });
            }
        });

        // Función global para abrir el modal de editar
        function openEditModal(run) {
            // Mostrar indicador de carga
            const runInput = document.getElementById('edit-run');
            const nombreInput = document.getElementById('edit-nombre');
            const correoInput = document.getElementById('edit-correo');
            const deptoSelect = document.getElementById('edit-id_depto');
            
            // Limpiar formulario
            runInput.value = '';
            nombreInput.value = '';
            correoInput.value = '';
            deptoSelect.value = '';
            document.getElementById('edit-contrasena').value = '';
            document.getElementById('edit-contrasena_confirmation').value = '';
            
            // Desmarcar todos los permisos
            document.querySelectorAll('.edit-permission-checkbox').forEach(cb => cb.checked = false);
            document.querySelectorAll('.edit-module-select-all').forEach(cb => {
                cb.checked = false;
                cb.indeterminate = false;
            });
            
            // Abrir modal
            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'edit-user' }));
            
            // Cargar datos del usuario
            fetch(`/users/${run}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data) {
                        const user = data.data;
                        
                        // Llenar campos del formulario
                        document.getElementById('edit-user-run').value = user.run;
                        runInput.value = user.run;
                        nombreInput.value = user.nombre || '';
                        correoInput.value = user.correo || '';
                        deptoSelect.value = user.id_depto || '';
                        
                        // Marcar permisos del usuario
                        if (user.permissions && user.permissions.length > 0) {
                            user.permissions.forEach(permission => {
                                const checkbox = document.getElementById(`edit_permission_${permission.name}`);
                                if (checkbox) {
                                    checkbox.checked = true;
                                }
                            });
                            
                            // Actualizar estado de checkboxes "Todas"
                            document.querySelectorAll('.edit-module-select-all').forEach(function(selectAllCheckbox) {
                                const moduleName = selectAllCheckbox.dataset.module;
                                const moduleCheckboxes = document.querySelectorAll(`.edit-permission-checkbox[data-module="${moduleName}"]`);
                                const checkedCount = Array.from(moduleCheckboxes).filter(cb => cb.checked).length;
                                const totalCount = moduleCheckboxes.length;
                                
                                if (checkedCount === 0) {
                                    selectAllCheckbox.checked = false;
                                    selectAllCheckbox.indeterminate = false;
                                } else if (checkedCount === totalCount) {
                                    selectAllCheckbox.checked = true;
                                    selectAllCheckbox.indeterminate = false;
                                } else {
                                    selectAllCheckbox.checked = false;
                                    selectAllCheckbox.indeterminate = true;
                                }
                            });
                        }
                    } else {
                        alert('Error al cargar los datos del usuario');
                        window.dispatchEvent(new CustomEvent('close-modal'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al cargar los datos del usuario');
                    window.dispatchEvent(new CustomEvent('close-modal'));
                });
        }
    </script>
</x-app-layout>