<?php

use Livewire\Volt\Component;

new class extends Component {}; ?>

<div>
    <aside class="fixed inset-y-0 left-0 z-50 transition-all duration-300 ease-in-out bg-white border-r border-gray-200 shadow-lg"
           :class="{ 'w-64': isSidebarOpen, 'w-0': !isSidebarOpen }"
           x-show="isSidebarOpen"
           x-transition:enter="transition-all duration-300 ease-in-out"
           x-transition:enter-start="w-0 opacity-0"
           x-transition:enter-end="w-64 opacity-100"
           x-transition:leave="transition-all duration-300 ease-in-out"
           x-transition:leave-start="w-64 opacity-100"
           x-transition:leave-end="w-0 opacity-0">
        
        <div class="flex items-center justify-center h-16 bg-dark-teal">
            <div class="relative flex items-center">
                {{-- LOGO --}}
                <h1 class="overflow-hidden text-xl font-bold text-white transition-all duration-300 ease-in-out"
                    :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                    GestionCIC
                </h1>
           
            </div>
        </div>

        <nav class="px-3 mt-6">
            <div class="my-6 border-t border-gray-200"></div>
           
            <div class="space-y-1">
                <a href="{{ route('dashboard') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all duration-300 {{ request()->routeIs('dashboard') ? 'bg-light-cyan text-white' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                    <x-icons.dashboard class="flex-shrink-0 w-5 h-5" />
                    <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                          :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                        Dashboard
                    </span>
                </a>

                <a href="{{ route('users') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all duration-300 {{ request()->routeIs('users') ? 'bg-light-cyan text-white' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                    <x-icons.users class="flex-shrink-0 w-5 h-5" />
                    <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                          :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                        Usuarios
                    </span>
                </a>

                <a href="{{ route('departamentos') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all duration-300 {{ request()->routeIs('departamentos') ? 'bg-light-cyan text-white' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                    <x-icons.building class="flex-shrink-0 w-5 h-5" />
                    <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                          :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                        Departamentos
                    </span>
                </a>

                <a href="{{ route('unidades') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all duration-300 {{ request()->routeIs('unidades') ? 'bg-light-cyan text-white' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
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
                            class="group flex items-center justify-between w-full px-3 py-2 text-sm font-medium rounded-md transition-all duration-300 {{ request()->routeIs('productos') || request()->routeIs('tipo-insumos.*') || request()->routeIs('carga-masiva.*') ? 'bg-light-cyan text-white' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                        <div class="flex items-center">
                            <x-icons.package class="flex-shrink-0 w-5 h-5" />
                            <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                                  :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                                Insumos
                            </span>
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-200" 
                             :class="{ 'rotate-180': insumosOpen }" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    
                    <!-- Submenú desplegable -->
                    <div x-show="insumosOpen" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="ml-6 space-y-1">
                        <!-- Todos los Insumos -->
                        <a href="{{ route('productos') }}" 
                           class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all duration-300 {{ request()->routeIs('productos') ? 'bg-light-cyan text-white' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                            <x-icons.package class="flex-shrink-0 w-4 h-4" />
                            <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                                  :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                                Todos los Insumos
                            </span>
                        </a>
                        
                        <!-- Tipos de Insumo -->
                        <a href="{{ route('tipo-insumos.index') }}" 
                           class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all duration-300 {{ request()->routeIs('tipo-insumos.*') ? 'bg-light-cyan text-white' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                            <svg class="flex-shrink-0 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                                  :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                                Tipos de Insumo
                            </span>
                        </a>
                        
                        <!-- Carga Masiva -->
                        <a href="{{ route('carga-masiva.index') }}" 
                           class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all duration-300 {{ request()->routeIs('carga-masiva.*') ? 'bg-light-cyan text-white' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                            <svg class="flex-shrink-0 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                                  :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                                Carga Masiva
                            </span>
                        </a>
                    </div>
                </div>

                <a href="{{ route('inventario.index') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all duration-300 {{ request()->routeIs('inventario.index') ? 'bg-light-cyan text-white' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                    <x-icons.package class="flex-shrink-0 w-5 h-5" />
                    <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                          :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                        Inventario
                    </span>
                </a>

                <a href="{{ route('carga-masiva.index') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all duration-300 {{ request()->routeIs('carga-masiva.index') ? 'bg-light-cyan text-white' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                    <x-icons.upload class="flex-shrink-0 w-5 h-5" />
                    <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                          :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                        Carga Masiva
                    </span>
                </a>

                <a href="{{ route('proveedores.index') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all duration-300 {{ request()->routeIs('proveedores.index') ? 'bg-light-cyan text-white' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                    <x-icons.truck class="flex-shrink-0 w-5 h-5" />
                    <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                          :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                        Proveedores
                    </span>
                </a>

                <a href="{{ route('facturas.index') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all duration-300 {{ request()->routeIs('facturas.index') ? 'bg-light-cyan text-white' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                    <x-icons.document class="flex-shrink-0 w-5 h-5" />
                    <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                          :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                        Facturas
                    </span>
                </a>
            </div>
        </nav>
    </aside>
</div>
