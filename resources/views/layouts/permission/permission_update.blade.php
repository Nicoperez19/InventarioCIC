<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar permiso #{{ $permission->id }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('permissions.update', $permission) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" name="name" value="{{ old('name', $permission->name) }}" class="mt-1 block w-full border-gray-300 rounded-md" required>
                        @error('name')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="flex gap-3">
                        <a href="{{ route('permissions.index') }}" class="px-4 py-2 bg-gray-200 rounded">Cancelar</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>


