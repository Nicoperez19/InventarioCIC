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
                                <div class="mt-1 flex items-center space-x-2">
                                    <p class="text-lg font-semibold text-gray-900">${{ number_format($factura->monto_total, 0, ',', '.') }}</p>
                                    @if($factura->monto_total == 0)
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded-full">
                                            Pendiente
                                        </span>
                                    @endif
                                </div>
                                @if($factura->monto_total == 0 && $factura->tieneArchivo())
                                    <p class="mt-1 text-xs text-gray-500">Revisa el archivo y actualiza el monto desde la edición</p>
                                @endif
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
                                    <div class="mt-1 flex items-center space-x-2 flex-wrap gap-2">
                                        <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <span class="text-sm text-gray-900">{{ $factura->archivo_nombre }}</span>
                                        <a href="{{ route('facturas.view', $factura) }}" 
                                           target="_blank"
                                           class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Ver
                                        </a>
                                        <a href="{{ route('facturas.download', $factura) }}" 
                                           class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-green-600 bg-green-50 rounded-lg hover:bg-green-100">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                            </svg>
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

                    <!-- Visor de PDF/DOC embebido -->
                    @if($factura->tieneArchivo())
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Vista Previa del Archivo</h4>
                            <div class="w-full bg-gray-100 rounded-lg overflow-hidden" style="height: 600px;">
                                @php
                                    $extension = strtolower(pathinfo($factura->archivo_path, PATHINFO_EXTENSION));
                                @endphp
                                @if($extension === 'pdf')
                                    <iframe src="{{ route('facturas.view', $factura) }}" 
                                            class="w-full h-full border-0"
                                            style="height: 600px;">
                                    </iframe>
                                @else
                                    <div class="flex flex-col items-center justify-center h-full p-8">
                                        <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="text-gray-600 mb-4">La vista previa no está disponible para archivos {{ strtoupper($extension) }}</p>
                                        <a href="{{ route('facturas.view', $factura) }}" 
                                           target="_blank"
                                           class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Abrir Archivo
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>





