<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Agregar nuevo rol
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('roles.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nombre del Rol</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="mt-1 block w-full border-gray-300 rounded-md" required>
                        @error('name')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <div class="bg-gray-50 rounded-lg border border-gray-200 overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gradient-to-r from-blue-50 to-blue-100">
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
                                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider bg-blue-100">
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
                                            <tr class="hover:bg-blue-50/50 transition-colors" data-row="{{ $rowId }}">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-8 w-8 bg-light-cyan/10 rounded-lg flex items-center justify-center">
                                                            <svg class="h-5 w-5 text-light-cyan" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                                                   {{ in_array($actionsMap[$action]->id, old('permissions', [])) ? 'checked' : '' }}
                                                                   class="permission-checkbox h-4 w-4 text-light-cyan focus:ring-light-cyan border-gray-300 rounded cursor-pointer"
                                                                   data-row="{{ $rowId }}"
                                                                   title="{{ translatePermission($actionsMap[$action]->name) }}">
                                                        @else
                                                            <span class="text-gray-300">—</span>
                                                        @endif
                                                    </td>
                                                @endforeach
                                                
                                                <!-- Checkbox "Todo" al final -->
                                                <td class="px-4 py-4 text-center bg-blue-50/30">
                                                    <input type="checkbox" 
                                                           class="check-all h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer"
                                                           data-row="{{ $rowId }}"
                                                           title="Seleccionar todos los permisos de {{ $contextName }}">
                                                </td>
                                            </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <a href="{{ route('roles.index') }}" class="px-4 py-2 bg-gray-200 rounded">Cancelar</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Crear Rol</button>
                    </div>
                </form>
            </div>
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
