<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle de Factura') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Factura #{{ $factura->numero_factura }}</h3>
                        <div class="flex space-x-2">
                            <a href="{{ route('facturas.edit', $factura) }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                Editar
                            </a>
                            <a href="{{ route('facturas.index') }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md text-sm font-medium">
                                Volver
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Número de Factura</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $factura->numero_factura }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Proveedor</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $factura->proveedor->nombre_proveedor }}</p>
                                <p class="text-xs text-gray-500">{{ $factura->proveedor->rut_formateado }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Monto Total</label>
                                <p class="mt-1 text-lg font-semibold text-gray-900">${{ number_format($factura->monto_total, 0, ',', '.') }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Fecha de Factura</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $factura->fecha_factura->format('d/m/Y') }}</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Fecha de Registro</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $factura->created_at->format('d/m/Y H:i') }}</p>
                            </div>

                            @if($factura->tieneArchivo())
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Archivo</label>
                                    <div class="mt-1 flex items-center space-x-2">
                                        <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <span class="text-sm text-gray-900">{{ $factura->archivo_nombre }}</span>
                                        <a href="{{ route('facturas.download', $factura) }}" 
                                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            Descargar
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Archivo</label>
                                    <p class="mt-1 text-sm text-gray-500">Sin archivo adjunto</p>
                                </div>
                            @endif

                            @if($factura->observaciones)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Observaciones</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $factura->observaciones }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

