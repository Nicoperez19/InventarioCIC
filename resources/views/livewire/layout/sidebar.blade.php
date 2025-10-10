<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div>
    <!-- Sidebar con transición fluida usando Alpine.js -->
    <aside class="fixed inset-y-0 left-0 z-50 bg-white shadow-lg border-r border-gray-200 transition-all duration-300 ease-in-out"
           :class="{ 'w-64': isSidebarOpen, 'w-0': !isSidebarOpen }"
           x-show="isSidebarOpen"
           x-transition:enter="transition-all duration-300 ease-in-out"
           x-transition:enter-start="w-0 opacity-0"
           x-transition:enter-end="w-64 opacity-100"
           x-transition:leave="transition-all duration-300 ease-in-out"
           x-transition:leave-start="w-64 opacity-100"
           x-transition:leave-end="w-0 opacity-0">
        
        <!-- Sidebar Header -->
        <div class="flex items-center justify-center h-16 bg-dark-teal">
            <div class="flex items-center relative">
                <!-- Logo expandido -->
                <h1 class="text-xl font-bold text-white transition-all duration-300 ease-in-out overflow-hidden"
                    :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                    GestionCIC
                </h1>
                <!-- Logo colapsado -->
                <span class="text-white font-bold text-lg transition-all duration-300 ease-in-out absolute"
                      :class="{ 'w-0 opacity-0': isSidebarOpen, 'w-auto opacity-100': !isSidebarOpen }">
                    G
                </span>
            </div>
        </div>

        <!-- Sidebar Navigation -->
        <nav class="mt-6 px-3">
            <div class="space-y-1">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all duration-300 {{ request()->routeIs('dashboard') ? 'bg-light-cyan text-white' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                    <x-icons.dashboard class="h-5 w-5 flex-shrink-0" />
                    <span class="ml-3 transition-all duration-300 ease-in-out overflow-hidden"
                          :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                        Dashboard
                    </span>
                </a>

                <!-- Usuarios -->
                <a href="{{ route('users') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all duration-300 {{ request()->routeIs('users') ? 'bg-light-cyan text-white' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                    <x-icons.users class="h-5 w-5 flex-shrink-0" />
                    <span class="ml-3 transition-all duration-300 ease-in-out overflow-hidden"
                          :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                        Usuarios
                    </span>
                </a>

                <!-- Departamentos -->
                <a href="{{ route('departamentos') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all duration-300 {{ request()->routeIs('departamentos') ? 'bg-light-cyan text-white' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                    <x-icons.building class="h-5 w-5 flex-shrink-0" />
                    <span class="ml-3 transition-all duration-300 ease-in-out overflow-hidden"
                          :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                        Departamentos
                    </span>
                </a>

                <!-- Unidades -->
                <a href="{{ route('unidades') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all duration-300 {{ request()->routeIs('unidades') ? 'bg-light-cyan text-white' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                    <x-icons.cube class="h-5 w-5 flex-shrink-0" />
                    <span class="ml-3 transition-all duration-300 ease-in-out overflow-hidden"
                          :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                        Unidades
                    </span>
                </a>

                <!-- Productos -->
                <a href="{{ route('productos') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all duration-300 {{ request()->routeIs('productos') ? 'bg-light-cyan text-white' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                    <x-icons.package class="h-5 w-5 flex-shrink-0" />
                    <span class="ml-3 transition-all duration-300 ease-in-out overflow-hidden"
                          :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                        Productos
                    </span>
                </a>
            </div>

            <!-- Divider -->
            <div class="mt-6 border-t border-gray-200"></div>

            <!-- Profile Section -->
            <div class="mt-6">
                <a href="{{ route('profile') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-all duration-300 {{ request()->routeIs('profile') ? 'bg-light-cyan text-white' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                    <x-icons.user class="h-5 w-5 flex-shrink-0" />
                    <span class="ml-3 transition-all duration-300 ease-in-out overflow-hidden"
                          :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                        Perfil
                    </span>
                </a>
            </div>
        </nav>
    </aside>
</div>
