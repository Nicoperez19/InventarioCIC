<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle de Proveedor') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">{{ $proveedor->nombre_proveedor }}</h3>
                        <div class="flex space-x-2">
                            <a href="{{ route('proveedores.edit', $proveedor) }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                Editar
                            </a>
                            <a href="{{ route('proveedores.index') }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md text-sm font-medium">
                                Volver
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">RUT</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $proveedor->rut_formateado }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nombre del Proveedor</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $proveedor->nombre_proveedor }}</p>
                            </div>

                            @if($proveedor->telefono)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $proveedor->telefono }}</p>
                                </div>
                            @endif
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Fecha de Registro</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $proveedor->created_at->format('d/m/Y H:i') }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Total de Facturas</label>
                                <p class="mt-1 text-lg font-semibold text-gray-900">{{ $proveedor->facturas_count ?? 0 }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Monto Total Facturado</label>
                                <p class="mt-1 text-lg font-semibold text-green-600">${{ number_format($proveedor->facturas_sum_monto_total ?? 0, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    @if($proveedor->facturas_count > 0)
                        <div class="mt-8">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Facturas Asociadas</h4>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    @foreach($proveedor->facturas->take(6) as $factura)
                                        <div class="bg-white p-3 rounded border">
                                            <p class="text-sm font-medium text-gray-900">{{ $factura->numero_factura }}</p>
                                            <p class="text-xs text-gray-500">{{ $factura->fecha_factura->format('d/m/Y') }}</p>
                                            <p class="text-sm text-green-600 font-semibold">${{ number_format($factura->monto_total, 0, ',', '.') }}</p>
                                        </div>
                                    @endforeach
                                </div>
                                @if($proveedor->facturas_count > 6)
                                    <p class="text-sm text-gray-500 mt-2">Y {{ $proveedor->facturas_count - 6 }} facturas más...</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

