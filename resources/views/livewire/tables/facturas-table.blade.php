<div>
    <div class="w-full overflow-hidden bg-white border rounded-lg shadow-sm border-neutral-200">
        <!-- Tabla -->
        <div class="w-full overflow-x-auto">
            <table class="w-full divide-y table-fixed divide-neutral-200">
                <thead class="bg-primary-100">
                    <tr>
                        <th
                            class="w-2/12 px-3 py-4 text-xs font-semibold tracking-wider text-center text-gray-600 uppercase sm:px-6">
                            <div class="flex items-center justify-center space-x-1">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <span class="hidden sm:inline">Número</span>
                            </div>
                        </th>
                        <th
                            class="w-2/12 px-3 py-4 text-xs font-semibold tracking-wider text-center text-gray-600 uppercase sm:px-6">
                            <div class="flex items-center justify-center space-x-1">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                                <span class="hidden sm:inline">Proveedor</span>
                            </div>
                        </th>
                        <th
                            class="w-2/12 px-3 py-4 text-xs font-semibold tracking-wider text-center text-gray-600 uppercase sm:px-6">
                            <div class="flex items-center justify-center space-x-1">

                                <span class="hidden sm:inline">Monto</span>
                            </div>
                        </th>
                        <th
                            class="w-2/12 px-3 py-4 text-xs font-semibold tracking-wider text-center text-gray-600 uppercase sm:px-6">
                            <div class="flex items-center justify-center space-x-1">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span class="hidden sm:inline">Fecha</span>
                            </div>
                        </th>

                        <th
                            class="w-4/12 px-3 py-4 text-xs font-semibold tracking-wider text-center text-gray-600 uppercase sm:px-6">
                            <div class="flex items-center justify-center space-x-1">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
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
                    @forelse($facturas as $factura)
                        <tr wire:key="factura-{{ $factura->id }}"
                            class="transition-colors duration-150 hover:bg-secondary-50">
                            <td class="w-2/12 px-3 py-4 sm:px-6 whitespace-nowrap text-center">
                                <div class="text-sm font-medium text-neutral-900">
                                    {{ $factura->numero_factura }}
                                </div>
                            </td>
                            <td class="w-2/12 px-3 py-4 sm:px-6 text-center">
                                <div class="text-sm font-medium text-neutral-900">
                                    {{ $factura->proveedor->nombre_proveedor ?? 'Sin proveedor' }}
                                </div>
                            </td>
                            <td class="w-2/12 px-3 py-4 sm:px-6 text-center">
                                <div class="text-sm text-neutral-600">
                                    ${{ number_format($factura->monto_total, 0, ',', '.') }}</div>
                            </td>
                            <td class="w-2/12 px-3 py-4 sm:px-6 text-center">
                                <div class="text-sm text-neutral-600">{{ $factura->fecha_factura->format('d/m/Y') }}</div>
                            </td>

                            <td class="w-4/12 px-3 py-4 text-sm font-medium sm:px-6 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center space-x-1 sm:space-x-3">

                                    <!-- Botón Ver Datos -->
                                    <button type="button"
                                        @click="$dispatch('open-modal', 'ver-datos-factura-{{ $factura->id }}')"
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-600 transition-colors duration-150 border border-transparent rounded-md sm:px-3 sm:py-2 bg-green-50 hover:bg-green-600 hover:text-white active:bg-green-700 active:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        <svg class="w-3 h-3 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                        <span class="hidden sm:inline">Ver datos</span>
                                    </button>

                                    @if($factura->tieneArchivo())
                                        <!-- Botón Descargar -->
                                        <a href="{{ route('facturas.download', $factura->id) }}"
                                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-600 transition-colors duration-150 border border-transparent rounded-md sm:px-3 sm:py-2 bg-blue-50 hover:bg-blue-600 hover:text-white active:bg-blue-700 active:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <svg class="w-3 h-3 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            <span class="hidden sm:inline">Descargar</span>
                                        </a>
                                    @endif

                                    <!-- Botón Eliminar -->
                                    <form action="{{ route('facturas.destroy', $factura->id) }}" method="POST"
                                        class="inline"
                                        onsubmit="return confirm('¿Estás seguro de que deseas eliminar la factura \'{{ $factura->numero_factura }}\'? Esta acción no se puede deshacer.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-600 transition-colors duration-150 border border-transparent rounded-md sm:px-3 sm:py-2 bg-red-50 hover:bg-red-600 hover:text-white active:bg-red-700 active:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            <svg class="w-3 h-3 sm:mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
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
                            <td class="px-3 py-12 text-center sm:px-6" colspan="5">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 mb-4 text-neutral-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    <h3 class="mb-2 text-lg font-medium text-neutral-900">No hay facturas</h3>
                                    <p class="text-neutral-500">Comienza creando tu primera factura para organizar tu
                                        sistema.
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
            {{ $facturas->links() }}
        </div>
    </div>

    <!-- Modales para ver datos de facturas -->
    @foreach($facturas as $factura)
        <x-modal name="ver-datos-factura-{{ $factura->id }}" title="Datos de la Factura" maxWidth="2xl">
            <div class="space-y-6">
                <!-- Información de la Factura -->
                <div class="p-4 bg-gray-50 border border-neutral-200 rounded-lg">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Información de la Factura</h3>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Número de Factura -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Número de Factura</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $factura->numero_factura }}</p>
                        </div>

                        <!-- Proveedor -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Proveedor</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $factura->proveedor->nombre_proveedor ?? 'Sin proveedor' }}</p>
                        </div>

                        <!-- Monto Total -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Monto Total</label>
                            <p class="mt-1 text-sm font-semibold text-gray-900">
                                ${{ number_format($factura->monto_total, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Información de Subida -->
                <div class="p-4 bg-gray-50 border border-neutral-200 rounded-lg">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Información de Subida</h3>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Usuario que subió -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Subida por</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $factura->user->nombre ?? 'N/A' }}</p>
                        </div>

                        <!-- RUN del Usuario -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">RUN</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $factura->user->run ?? 'N/A' }}</p>
                        </div>

                        <!-- Fecha de Subida -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fecha de Subida</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $factura->created_at->format('d/m/Y') }}</p>
                        </div>

                        <!-- Hora de Subida -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Hora de Subida</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $factura->created_at->format('H:i:s') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </x-modal>
    @endforeach
</div>