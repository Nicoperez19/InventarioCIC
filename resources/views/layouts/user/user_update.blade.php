<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-dark-teal rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
            </div>
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    {{ __('Editar usuario') }} - {{ $user->run }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">Modifica la información del usuario {{ $user->nombre }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('users.update', $user) }}" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Información básica -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Información básica</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">RUN</label>
                                <input type="text" value="{{ $user->run }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100 text-gray-500" 
                                       disabled>
                                <p class="text-xs text-gray-500 mt-1">El RUN no se puede modificar</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nombre completo</label>
                                <input type="text" name="nombre" value="{{ old('nombre', $user->nombre) }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-dark-teal focus:border-dark-teal" 
                                       required>
                                @error('nombre')
                                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" name="correo" value="{{ old('correo', $user->correo) }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-dark-teal focus:border-dark-teal" 
                                       required>
                                @error('correo')
                                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Departamento</label>
                                <select name="id_depto" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-dark-teal focus:border-dark-teal" 
                                        required>
                                    @foreach ($departamentos as $depto)
                                        <option value="{{ $depto->id_depto }}" {{ old('id_depto', $user->id_depto) === $depto->id_depto ? 'selected' : '' }}>
                                            {{ $depto->nombre_depto }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_depto')
                                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Contraseña -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Cambiar contraseña</h3>
                        <div class="max-w-md">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nueva contraseña (opcional)</label>
                                <input type="password" name="contrasena" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-dark-teal focus:border-dark-teal" 
                                       placeholder="Dejar en blanco para mantener la contraseña actual">
                                @error('contrasena')
                                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                @enderror
                                <p class="text-xs text-gray-500 mt-1">Deja este campo vacío si no deseas cambiar la contraseña</p>
                            </div>
                        </div>
                    </div>

                    <!-- Permisos -->
                    <div class="pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Permisos del usuario</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-64 overflow-y-auto">
                                @foreach ($permissions as $permission)
                                    <div class="flex items-center">
                                        <input id="permission-{{ $permission->id }}" type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                               {{ $user->permissions->contains('name', $permission->name) ? 'checked' : '' }}
                                               class="h-4 w-4 text-dark-teal focus:ring-dark-teal border-gray-300 rounded">
                                        <label for="permission-{{ $permission->id }}" class="ml-2 text-sm text-gray-700">
                                            {{ $permission->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('users') }}" 
                           class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-dark-teal">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-dark-teal border border-transparent rounded-md shadow-sm hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-dark-teal">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

