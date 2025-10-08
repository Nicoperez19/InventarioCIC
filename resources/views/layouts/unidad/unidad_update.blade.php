<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar unidad
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('unidades.update', $unidad->id_unidad) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">ID</label>
                        <input type="text" value="{{ $unidad->id_unidad }}" class="mt-1 block w-full border-gray-300 rounded-md bg-gray-100" disabled>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" name="nombre_unidad" value="{{ old('nombre_unidad', $unidad->nombre_unidad) }}" class="mt-1 block w-full border-gray-300 rounded-md" required>
                        @error('nombre_unidad')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="flex gap-3">
                        <a href="{{ route('unidades') }}" class="px-4 py-2 bg-gray-200 rounded">Cancelar</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Actualizar Unidad</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>


