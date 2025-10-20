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
                    {{ __('Agregar nuevo usuario') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">Crea un nuevo usuario en el sistema</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('users.store') }}" class="p-6 space-y-6">
                    @csrf

                    <!-- Información básica -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Información básica</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">RUN</label>
                                <input id="run-input" type="text" name="run" value="{{ old('run') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-dark-teal focus:border-dark-teal" 
                                       required inputmode="numeric" autocomplete="off" placeholder="12345678-9">
                                @error('run')
                                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nombre completo</label>
                                <input type="text" name="nombre" value="{{ old('nombre') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-dark-teal focus:border-dark-teal" 
                                       required>
                                @error('nombre')
                                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" name="correo" value="{{ old('correo') }}" 
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
                                    <option value="">Seleccione un departamento...</option>
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
                        </div>
                    </div>

                    <!-- Contraseña -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Contraseña</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Contraseña</label>
                                <input type="password" name="contrasena" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-dark-teal focus:border-dark-teal" 
                                       required>
                                @error('contrasena')
                                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Confirmar contraseña</label>
                                <input type="password" name="contrasena_confirmation" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-dark-teal focus:border-dark-teal" 
                                       required>
                                @error('contrasena_confirmation')
                                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                @enderror
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
                                               {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}
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
                            Crear Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    (function () {
        const input = document.getElementById('run-input');
        if (!input) return;

        const formatRun = (raw) => {
            // Mantener solo dígitos y posible 'K'/'k' como dígito verificador
            const cleaned = raw.replace(/[^0-9kK]/g, '');
            if (cleaned.length === 0) return '';

            // DV es el último carácter si hay al menos 2
            if (cleaned.length === 1) return cleaned;
            const body = cleaned.slice(0, -1);
            const dv = cleaned.slice(-1).toUpperCase();
            return body + '-' + dv;
        };

        let lastValue = input.value;
        const applyFormat = () => {
            const start = input.selectionStart;
            const before = input.value;
            const formatted = formatRun(before);
            input.value = formatted;
            // Intento simple de preservar el caret
            const delta = formatted.length - before.length;
            const newPos = Math.max(0, (start ?? formatted.length) + delta);
            input.setSelectionRange(newPos, newPos);
            lastValue = formatted;
        };

        input.addEventListener('input', applyFormat);
        input.addEventListener('blur', applyFormat);
        // Formatear si viene con valor previo
        if (input.value) applyFormat();
    })();
</script>
