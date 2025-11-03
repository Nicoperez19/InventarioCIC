<div class="w-full overflow-hidden bg-white border rounded-lg shadow-sm border-neutral-200">
    <!-- Tabla -->
    <div class="w-full overflow-x-auto">
        <table class="w-full divide-y table-fixed divide-neutral-200">
            <thead class="bg-primary-100">
                <tr>
                    <th
                        class="w-1/12 px-3 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase sm:px-6">
                        <div class="flex items-center space-x-1">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                </path>
                            </svg>
                            <span class="hidden sm:inline">RUT</span>
                        </div>
                    </th>
                    <th
                        class="w-3/12 px-3 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase sm:px-6">
                        <div class="flex items-center pl-6 space-x-1">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                            <span class="hidden sm:inline">Nombre</span>
                        </div>
                    </th>
                    <th
                        class="w-2/12 px-3 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase sm:px-6">
                        <div class="flex items-center pl-6 space-x-1">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                </path>
                            </svg>
                            <span class="hidden sm:inline">Teléfono</span>
                        </div>
                    </th>
                    <th class="w-2/12 px-3 py-4 text-xs font-semibold tracking-wider text-gray-600 uppercase sm:px-6">
                        <div class="flex items-center justify-end pr-6 space-x-1">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4">
                                </path>
                            </svg>
                            <span class="hidden sm:inline">Acciones</span>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-neutral-200">
                @forelse($proveedores as $proveedor)
                    <tr wire:key="proveedor-{{ $proveedor->id }}"
                        class="transition-colors duration-150 hover:bg-secondary-50">
                        <td class="w-1/12 px-3 py-4 sm:px-6 whitespace-nowrap">
                            <div class="text-sm font-medium text-neutral-500">
                                {{ $proveedor->rut }}
                            </div>
                        </td>
                        <td class="w-3/12 px-3 py-4 sm:px-6">
                            <div class="pl-6 text-sm font-medium text-neutral-900">{{ $proveedor->nombre_proveedor }}</div>
                        </td>
                        <td class="w-2/12 px-3 py-4 sm:px-6">
                            <div class="pl-6 text-sm text-neutral-600">{{ $proveedor->telefono ?? 'Sin teléfono' }}</div>
                        </td>

                        <td class="w-2/12 px-3 py-4 text-sm font-medium sm:px-6 whitespace-nowrap">
                            <div class="flex items-center justify-end space-x-1 sm:space-x-3">
                                <!-- Botón Editar -->
                                <button type="button"
                                    onclick="window.dispatchEvent(new CustomEvent('open-edit-modal', { detail: { id: {{ $proveedor->id }}, rut: {{ json_encode($proveedor->rut) }}, nombre_proveedor: {{ json_encode($proveedor->nombre_proveedor) }}, telefono: {{ json_encode($proveedor->telefono ?? '') }} } }))"
                                    class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-600 transition-colors duration-150 border border-transparent rounded-md sm:px-3 sm:py-2 bg-blue-50 hover:bg-blue-600 hover:text-white active:bg-blue-700 active:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-3 h-3 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                    <span class="hidden sm:inline">Editar</span>
                                </button>

                                <!-- Botón Eliminar -->
                                <form action="{{ route('proveedores.destroy', $proveedor->id) }}" method="POST"
                                    class="inline"
                                    onsubmit="return confirm('¿Estás seguro de que deseas eliminar el proveedor \'{{ $proveedor->nombre_proveedor }}\'? Esta acción no se puede deshacer.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-600 transition-colors duration-150 border border-transparent rounded-md sm:px-3 sm:py-2 bg-red-50 hover:bg-red-600 hover:text-white active:bg-red-700 active:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <svg class="w-3 h-3 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                        <span class="hidden sm:inline">Eliminar</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-3 py-12 text-center sm:px-6" colspan="4">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 mb-4 text-neutral-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                                <h3 class="mb-2 text-lg font-medium text-neutral-900">No hay proveedores</h3>
                                <p class="text-neutral-500">Comienza creando tu primer proveedor para organizar tu sistema.
                                </p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="px-4 py-3 border-t bg-primary-50 border-neutral-200">
        {{ $proveedores->links() }}
    </div>