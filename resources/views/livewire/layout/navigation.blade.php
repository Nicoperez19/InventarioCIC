<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="bg-dark-teal shadow-lg border-b border-dark-teal/20 relative z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-1 sm:px-2 lg:px-3">
        <div class="flex justify-between h-12">
            <div class="flex">
                <!-- Botón para abrir/cerrar sidebar -->
                <div class="flex items-center mr-2">
                    <button @click="$dispatch('toggle-sidebar')" 
                            class="inline-flex items-center justify-center p-1 rounded-lg text-light-gray hover:text-white hover:bg-dark-teal/20 focus:outline-none focus:bg-dark-teal/20 focus:text-white transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

                <!-- Logo/Título -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate>
                        <span class="text-xl font-bold text-light-gray">GestionCIC</span>
                    </a>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-2">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-1.5 py-1 border border-transparent text-sm leading-4 font-medium rounded-lg text-light-gray bg-dark-teal/20 hover:bg-dark-teal/30 hover:text-white focus:outline-none focus:ring-2 focus:ring-light-cyan transition ease-in-out duration-150">
                            <div class="w-6 h-6 bg-light-cyan rounded-full flex items-center justify-center mr-1.5">
                                <span class="text-sm font-medium text-white">
                                    {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                                </span>
                            </div>
                            <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            {{ __('Perfil') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                {{ __('Cerrar Sesión') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-1 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-1 rounded-lg text-light-gray hover:text-white hover:bg-dark-teal/20 focus:outline-none focus:bg-dark-teal/20 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-dark-teal/95 backdrop-blur-sm">
        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-light-gray/20">
            <div class="px-4">
                <div class="font-medium text-base text-light-gray" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    {{ __('Perfil') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>
                        {{ __('Cerrar Sesión') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>
