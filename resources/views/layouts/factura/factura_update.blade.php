<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Factura') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('facturas.update', $factura) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="numero_factura" class="block text-sm font-medium text-gray-700">Número de Factura</label>
                                <input type="text" 
                                       name="numero_factura" 
                                       id="numero_factura" 
                                       value="{{ old('numero_factura', $factura->numero_factura) }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('numero_factura') border-red-500 @enderror"
                                       required>
                                @error('numero_factura')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="proveedor_id" class="block text-sm font-medium text-gray-700">Proveedor</label>
                                <select name="proveedor_id" 
                                        id="proveedor_id" 
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('proveedor_id') border-red-500 @enderror"
                                        required>
                                    <option value="">Seleccionar proveedor</option>
                                    @foreach($proveedores as $proveedor)
                                        <option value="{{ $proveedor->id }}" {{ old('proveedor_id', $factura->proveedor_id) == $proveedor->id ? 'selected' : '' }}>
                                            {{ $proveedor->nombre_proveedor }} ({{ $proveedor->rut_formateado }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('proveedor_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="monto_total" class="block text-sm font-medium text-gray-700">Monto Total</label>
                                <input type="number" 
                                       name="monto_total" 
                                       id="monto_total" 
                                       step="0.01"
                                       value="{{ old('monto_total', $factura->monto_total) }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('monto_total') border-red-500 @enderror"
                                       required>
                                @error('monto_total')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="fecha_factura" class="block text-sm font-medium text-gray-700">Fecha de Factura</label>
                                <input type="date" 
                                       name="fecha_factura" 
                                       id="fecha_factura" 
                                       value="{{ old('fecha_factura', $factura->fecha_factura->format('Y-m-d')) }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('fecha_factura') border-red-500 @enderror"
                                       required>
                                @error('fecha_factura')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        @if($factura->tieneArchivo())
                            <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span class="text-sm text-blue-800">Archivo actual: {{ $factura->archivo_nombre }}</span>
                                    <a href="{{ route('facturas.download', $factura) }}" 
                                       class="ml-2 text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        Descargar
                                    </a>
                                </div>
                            </div>
                        @endif

                        <div>
                            <label for="archivo" class="block text-sm font-medium text-gray-700">
                                {{ $factura->tieneArchivo() ? 'Reemplazar Archivo' : 'Archivo de Factura' }} (PDF, JPG, PNG)
                            </label>
                            <input type="file" 
                                   name="archivo" 
                                   id="archivo" 
                                   accept=".pdf,.jpg,.jpeg,.png"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('archivo') border-red-500 @enderror">
                            @error('archivo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="observaciones" class="block text-sm font-medium text-gray-700">Observaciones</label>
                            <textarea name="observaciones" 
                                      id="observaciones" 
                                      rows="3"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('observaciones') border-red-500 @enderror">{{ old('observaciones', $factura->observaciones) }}</textarea>
                            @error('observaciones')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('facturas.index') }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md text-sm font-medium">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                Actualizar Factura
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
