<?php

use Livewire\Volt\Component;

new class extends Component { }; ?>

<nav x-data="{ open: false }" class="border-b border-primary-200/40 bg-primary-500 backdrop-blur-md shadow-lg">
    <div class="px-4 mx-auto max-w-8xl sm:px-6 lg:px-4">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center">
                <x-button type="button" variant="navbar" icon-only sr-text="Toggle sidebar"
                    x-on:click="isSidebarOpen = !isSidebarOpen" >
                    <x-icons.menu-fold-right x-show="!isSidebarOpen" aria-hidden="true" class="w-6 h-6 md:block" />
                    <x-icons.menu-fold-left x-show="isSidebarOpen" aria-hidden="true" class="w-6 h-6 md:block" />
                </x-button>
                
                <!-- Logo y nombre cuando el sidebar está cerrado -->
                <div x-show="!isSidebarOpen" x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95"
                     class="flex items-center ml-4">
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-2 mr-3">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo InventarioCIC" class="h-8 w-auto">
                    </div>
                    <h1 class="text-xl font-semibold text-white">InventarioCIC</h1>
                </div>
            </div>

            <div class="hidden md:flex md:items-center md:space-x-4">
                <!-- Notificaciones -->
                @can('notificaciones')
                <livewire:layout.notification-bell />
                @endcan
                
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-4 py-2 text-sm font-medium leading-4 text-white transition-all duration-300 ease-in-out border border-white/20 rounded-xl bg-white/10 hover:bg-secondary-100/20 hover:text-white focus:outline-none focus:ring-2 focus:ring-secondary-500/50 backdrop-blur-sm shadow-md hover:shadow-lg hover:scale-105">
                            <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"
                                x-on:profile-updated.window="name = $event.detail.name"></div>

                            <div class="ms-1">
                                <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile')">
                            {{ __('Perfil') }}
                        </x-dropdown-link>


                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-start">
                                <x-dropdown-link>
                                    {{ __('Cerrar Sesión') }}
                                </x-dropdown-link>
                            </button>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>

    <div :class="{ 'block': open, 'hidden': !open }" class="hidden md:hidden">
        <!-- Navegación responsive oculta - Todo está en el sidebar -->
    </div>
</nav>