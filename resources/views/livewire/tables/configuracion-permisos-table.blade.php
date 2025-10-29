<div class="w-full bg-white shadow-sm rounded-lg border border-neutral-200 overflow-hidden">
    <!-- Mensajes -->
    @if (session()->has('success'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Información -->
    <div class="p-4 border-b border-gray-200 bg-blue-50">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="text-sm text-blue-800">
                <strong>Configuración de Permisos de Solicitud</strong> - 
                Selecciona qué tipos de insumo puede solicitar cada rol de usuario.
            </div>
        </div>
    </div>

    <!-- Tabla de configuración -->
    <div class="w-full overflow-x-auto">
        <table class="w-full divide-y divide-neutral-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Rol de Usuario
                    </th>
                    @foreach($tiposInsumo as $tipo)
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            {{ $tipo->nombre_tipo }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-neutral-200">
                @foreach($roles as $role)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-blue-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ ucfirst(str_replace('-', ' ', $role->name)) }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $role->users_count ?? 0 }} usuario(s)
                                    </div>
                                </div>
                            </div>
                        </td>
                        @foreach($tiposInsumo as $tipo)
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <button wire:click="togglePermiso({{ $role->id }}, {{ $tipo->id }})"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-full transition-colors duration-200 {{ $permisos[$role->id][$tipo->id] ? 'bg-green-100 text-green-600 hover:bg-green-200' : 'bg-gray-100 text-gray-400 hover:bg-gray-200' }}">
                                    @if($permisos[$role->id][$tipo->id])
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    @endif
                                </button>
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Información adicional -->
    <div class="p-4 border-t border-gray-200 bg-gray-50">
        <div class="text-sm text-gray-600">
            <h4 class="font-medium text-gray-900 mb-2">Instrucciones:</h4>
            <ul class="list-disc list-inside space-y-1">
                <li>Haz clic en los botones para activar/desactivar permisos</li>
                <li>Los usuarios con el rol <strong>Jefe Departamento</strong> pueden solicitar artículos de oficina</li>
                <li>Los usuarios con el rol <strong>Auxiliar</strong> pueden solicitar artículos de aseo</li>
                <li>Los cambios se aplican inmediatamente</li>
            </ul>
        </div>
    </div>
</div>
