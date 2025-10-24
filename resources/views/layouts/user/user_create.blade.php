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
            <form method="POST" action="{{ route('users.store') }}" class="space-y-6">
                @csrf

                <!-- Información básica -->
                <div class="p-4 bg-white shadow sm:p-8 sm:rounded-lg">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 text-light-cyan mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Información Personal
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Datos básicos del usuario</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <svg class="w-4 h-4 text-gray-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                </svg>
                                RUN
                            </label>
                            <input id="run-input" type="text" name="run" value="{{ old('run') }}" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-light-cyan focus:border-light-cyan transition-colors" 
                                   required inputmode="numeric" autocomplete="off" placeholder="12345678-9">
                            @error('run')
                                <div class="flex items-center mt-2 text-sm text-red-600">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <svg class="w-4 h-4 text-gray-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Nombre completo
                            </label>
                            <input type="text" name="nombre" value="{{ old('nombre') }}" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-light-cyan focus:border-light-cyan transition-colors" 
                                   required placeholder="Ingrese el nombre completo">
                            @error('nombre')
                                <div class="flex items-center mt-2 text-sm text-red-600">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <svg class="w-4 h-4 text-gray-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Email
                            </label>
                            <input type="email" name="correo" value="{{ old('correo') }}" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-light-cyan focus:border-light-cyan transition-colors" 
                                   required placeholder="usuario@ejemplo.cl">
                            @error('correo')
                                <div class="flex items-center mt-2 text-sm text-red-600">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <svg class="w-4 h-4 text-gray-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                Departamento
                            </label>
                            <select name="id_depto" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-light-cyan focus:border-light-cyan transition-colors" 
                                    required>
                                <option value="">Seleccione un departamento...</option>
                                @foreach ($departamentos as $depto)
                                    <option value="{{ $depto->id_depto }}" {{ old('id_depto') === $depto->id_depto ? 'selected' : '' }}>
                                        {{ $depto->nombre_depto }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_depto')
                                <div class="flex items-center mt-2 text-sm text-red-600">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Contraseña -->
                <div class="p-4 bg-white shadow sm:p-8 sm:rounded-lg">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 text-light-cyan mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            Seguridad
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Establece una contraseña segura para el usuario</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <svg class="w-4 h-4 text-gray-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                </svg>
                                Contraseña
                            </label>
                            <input type="password" name="contrasena" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-light-cyan focus:border-light-cyan transition-colors" 
                                   required placeholder="••••••••">
                            @error('contrasena')
                                <div class="flex items-center mt-2 text-sm text-red-600">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <svg class="w-4 h-4 text-gray-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                Confirmar contraseña
                            </label>
                            <input type="password" name="contrasena_confirmation" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-light-cyan focus:border-light-cyan transition-colors" 
                                   required placeholder="••••••••">
                            @error('contrasena_confirmation')
                                <div class="flex items-center mt-2 text-sm text-red-600">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Permisos -->
                <div class="p-4 bg-white shadow sm:p-8 sm:rounded-lg">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 text-light-cyan mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            Permisos y Accesos
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Selecciona los permisos que tendrá el usuario en el sistema</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 max-h-80 overflow-y-auto pr-2">
                            @foreach ($permissions as $permission)
                                <label for="permission-{{ $permission->id }}" 
                                       class="flex items-center p-3 bg-white rounded-lg border border-gray-200 hover:border-light-cyan hover:bg-light-cyan/5 cursor-pointer transition-all duration-150">
                                    <input id="permission-{{ $permission->id }}" type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                           {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}
                                           class="h-4 w-4 text-light-cyan focus:ring-light-cyan border-gray-300 rounded">
                                    <span class="ml-3 text-sm text-gray-700 font-medium">
                                        {{ $permission->name }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex items-center justify-end pt-8 mt-8 border-t border-gray-200 space-x-3">
                        <a href="{{ route('users') }}" 
                           class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-light-cyan transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-light-cyan border border-transparent rounded-lg shadow-sm hover:bg-dark-teal focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-light-cyan transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Crear Usuario
                        </button>
                    </div>
                </div>
            </form>
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
