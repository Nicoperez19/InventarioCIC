<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Agregar nueva unidad
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('unidades.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">ID</label>
                        <input type="text" name="id_unidad" value="{{ old('id_unidad') }}" class="mt-1 block w-full border-gray-300 rounded-md" required>
                        @error('id_unidad')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" name="nombre_unidad" value="{{ old('nombre_unidad') }}" class="mt-1 block w-full border-gray-300 rounded-md" required>
                        @error('nombre_unidad')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="flex gap-3">
                        <a href="{{ route('unidades') }}" class="px-4 py-2 bg-gray-200 rounded">Cancelar</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Crear Unidad</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>


