<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login()
    {
        $success = $this->form->authenticate();

        if ($success) {
            Session::regenerate();

            // Server-side redirect to intended URL (forces full navigation and preserves session)
            return redirect()->intended(route('dashboard', [], false));
        }
    }
}; ?>

<div>
    <div class="mb-3 sm:mb-4 text-center">
        <h2 class="mb-1 text-lg sm:text-xl font-bold text-primary-800">Iniciar Sesión</h2>
        <p class="text-xs sm:text-sm text-neutral-600">Ingresa tus credenciales para acceder al sistema</p>
    </div>

    <x-auth-session-status class="mb-3 sm:mb-4" :status="session('status')" />

    <form wire:submit="login" class="space-y-3 sm:space-y-4">
        <div>
            <x-input-label for="run" :value="__('RUN')" class="font-semibold text-primary-700 text-xs sm:text-sm" />
            <div class="relative mt-1">
                <div class="absolute inset-y-0 left-0 flex items-center pl-2.5 sm:pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <x-text-input wire:model="form.run" id="run" 
                    class="block w-full py-2 pl-8 sm:pl-9 pr-3 text-sm transition-all duration-200 border shadow-sm border-neutral-300 rounded-lg placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-secondary-500 focus:border-secondary-500 hover:border-primary-300" 
                    type="text" name="run" autofocus autocomplete="username" 
                    placeholder="12.345.678-9" oninput="formatRun(this)" />
            </div>
            <x-input-error :messages="$errors->get('form.run')" class="mt-1 text-xs" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Contraseña')" class="font-semibold text-primary-700 text-xs sm:text-sm" />
            <div class="relative mt-1">
                <div class="absolute inset-y-0 left-0 flex items-center pl-2.5 sm:pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <x-text-input wire:model="form.password" id="password" 
                    class="block w-full py-2 pl-8 sm:pl-9 pr-3 text-sm transition-all duration-200 border shadow-sm border-neutral-300 rounded-lg placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-secondary-500 focus:border-secondary-500 hover:border-primary-300"
                    type="password" name="password" autocomplete="current-password" 
                    placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('form.password')" class="mt-1 text-xs" />
        </div>

        <div class="flex items-center justify-between flex-wrap gap-1.5">
            <label for="remember" class="flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox" 
                    class="w-3.5 h-3.5 rounded text-secondary-500 focus:ring-secondary-500 border-neutral-300">
                <span class="ml-1.5 text-xs text-neutral-600">{{ __('Recordarme') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-xs font-medium transition-colors text-secondary-500 hover:text-primary-600" 
                   href="{{ route('password.request') }}">
                    {{ __('¿Olvidaste tu contraseña?') }}
                </a>
            @endif
        </div>

        <div>
            <button type="submit" 
                class="w-full flex justify-center items-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-secondary-500 to-secondary-600 hover:from-secondary-600 hover:to-secondary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500 transition-all duration-200 transform hover:scale-[1.01] hover:shadow-md">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
                {{ __('Iniciar Sesión') }}
            </button>
        </div>
    </form>

</div>

<script>
// Bandera para prevenir recursión infinita
// Usar window para evitar redeclaración cuando Livewire navega
if (typeof window.loginFormatting === 'undefined') {
    window.loginFormatting = false;
}

function formatRun(input) {
    // Si ya estamos formateando, salir para evitar recursión
    if (window.loginFormatting) {
        return;
    }
    
    // Usar requestAnimationFrame para asegurar que el valor se capture después de que el navegador actualice el DOM
    requestAnimationFrame(() => {
        // Si ya estamos formateando, salir
        if (window.loginFormatting) {
            return;
        }
        
        // Marcar que estamos formateando
        window.loginFormatting = true;
        
        try {
            // Guardar la posición del cursor
            const cursorPosition = input.selectionStart;
            const oldValue = input.value;
            
            // Limpiar y formatear el valor - capturar el valor actual del input
            let value = input.value.replace(/[^0-9kK]/g, '').toUpperCase();
            
            // Formatear según la longitud
            let formattedValue;
            if (value.length <= 7) {
                formattedValue = value;
            } else if (value.length === 8) {
                // 8 dígitos: primeros 7 + guion + último dígito
                formattedValue = value.substring(0, 7) + '-' + value.substring(7, 8);
            } else if (value.length === 9) {
                // 9 dígitos: primeros 8 + guion + último dígito
                formattedValue = value.substring(0, 8) + '-' + value.substring(8, 9);
            } else {
                // Más de 9 dígitos: tomar solo los primeros 8 + guion + 9no dígito
                formattedValue = value.substring(0, 8) + '-' + value.substring(8, 9);
            }
            
            // Calcular nueva posición del cursor basándose en los dígitos
            let newCursorPosition = cursorPosition;
            
            // Contar dígitos antes del cursor en el valor anterior
            const digitsBeforeCursor = oldValue.substring(0, cursorPosition).replace(/[^0-9kK]/g, '').length;
            
            // Si se agregó un guion (no había guion antes pero ahora sí)
            if (!oldValue.includes('-') && formattedValue.includes('-')) {
                // Se agregó el guion: colocar cursor al final para que el usuario vea el último dígito
                newCursorPosition = formattedValue.length;
            } else if (oldValue.includes('-') && formattedValue.includes('-')) {
                // Ya tenía guion: calcular posición basándose en dígitos antes del cursor
                const newDashIndex = formattedValue.indexOf('-');
                
                if (digitsBeforeCursor <= 7) {
                    // Cursor estaba antes del guion: mantener posición relativa a los dígitos
                    newCursorPosition = Math.min(digitsBeforeCursor, newDashIndex);
                } else {
                    // Cursor estaba después del guion: ajustar posición
                    newCursorPosition = newDashIndex + 1 + Math.min(digitsBeforeCursor - 7, formattedValue.length - newDashIndex - 1);
                }
            } else if (oldValue.includes('-') && !formattedValue.includes('-')) {
                // Se eliminó el guion: mantener posición relativa a los dígitos
                newCursorPosition = Math.min(digitsBeforeCursor, formattedValue.length);
            } else {
                // Sin guion en ambos: mantener posición relativa a los dígitos
                newCursorPosition = Math.min(digitsBeforeCursor, formattedValue.length);
            }
            
            // Asegurar que la posición del cursor sea válida
            newCursorPosition = Math.max(0, Math.min(newCursorPosition, formattedValue.length));
            
            // Actualizar el valor
            input.value = formattedValue;
            
            // Ajustar la posición del cursor
            setTimeout(() => {
                input.setSelectionRange(newCursorPosition, newCursorPosition);
            }, 0);
            
            // Notificar a Livewire del cambio
            // Disparar evento 'input' para que Livewire detecte el cambio automáticamente
            setTimeout(() => {
                // Disparar evento 'input' para que Livewire lo detecte
                input.dispatchEvent(new Event('input', { bubbles: true }));
                
                // También disparar evento 'change' como respaldo
                input.dispatchEvent(new Event('change', { bubbles: true }));
            }, 0);
            
        } finally {
            // Siempre liberar la bandera después de un breve delay para asegurar que termine el formateo
            setTimeout(() => {
                window.loginFormatting = false;
            }, 10);
        }
    });
}
</script>
