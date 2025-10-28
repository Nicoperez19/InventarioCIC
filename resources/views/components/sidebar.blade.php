@props(['active' => false])

<div x-data="{ open: false }" 
     x-init="
        $watch('open', value => {
            if (value) {
                document.body.classList.add('sidebar-open');
            } else {
                document.body.classList.remove('sidebar-open');
            }
        });
     "
     @toggle-sidebar.window="open = !open"
     class="fixed inset-y-0 left-0 z-40 w-64 bg-logo-white shadow-lg transform transition-transform duration-300 ease-in-out" 
     :class="{ '-translate-x-full': !open, 'translate-x-0': open }">
    
    <!-- Sidebar Header -->
    <div class="flex items-center justify-between h-16 px-6 border-b border-logo-light-gray">
        <h2 class="text-lg font-semibold text-logo-navy">Menú</h2>
        <button @click="open = false" class="text-logo-navy hover:text-logo-steel-blue">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Sidebar Navigation -->
    <nav class="mt-6 px-3">
        <div class="space-y-1">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" 
               wire:navigate
               class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-logo-green/20 text-logo-navy border-r-2 border-logo-green' : 'text-logo-navy hover:bg-logo-light-gray hover:text-logo-steel-blue' }}">
                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"/>
                </svg>
                Dashboard
            </a>

            <!-- Inventario -->
            <a href="#" 
               class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 text-logo-navy hover:bg-logo-light-gray hover:text-logo-steel-blue">
                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                Inventario
            </a>

            <!-- Solicitudes -->
            <a href="#" 
               class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 text-logo-navy hover:bg-logo-light-gray hover:text-logo-steel-blue">
                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Solicitudes
            </a>

            <!-- Separador -->
            <div class="border-t border-logo-light-gray my-4"></div>

            <!-- Gestión de Usuarios -->
            <a href="#" 
               class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 text-logo-navy hover:bg-logo-light-gray hover:text-logo-steel-blue">
                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                </svg>
                Usuarios
            </a>

            <!-- Productos -->
            <a href="#" 
               class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 text-logo-navy hover:bg-logo-light-gray hover:text-logo-steel-blue">
                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                Productos
            </a>

            <!-- Departamentos -->
            <a href="#" 
               class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 text-logo-navy hover:bg-logo-light-gray hover:text-logo-steel-blue">
                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                Departamentos
            </a>

            <!-- Unidades -->
            <a href="#" 
               class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 text-logo-navy hover:bg-logo-light-gray hover:text-logo-steel-blue">
                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Unidades
            </a>
        </div>
    </nav>

    <!-- Sidebar Footer -->
    <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-neutral-300">
        <div class="text-xs text-logo-navy/60 text-center">
            GestionCIC v1.0
        </div>
    </div>
</div>

<!-- Overlay para móviles -->
<div x-data="{ open: false }" 
     x-show="open" 
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden"
     style="display: none;">
</div>
