<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<section>
    <header class="mb-8">
        <div class="flex items-center space-x-3 mb-4">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-900">
                    {{ __('Cambiar Contraseña') }}
                </h2>
                <p class="text-sm text-gray-600">
                    {{ __('Actualiza tu contraseña para mantener la seguridad de tu cuenta.') }}
                </p>
            </div>
        </div>
    </header>

    <form wire:submit="updatePassword" class="space-y-6">
        <div class="space-y-4">
            <div>
                <x-input-label for="update_password_current_password" :value="__('Contraseña actual')" class="text-sm font-medium text-gray-700 mb-2" />
                <x-text-input wire:model="current_password" id="update_password_current_password" name="current_password" type="password" 
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-dark-teal focus:border-dark-teal" 
                    autocomplete="current-password" 
                    placeholder="Ingresa tu contraseña actual" />
                <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="update_password_password" :value="__('Nueva contraseña')" class="text-sm font-medium text-gray-700 mb-2" />
                    <x-text-input wire:model="password" id="update_password_password" name="password" type="password" 
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-dark-teal focus:border-dark-teal" 
                        autocomplete="new-password" 
                        placeholder="Ingresa tu nueva contraseña" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="update_password_password_confirmation" :value="__('Confirmar contraseña')" class="text-sm font-medium text-gray-700 mb-2" />
                    <x-text-input wire:model="password_confirmation" id="update_password_password_confirmation" name="password_confirmation" type="password" 
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-dark-teal focus:border-dark-teal" 
                        autocomplete="new-password" 
                        placeholder="Confirma tu nueva contraseña" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end pt-6 border-t border-gray-200 space-x-4">
            <x-action-message class="text-sm text-green-600 font-medium" on="password-updated">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ __('Contraseña actualizada correctamente.') }}
                </div>
            </x-action-message>
            
            <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-dark-teal rounded-lg hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-dark-teal transition-colors duration-150 shadow-sm">
                {{ __('Actualizar Contraseña') }}
            </button>
        </div>
    </form>
</section>
