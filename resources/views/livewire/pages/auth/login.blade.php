<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();
        $this->form->authenticate();
        Session::regenerate();
        redirect()->intended(route('dashboard'));
    }
}; ?>

<div>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-dark-black mb-2">Iniciar Sesión</h2>
        <p class="text-neutral-500">Ingresa tus credenciales para acceder al sistema</p>
    </div>

    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form wire:submit="login" class="space-y-6">
        <div>
            <x-input-label for="run" :value="__('RUN')" class="text-dark-black font-medium" />
            <div class="mt-2 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <x-text-input wire:model="form.run" id="run" 
                    class="block w-full pl-10 pr-3 py-3 border border-neutral-300 rounded-xl shadow-sm placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-light-cyan focus:border-light-cyan transition-colors" 
                    type="text" name="run" required autofocus autocomplete="username" 
                    placeholder="12.345.678-9" 
                    oninput="formatRun(this)" />
            </div>
            <x-input-error :messages="$errors->get('form.run')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Contraseña')" class="text-dark-black font-medium" />
            <div class="mt-2 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <x-text-input wire:model="form.password" id="password" 
                    class="block w-full pl-10 pr-3 py-3 border border-neutral-300 rounded-xl shadow-sm placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-light-cyan focus:border-light-cyan transition-colors"
                    type="password" name="password" required autocomplete="current-password" 
                    placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between">
            <label for="remember" class="flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox" 
                    class="h-4 w-4 text-light-cyan focus:ring-light-cyan border-neutral-300 rounded">
                <span class="ml-2 text-sm text-neutral-600">{{ __('Recordarme') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-light-cyan hover:text-dark-teal font-medium transition-colors" 
                   href="{{ route('password.request') }}" wire:navigate>
                    {{ __('¿Olvidaste tu contraseña?') }}
                </a>
            @endif
        </div>

        <div>
            <button type="submit" 
                class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-dark-teal hover:bg-navy-blue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-light-cyan transition-all duration-200 transform hover:scale-[1.02]">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
                {{ __('Iniciar Sesión') }}
            </button>
        </div>
    </form>

</div>

<script>
function formatRun(input) {
    let value = input.value.replace(/[^0-9kK]/g, '').toUpperCase();
    
    if (value.length <= 7) {
        input.value = value;
    } else if (value.length === 8) {
        input.value = value.substring(0, 7) + '-' + value.substring(7, 8);
    } else if (value.length === 9) {
        input.value = value.substring(0, 8) + '-' + value.substring(8, 9);
    } else {
        input.value = value.substring(0, 8) + '-' + value.substring(8, 9);
    }
    
    input.dispatchEvent(new Event('input', { bubbles: true }));
}
</script>
