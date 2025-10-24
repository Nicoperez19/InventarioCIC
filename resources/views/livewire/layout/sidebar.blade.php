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

                <a href="{{ route('productos') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all duration-300 {{ request()->routeIs('productos') ? 'bg-light-cyan text-white' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                    <x-icons.package class="flex-shrink-0 w-5 h-5" />
                    <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                          :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                        Productos
                    </span>
                </a>

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
