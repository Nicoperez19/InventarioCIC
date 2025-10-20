<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section>
    <header class="mb-8">
        <div class="flex items-center space-x-3 mb-4">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </div>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-900">
                    {{ __('Eliminar Cuenta') }}
                </h2>
                <p class="text-sm text-gray-600">
                    {{ __('Una vez que elimines tu cuenta, todos sus recursos y datos serán eliminados permanentemente.') }}
                </p>
            </div>
        </div>
    </header>

    <!-- Advertencia importante -->
    <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-red-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
            <div>
                <h3 class="text-sm font-medium text-red-900 mb-2">Advertencia: Acción irreversible</h3>
                <div class="text-sm text-red-800 space-y-2">
                    <p>Antes de eliminar tu cuenta, ten en cuenta que:</p>
                    <ul class="list-disc list-inside space-y-1 ml-4">
                        <li>Todos tus datos personales serán eliminados permanentemente</li>
                        <li>No podrás recuperar tu cuenta ni sus datos</li>
                        <li>Se perderá el acceso a todas las funcionalidades del sistema</li>
                        <li>Esta acción no se puede deshacer</li>
                    </ul>
                    <p class="font-medium mt-3">Te recomendamos descargar cualquier información importante antes de proceder.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
        <div class="text-sm text-gray-600">
            {{ __('¿Estás seguro de que quieres eliminar tu cuenta?') }}
        </div>
        
        <button
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200"
        >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
            {{ __('Eliminar Cuenta') }}
        </button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="deleteUser" class="p-6">
            <div class="flex items-center space-x-3 mb-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">
                        {{ __('Confirmar eliminación de cuenta') }}
                    </h2>
                    <p class="text-sm text-gray-600">
                        {{ __('Esta acción no se puede deshacer.') }}
                    </p>
                </div>
            </div>

            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-red-800">
                    {{ __('Una vez que elimines tu cuenta, todos sus recursos y datos serán eliminados permanentemente. Por favor, ingresa tu contraseña para confirmar que deseas eliminar permanentemente tu cuenta.') }}
                </p>
            </div>

            <div class="space-y-4">
                <div>
                    <x-input-label for="password" :value="__('Contraseña actual')" class="text-sm font-medium text-gray-700 mb-2" />
                    <x-text-input
                        wire:model="password"
                        id="password"
                        name="password"
                        type="password"
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500"
                        placeholder="{{ __('Ingresa tu contraseña para confirmar') }}"
                        required
                    />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-3">
                <button type="button" x-on:click="$dispatch('close')" 
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                    {{ __('Cancelar') }}
                </button>

                <button type="submit" 
                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                    {{ __('Eliminar Cuenta Permanentemente') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
