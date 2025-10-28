<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center space-x-3 sm:space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-secondary-400 to-secondary-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></circle>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                </div>
                <div class="min-w-0 flex-1">
                    <h2 class="text-lg sm:text-xl font-semibold leading-tight text-gray-800 truncate">
                        {{ __('Editar usuario') }} - {{ $user->run }}
                    </h2>
                    <p class="text-xs sm:text-sm text-gray-600 mt-1 hidden sm:block">Modifica la información del usuario {{ $user->nombre }}</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Información básica -->
                <div class="p-4 bg-white shadow sm:p-8 sm:rounded-lg">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 text-primary-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Información Personal
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Datos básicos del usuario</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <svg class="w-4 h-4 text-gray-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                </svg>
                                RUN
                            </label>
                            <input type="text" value="{{ $user->run }}" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm bg-gray-100 text-gray-500" 
                                   disabled>
                            <p class="text-xs text-gray-500 mt-1">El RUN no se puede modificar</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <svg class="w-4 h-4 text-gray-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Nombre completo
                            </label>
                            <input type="text" name="nombre" value="{{ old('nombre', $user->nombre) }}" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400 transition-colors" 
                                   required placeholder="Ingrese el nombre completo">
                            @error('nombre')
                                <div class="flex items-center mt-2 text-sm text-red-600">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <svg class="w-4 h-4 text-gray-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Email
                            </label>
                            <input type="email" name="correo" value="{{ old('correo', $user->correo) }}" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400 transition-colors" 
                                   required placeholder="usuario@ejemplo.cl">
                            @error('correo')
                                <div class="flex items-center mt-2 text-sm text-red-600">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <svg class="w-4 h-4 text-gray-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                Departamento
                            </label>
                            <select name="id_depto" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400 transition-colors" 
                                    required>
                                @foreach ($departamentos as $depto)
                                    <option value="{{ $depto->id_depto }}" {{ old('id_depto', $user->id_depto) === $depto->id_depto ? 'selected' : '' }}>
                                        {{ $depto->nombre_depto }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_depto')
                                <div class="flex items-center mt-2 text-sm text-red-600">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Contraseña -->
                <div class="p-4 bg-white shadow sm:p-8 sm:rounded-lg">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 text-primary-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            Seguridad
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Cambia la contraseña del usuario (opcional)</p>
                    </div>

                    <div class="max-w-md">
                        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <svg class="w-4 h-4 text-gray-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                            </svg>
                            Nueva Contraseña (opcional)
                        </label>
                        <input type="password" name="contrasena" 
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400 transition-colors" 
                               placeholder="••••••••">
                        <p class="text-xs text-gray-500 mt-1">Deja este campo vacío si no deseas cambiar la contraseña</p>
                        @error('contrasena')
                            <div class="flex items-center mt-2 text-sm text-red-600">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Permisos -->
                <div class="p-4 bg-white shadow sm:p-8 sm:rounded-lg">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 text-primary-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            Permisos y Accesos
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Selecciona los permisos que tendrá el usuario en el sistema</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg border border-gray-200 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-primary-50 via-secondary-50 to-primary-100">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider w-48">
                                            Módulo
                                        </th>
                                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                            Ver
                                        </th>
                                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                            Agregar
                                        </th>
                                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                            Editar
                                        </th>
                                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                            Eliminar
                                        </th>
                                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider bg-gradient-to-r from-primary-100 to-secondary-100">
                                            <div class="flex items-center justify-center gap-1">
                                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Todo
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @php
                                        $groupedPermissions = groupPermissionsByContext($permissions);
                                    @endphp
                                    
                                    @foreach ($groupedPermissions as $contextName => $context)
                                        @php
                                            $rowId = 'row-' . str_replace(' ', '-', strtolower($contextName));
                                        @endphp
                                        <tr class="hover:bg-gradient-to-r hover:from-primary-50 hover:to-secondary-50 transition-all duration-200" data-row="{{ $rowId }}">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-8 w-8 bg-gradient-to-br from-primary-50 to-secondary-50 rounded-lg flex items-center justify-center">
                                                        <svg class="h-5 w-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $context['icon'] }}"></path>
                                                        </svg>
                                                    </div>
                                                    <span class="ml-3 text-sm font-semibold text-gray-900">{{ $contextName }}</span>
                                                </div>
                                            </td>
                                            
                                            @php
                                                $actionsMap = [
                                                    'view' => null,
                                                    'create' => null,
                                                    'edit' => null,
                                                    'delete' => null
                                                ];
                                                
                                                foreach ($context['permissions'] as $perm) {
                                                    $action = getPermissionAction($perm->name);
                                                    if ($action === 'view') $actionsMap['view'] = $perm;
                                                    elseif ($action === 'create') $actionsMap['create'] = $perm;
                                                    elseif ($action === 'edit') $actionsMap['edit'] = $perm;
                                                    elseif ($action === 'delete') $actionsMap['delete'] = $perm;
                                                }
                                            @endphp
                                            
                                            @foreach (['view', 'create', 'edit', 'delete'] as $action)
                                                <td class="px-4 py-4 text-center">
                                                    @if ($actionsMap[$action])
                                                        <input type="checkbox" 
                                                               name="permissions[]" 
                                                               value="{{ $actionsMap[$action]->id }}"
                                                               {{ $user->permissions->contains('name', $actionsMap[$action]->name) ? 'checked' : '' }}
                                                               class="permission-checkbox h-4 w-4 text-primary-400 focus:ring-primary-400 border-gray-300 rounded cursor-pointer"
                                                               data-row="{{ $rowId }}"
                                                               title="{{ translatePermission($actionsMap[$action]->name) }}">
                                                    @else
                                                        <span class="text-gray-300">—</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                            
                                            <!-- Checkbox "Todo" al final -->
                                            <td class="px-4 py-4 text-center bg-gradient-to-r from-primary-50/30 to-secondary-50/30">
                                                <input type="checkbox" 
                                                       class="check-all h-5 w-5 text-secondary-500 focus:ring-secondary-400 border-gray-300 rounded cursor-pointer"
                                                       data-row="{{ $rowId }}"
                                                       title="Seleccionar todos los permisos de {{ $contextName }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex items-center justify-end pt-8 mt-8 border-t border-gray-200 space-x-3">
                        <a href="{{ route('users') }}" 
                           class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-400 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-primary-500 to-secondary-500 rounded-lg shadow-sm hover:from-primary-600 hover:to-secondary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-400 transition-all duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Guardar Cambios
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

<script>
    // Funcionalidad de checkbox "Todo"
    document.addEventListener('DOMContentLoaded', function() {
        // Manejar clicks en checkboxes "Todo"
        document.querySelectorAll('.check-all').forEach(function(checkAll) {
            checkAll.addEventListener('change', function() {
                const rowId = this.dataset.row;
                const isChecked = this.checked;
                
                // Marcar/desmarcar todos los checkboxes de esa fila
                document.querySelectorAll(`.permission-checkbox[data-row="${rowId}"]`).forEach(function(checkbox) {
                    checkbox.checked = isChecked;
                });
            });
        });

        // Actualizar estado del checkbox "Todo" cuando se marcan/desmarcan permisos individuales
        document.querySelectorAll('.permission-checkbox').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const rowId = this.dataset.row;
                updateCheckAllState(rowId);
            });
        });

        // Función para actualizar el estado del checkbox "Todo"
        function updateCheckAllState(rowId) {
            const allCheckboxes = document.querySelectorAll(`.permission-checkbox[data-row="${rowId}"]`);
            const checkAllBox = document.querySelector(`.check-all[data-row="${rowId}"]`);
            
            if (allCheckboxes.length === 0 || !checkAllBox) return;
            
            const checkedCount = Array.from(allCheckboxes).filter(cb => cb.checked).length;
            
            if (checkedCount === 0) {
                checkAllBox.checked = false;
                checkAllBox.indeterminate = false;
            } else if (checkedCount === allCheckboxes.length) {
                checkAllBox.checked = true;
                checkAllBox.indeterminate = false;
            } else {
                checkAllBox.checked = false;
                checkAllBox.indeterminate = true;
            }
        }

        // Inicializar el estado de todos los checkboxes "Todo"
        document.querySelectorAll('.check-all').forEach(function(checkAll) {
            updateCheckAllState(checkAll.dataset.row);
        });
    });
</script>
