<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Agregar nuevo usuario
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('users.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">RUN</label>
                        <input type="text" name="run" value="{{ old('run') }}" class="mt-1 block w-full border-gray-300 rounded-md" required>
                        @error('run')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}" class="mt-1 block w-full border-gray-300 rounded-md" required>
                        @error('nombre')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="correo" value="{{ old('correo') }}" class="mt-1 block w-full border-gray-300 rounded-md" required>
                        @error('correo')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Contraseña</label>
                        <input type="password" name="contrasena" class="mt-1 block w-full border-gray-300 rounded-md" required>
                        @error('contrasena')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Confirmar Contraseña</label>
                        <input type="password" name="contrasena_confirmation" class="mt-1 block w-full border-gray-300 rounded-md" required>
                        @error('contrasena_confirmation')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Departamento</label>
                        <select name="id_depto" class="mt-1 block w-full border-gray-300 rounded-md" required>
                            <option value="">Seleccione...</option>
                            @foreach ($departamentos as $depto)
                                <option value="{{ $depto->id_depto }}" {{ old('id_depto') === $depto->id_depto ? 'selected' : '' }}>
                                    {{ $depto->nombre_depto }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_depto')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="p-4 border rounded-lg shadow-lg">
                            <div class="py-2 text-lg font-semibold text-center bg-gray-200 rounded-t-lg">
                                {{ __('Permisos') }}
                            </div>
                            <div class="p-2 overflow-y-auto max-h-64">
                                <ul>
                                    @foreach ($permissions as $permission)
                                        <li class="flex items-center mb-2">
                                            <input id="permission-{{ $permission->id }}" type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                                {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}
                                                class="mr-2" />
                                            <label for="permission-{{ $permission->id }}">{{ $permission->name }}</label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <a href="{{ route('users') }}" class="px-4 py-2 bg-gray-200 rounded">Cancelar</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Crear Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

