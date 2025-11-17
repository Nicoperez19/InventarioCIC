<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $name = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('correo')) {
            $user->correo_verificado_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
    <header class="mb-8">
        <div class="flex items-center space-x-3 mb-4">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-900">
                    {{ __('Información del Perfil') }}
                </h2>
                <p class="text-sm text-gray-600">
                    {{ __('Actualiza la información de tu cuenta y dirección de correo electrónico.') }}
                </p>
            </div>
        </div>
    </header>

    <form wire:submit="updateProfileInformation" class="space-y-6">
        <div class="space-y-6">
            <div>
                <x-input-label for="name" :value="__('Nombre completo')" class="text-sm font-medium text-gray-700 mb-2" />
                <x-text-input wire:model="name" id="name" name="name" type="text" 
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-secondary-400 focus:border-secondary-400" 
                    required autofocus autocomplete="name" 
                    placeholder="Ingresa tu nombre completo" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Correo electrónico')" class="text-sm font-medium text-gray-700 mb-2" />
                <x-text-input wire:model="email" id="email" name="email" type="email" 
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-secondary-400 focus:border-secondary-400" 
                    required autocomplete="username" 
                    placeholder="tu@ejemplo.com" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                    <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <div>
                                <p class="text-sm text-yellow-800">
                                    {{ __('Tu dirección de correo electrónico no está verificada.') }}
                                </p>
                                <button wire:click.prevent="sendVerification" 
                                    class="mt-1 text-sm text-yellow-700 hover:text-yellow-900 underline focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 rounded">
                                    {{ __('Haz clic aquí para reenviar el correo de verificación.') }}
                                </button>
                            </div>
                        </div>

                        @if (session('status') === 'verification-link-sent')
                            <div class="mt-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <p class="text-sm font-medium text-green-800">
                                        {{ __('Se ha enviado un nuevo enlace de verificación a tu dirección de correo electrónico.') }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="flex items-center justify-end pt-6 border-t border-gray-200 space-x-4">
            <x-action-message class="text-sm text-green-600 font-medium" on="profile-updated">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ __('Información guardada correctamente.') }}
                </div>
            </x-action-message>
            
            <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-secondary-500 rounded-lg hover:bg-secondary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-400 transition-all duration-150 shadow-sm">
                {{ __('Guardar Cambios') }}
            </button>
        </div>
    </form>
</section>
