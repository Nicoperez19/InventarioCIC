<?php

use Livewire\Volt\Component;

new class extends Component {}; ?>

<div>
    <aside
        class="fixed inset-y-0 left-0 z-50 transition-all duration-300 ease-in-out bg-primary-100 shadow-xl backdrop-blur-sm md:flex md:flex-col"
        :class="{ 'w-64': isSidebarOpen, 'w-0': !isSidebarOpen }" x-show="isSidebarOpen"
        x-transition:enter="transition-all duration-300 ease-in-out" x-transition:enter-start="w-0 opacity-0"
        x-transition:enter-end="w-64 opacity-100" x-transition:leave="transition-all duration-300 ease-in-out"
        x-transition:leave-start="w-64 opacity-100" x-transition:leave-end="w-0 opacity-0">

        <div class="flex items-center justify-center h-16 bg-primary-500 shadow-lg relative overflow-hidden">
            <!-- Efecto de brillo sutil -->
            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/8 to-transparent"></div>
            <div class="relative flex items-center z-10">
                    <h1 class="overflow-hidden text-xl font-semibold text-white transition-all duration-300 ease-in-out drop-shadow-sm"
                    :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                    GestionCIC
                </h1>
            </div>
        </div>

        <nav class="px-3 mt-6">

            <div class="space-y-2">
                <a href="{{ route('dashboard') }}"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('dashboard') ? 'bg-secondary-500 text-white shadow-lg transform scale-105' : 'text-primary-800 hover:bg-white/60 hover:text-primary-900 hover:shadow-sm hover:scale-105' }}">
                    <x-icons.dashboard class="flex-shrink-0 w-5 h-5" />
                    <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                        :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                        Dashboard
                    </span>
                </a>

                <a href="{{ route('users') }}"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('users') ? 'bg-secondary-500 text-white shadow-lg transform scale-105' : 'text-primary-800 hover:bg-white/60 hover:text-primary-900 hover:shadow-sm hover:scale-105' }}">
                    <x-icons.users class="flex-shrink-0 w-5 h-5" />
                    <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                        :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                        Usuarios
                    </span>
                </a>

                <a href="{{ route('departamentos') }}"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('departamentos') ? 'bg-secondary-500 text-white shadow-lg transform scale-105' : 'text-primary-800 hover:bg-white/60 hover:text-primary-900 hover:shadow-sm hover:scale-105' }}">
                    <x-icons.building class="flex-shrink-0 w-5 h-5" />
                    <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                        :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                        Departamentos
                    </span>
                </a>

                <a href="{{ route('unidades') }}"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('unidades') ? 'bg-secondary-500 text-white shadow-lg transform scale-105' : 'text-primary-800 hover:bg-white/60 hover:text-primary-900 hover:shadow-sm hover:scale-105' }}">
                    <x-icons.cube class="flex-shrink-0 w-5 h-5" />
                    <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                        :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                        Unidades
                    </span>
                </a>


                <!-- Menú desplegable de Insumos -->
                <div x-data="{ insumosOpen: {{ request()->routeIs('productos') || request()->routeIs('tipo-insumos.*') || request()->routeIs('carga-masiva.*') ? 'true' : 'false' }} }">
                    <!-- Botón principal de Insumos -->
                    <button @click="insumosOpen = !insumosOpen"
                        class="group flex items-center justify-between w-full px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('productos') || request()->routeIs('tipo-insumos.*') || request()->routeIs('carga-masiva.*') ? 'bg-secondary-100 text-primary-800 border border-secondary-300' : 'text-primary-800 hover:bg-white/60 hover:text-primary-900 hover:shadow-sm hover:scale-105' }}">
                        <div class="flex items-center">
                            <x-icons.package class="flex-shrink-0 w-5 h-5" />
                            <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                                :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                                Insumos
                            </span>
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': insumosOpen }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>

                    <!-- Submenú desplegable -->
                    <div x-show="insumosOpen" x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95" class="ml-6 mt-2 space-y-1">
                        <!-- Todos los Insumos -->
                        <a href="{{ route('productos') }}"
                            class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300 {{ request()->routeIs('productos') ? 'bg-secondary-500 text-white shadow-md transform scale-105' : 'text-primary-700 hover:bg-white/60 hover:text-primary-900 hover:shadow-sm hover:scale-105' }}">
                            <x-icons.package class="flex-shrink-0 w-4 h-4" />
                            <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                                :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                                Todos los Insumos
                            </span>
                        </a>

                        <!-- Tipos de Insumo -->
                        <a href="{{ route('tipo-insumos.index') }}"
                            class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300 {{ request()->routeIs('tipo-insumos.*') ? 'bg-secondary-500 text-white shadow-md transform scale-105' : 'text-primary-700 hover:bg-white/60 hover:text-primary-900 hover:shadow-sm hover:scale-105' }}">
                            <svg class="flex-shrink-0 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                </path>
                            </svg>
                            <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                                :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                                Tipos de Insumo
                            </span>
                        </a>
                    </div>
                </div>


                <a href="{{ route('carga-masiva.index') }}"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('carga-masiva.index') ? 'bg-secondary-500 text-white shadow-lg transform scale-105' : 'text-primary-800 hover:bg-white/60 hover:text-primary-900 hover:shadow-sm hover:scale-105' }}">
                    <x-icons.upload class="flex-shrink-0 w-5 h-5" />
                    <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                        :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                        Carga Masiva
                    </span>
                </a>

                <a href="{{ route('proveedores.index') }}"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('proveedores.index') ? 'bg-secondary-500 text-white shadow-lg transform scale-105' : 'text-primary-800 hover:bg-white/60 hover:text-primary-900 hover:shadow-sm hover:scale-105' }}">
                    <x-icons.truck class="flex-shrink-0 w-5 h-5" />
                    <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                        :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                        Proveedores
                    </span>
                </a>

                <a href="{{ route('facturas.index') }}"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('facturas.index') ? 'bg-secondary-500 text-white shadow-lg transform scale-105' : 'text-primary-800 hover:bg-white/60 hover:text-primary-900 hover:shadow-sm hover:scale-105' }}">
                    <x-icons.document class="flex-shrink-0 w-5 h-5" />
                    <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                        :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                        Facturas
                    </span>
                </a>
            </div>
        </nav>

        <!-- Sección de Usuario - Solo visible en móvil -->
        <div class="md:hidden mt-auto p-4 border-t border-primary-300/50">
            <div class="flex items-center px-3 py-2 mb-3">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-secondary-500 rounded-full flex items-center justify-center">
                        <span class="text-white text-sm font-semibold">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </span>
                    </div>
                </div>
                <div class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                    :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                    <div class="text-sm font-medium text-primary-800" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"
                        x-on:profile-updated.window="name = $event.detail.name"></div>
                </div>
            </div>
            
            <div class="space-y-1">
                <a href="{{ route('profile') }}" wire:navigate
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 text-primary-700 hover:bg-secondary-100 hover:text-primary-900">
                    <svg class="flex-shrink-0 w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span class="overflow-hidden transition-all duration-300 ease-in-out"
                        :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                        Perfil
                    </span>
                </a>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 text-primary-700 hover:bg-secondary-100 hover:text-primary-900">
                        <svg class="flex-shrink-0 w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span class="overflow-hidden transition-all duration-300 ease-in-out"
                            :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                            Cerrar Sesión
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </aside>
</div>
