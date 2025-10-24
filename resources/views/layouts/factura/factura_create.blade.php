<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Factura') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('facturas.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="numero_factura" class="block text-sm font-medium text-gray-700">Número de Factura</label>
                                <input type="text" 
                                       name="numero_factura" 
                                       id="numero_factura" 
                                       value="{{ old('numero_factura') }}"
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
                                        <option value="{{ $proveedor->id }}" {{ old('proveedor_id') == $proveedor->id ? 'selected' : '' }}>
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
                                       value="{{ old('monto_total') }}"
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
                                       value="{{ old('fecha_factura', date('Y-m-d')) }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('fecha_factura') border-red-500 @enderror"
                                       required>
                                @error('fecha_factura')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="archivo" class="block text-sm font-medium text-gray-700">Archivo de Factura (PDF, JPG, PNG)</label>
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
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('observaciones') border-red-500 @enderror">{{ old('observaciones') }}</textarea>
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
                                Crear Factura
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

