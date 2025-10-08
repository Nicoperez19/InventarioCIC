<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Agregar nuevo producto
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('productos.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">ID</label>
                            <input type="text" name="id_producto" value="{{ old('id_producto') }}" class="mt-1 block w-full border-gray-300 rounded-md" required>
                            @error('id_producto')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Código</label>
                            <input type="text" name="codigo_producto" value="{{ old('codigo_producto') }}" class="mt-1 block w-full border-gray-300 rounded-md" required>
                            @error('codigo_producto')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input type="text" name="nombre_producto" value="{{ old('nombre_producto') }}" class="mt-1 block w-full border-gray-300 rounded-md" required>
                            @error('nombre_producto')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Stock mínimo</label>
                            <input type="number" name="stock_minimo" value="{{ old('stock_minimo', 0) }}" class="mt-1 block w-full border-gray-300 rounded-md" required min="0">
                            @error('stock_minimo')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Stock actual</label>
                            <input type="number" name="stock_actual" value="{{ old('stock_actual', 0) }}" class="mt-1 block w-full border-gray-300 rounded-md" required min="0">
                            @error('stock_actual')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Observaciones</label>
                            <textarea name="observaciones" class="mt-1 block w-full border-gray-300 rounded-md" rows="3">{{ old('observaciones') }}</textarea>
                            @error('observaciones')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Unidad</label>
                            <select name="id_unidad" class="mt-1 block w-full border-gray-300 rounded-md" required>
                                <option value="">Seleccione...</option>
                                @foreach($unidades as $u)
                                    <option value="{{ $u->id_unidad }}" @selected(old('id_unidad')===$u->id_unidad)>{{ $u->nombre_unidad }}</option>
                                @endforeach
                            </select>
                            @error('id_unidad')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6">
                        <a href="{{ route('productos') }}" class="px-4 py-2 bg-gray-200 rounded">Cancelar</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Crear Producto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>


