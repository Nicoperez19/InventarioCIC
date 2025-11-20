@php
    use Illuminate\Support\Facades\Auth;

    $user = Auth::user();
    $tiposInsumo = \App\Models\TipoInsumo::orderBy('nombre_tipo')->get();

    // Función simple para verificar permisos
    $can = function ($permission) use ($user) {
        if (!$user)
            return false;

        // Si tiene permisos directos, solo esos cuentan
        $directPermissions = $user->getDirectPermissions()->pluck('name')->toArray();
        if (!empty($directPermissions)) {
            return in_array($permission, $directPermissions);
        }

        // Si no tiene permisos directos, usar los de roles
        return $user->can($permission);
    };
@endphp

<div wire:key="sidebar-component">
    <aside
        class="fixed inset-y-0 left-0 z-50 transition-all duration-300 ease-in-out shadow-xl bg-primary-100 backdrop-blur-sm md:flex md:flex-col"
        :class="{ 'w-64': isSidebarOpen, 'w-0': !isSidebarOpen }" x-show="isSidebarOpen"
        x-transition:enter="transition-all duration-300 ease-in-out" x-transition:enter-start="w-0 opacity-0"
        x-transition:enter-end="w-64 opacity-100" x-transition:leave="transition-all duration-300 ease-in-out"
        x-transition:leave-start="w-64 opacity-100" x-transition:leave-end="w-0 opacity-0">
        <div class="relative flex items-center h-16 px-3 overflow-hidden shadow-lg bg-primary-500">
            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/8 to-transparent"></div>
            <div class="relative z-10 flex items-center">
                <div class="p-2 mr-3 bg-white/20 backdrop-blur-sm rounded-xl">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo GestionCIC" class="w-auto h-10">
                </div>
                <h1 class="overflow-hidden text-xl font-semibold text-white transition-all duration-300 ease-in-out drop-shadow-sm"
                    :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                    GestionCIC
                </h1>
            </div>
        </div>

        <nav class="flex-1 px-3 mt-6 overflow-y-auto">
            <div class="space-y-4">
                <!-- General -->
                @if($can('dashboard') || $can('reportes'))
                    <div>
                        <div class="px-3 mb-2">
                            <span
                                class="overflow-hidden text-xs font-semibold tracking-wider uppercase transition-all duration-300 ease-in-out text-primary-600"
                                :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                                General
                            </span>
                        </div>
                        <div class="space-y-2">
                            @if($can('dashboard'))
                                <button type="button" data-nav-url="{{ route('dashboard') }}"
                                    class="sidebar-nav-btn w-full flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-300 text-left {{ request()->routeIs('dashboard') ? 'bg-secondary-500 text-white shadow-lg' : 'text-primary-800 hover:bg-white/60 hover:text-primary-900' }}">
                                    <x-icons.dashboard class="flex-shrink-0 w-5 h-5" />
                                    <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                                        :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                                        Dashboard
                                    </span>
                                </button>
                            @endif

                            @if($can('reportes'))
                                <div x-data="{ open: {{ request()->routeIs('reportes.*') ? 'true' : 'false' }} }">
                                    <button type="button" @click="open = !open"
                                        class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-300 text-left {{ request()->routeIs('reportes.*') ? 'bg-secondary-100 text-primary-800 border border-secondary-300' : 'text-primary-800 hover:bg-white/60 hover:text-primary-900' }}">
                                        <div class="flex items-center">
                                            <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                                                :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                                                Reportes
                                            </span>
                                        </div>
                                        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                    <div x-show="open" x-transition class="mt-2 ml-6 space-y-1">
                                        @if($can('reportes insumos'))
                                            <button type="button" data-nav-url="{{ route('reportes.insumos.index') }}"
                                                class="sidebar-nav-btn w-full flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300 text-left {{ request()->routeIs('reportes.insumos.*') ? 'bg-secondary-500 text-white' : 'text-primary-700 hover:bg-white/60 hover:text-primary-900' }}">
                                                <svg class="flex-shrink-0 w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                                    </path>
                                                </svg>
                                                <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                                                    :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                                                    Insumos
                                                </span>
                                            </button>
                                        @endif
                                        @if($can('reportes stock'))
                                            <button type="button" data-nav-url="{{ route('reportes.stock.index') }}"
                                                class="sidebar-nav-btn w-full flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300 text-left {{ request()->routeIs('reportes.stock.*') ? 'bg-secondary-500 text-white' : 'text-primary-700 hover:bg-white/60 hover:text-primary-900' }}">
                                                <svg class="flex-shrink-0 w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                                    </path>
                                                </svg>
                                                <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                                                    :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                                                    Stock Crítico
                                                </span>
                                            </button>
                                        @endif
                                        @if($can('reportes consumo departamento'))
                                            <button type="button" data-nav-url="{{ route('reportes.consumo-departamento.index') }}"
                                                class="sidebar-nav-btn w-full flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300 text-left {{ request()->routeIs('reportes.consumo-departamento.*') ? 'bg-secondary-500 text-white' : 'text-primary-700 hover:bg-white/60 hover:text-primary-900' }}">
                                                <svg class="flex-shrink-0 w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                                    </path>
                                                </svg>
                                                <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                                                    :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                                                    Consumo por Depto.
                                                </span>
                                            </button>
                                        @endif
                                        @if($can('reportes rotacion'))
                                            <button type="button" data-nav-url="{{ route('reportes.rotacion.index') }}"
                                                class="sidebar-nav-btn w-full flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300 text-left {{ request()->routeIs('reportes.rotacion.*') ? 'bg-secondary-500 text-white' : 'text-primary-700 hover:bg-white/60 hover:text-primary-900' }}">
                                                <svg class="flex-shrink-0 w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                                    </path>
                                                </svg>
                                                <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                                                    :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                                                    Rotación de Inventario
                                                </span>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Operación -->
                @if($can('solicitudes') || $can('carga masiva'))
                    <div>
                        <div class="px-3 mb-2">
                            <span
                                class="overflow-hidden text-xs font-semibold tracking-wider uppercase transition-all duration-300 ease-in-out text-primary-600"
                                :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                                Operación
                            </span>
                        </div>
                        <div class="space-y-2">
                            @if($can('solicitudes'))
                                <button type="button" data-nav-url="{{ route('solicitudes') }}"
                                    class="sidebar-nav-btn w-full flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-300 text-left {{ request()->routeIs('solicitudes') ? 'bg-secondary-500 text-white shadow-lg' : 'text-primary-800 hover:bg-white/60 hover:text-primary-900' }}">
                                    <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                                        :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                                        Solicitudes
                                    </span>
                                </button>
                            @endif

                            @if($can('carga masiva'))
                                <button type="button" data-nav-url="{{ route('carga-masiva.index') }}"
                                    class="sidebar-nav-btn w-full flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-300 text-left {{ request()->routeIs('carga-masiva.index') ? 'bg-secondary-500 text-white shadow-lg' : 'text-primary-800 hover:bg-white/60 hover:text-primary-900' }}">
                                    <x-icons.upload class="flex-shrink-0 w-5 h-5" />
                                    <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                                        :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                                        Carga Masiva
                                    </span>
                                </button>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Inventario -->
                @if($can('insumos') || $can('mantenedor de proveedores') || $can('mantenedor de facturas') || $can('mantenedor de unidades'))
                    <div>
                        <div class="px-3 mb-2">
                            <span
                                class="overflow-hidden text-xs font-semibold tracking-wider uppercase transition-all duration-300 ease-in-out text-primary-600"
                                :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                                Inventario
                            </span>
                        </div>
                        <div class="space-y-2">
                            @if($can('insumos'))
                                <div
                                    x-data="{ open: {{ request()->routeIs('insumos.*') || request()->routeIs('tipo-insumos.*') || request()->get('tipoInsumoFilter') ? 'true' : 'false' }} }">
                                    <button type="button" @click="open = !open"
                                        class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-300 text-left {{ request()->routeIs('insumos.*') || request()->routeIs('tipo-insumos.*') || request()->get('tipoInsumoFilter') ? 'bg-secondary-100 text-primary-800 border border-secondary-300' : 'text-primary-800 hover:bg-white/60 hover:text-primary-900' }}">
                                        <div class="flex items-center">
                                            <x-icons.package class="flex-shrink-0 w-5 h-5" />
                                            <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                                                :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                                                Insumos
                                            </span>
                                        </div>
                                        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                    <div x-show="open" x-transition class="mt-2 ml-6 space-y-1">
                                        <button type="button" data-nav-url="{{ route('insumos.index') }}"
                                            class="sidebar-nav-btn w-full flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300 text-left {{ request()->routeIs('insumos.index') && !request()->get('tipoInsumoFilter') ? 'bg-secondary-500 text-white' : 'text-primary-700 hover:bg-white/60 hover:text-primary-900' }}">
                                            <x-icons.package class="flex-shrink-0 w-4 h-4" />
                                            <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                                                :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                                                Todos
                                            </span>
                                        </button>
                                        @foreach($tiposInsumo as $tipo)
                                            <button type="button"
                                                data-nav-url="{{ route('insumos.index', ['tipoInsumoFilter' => $tipo->id]) }}"
                                                class="sidebar-nav-btn w-full flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300 text-left {{ request()->routeIs('insumos.index') && (request()->get('tipoInsumoFilter') == $tipo->id || request()->get('tipoInsumoFilter') == (string) $tipo->id) ? 'bg-secondary-500 text-white' : 'text-primary-700 hover:bg-white/60 hover:text-primary-900' }}">
                                                <svg class="flex-shrink-0 w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                                    </path>
                                                </svg>
                                                <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                                                    :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                                                    {{ $tipo->nombre_tipo }}
                                                </span>
                                            </button>
                                        @endforeach
                                        @if($can('mantenedor de tipos de insumo'))
                                            <button type="button" data-nav-url="{{ route('tipo-insumos.index') }}"
                                                class="sidebar-nav-btn w-full flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300 text-left {{ request()->routeIs('tipo-insumos.*') ? 'bg-secondary-500 text-white' : 'text-primary-700 hover:bg-white/60 hover:text-primary-900' }}">
                                                <svg class="flex-shrink-0 w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                                    </path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                                                    :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                                                    Gestionar Tipos
                                                </span>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @if($can('mantenedor de proveedores'))
                                <button type="button" data-nav-url="{{ route('proveedores.index') }}"
                                    class="sidebar-nav-btn w-full flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-300 text-left {{ request()->routeIs('proveedores.index') ? 'bg-secondary-500 text-white shadow-lg' : 'text-primary-800 hover:bg-white/60 hover:text-primary-900' }}">
                                    <x-icons.truck class="flex-shrink-0 w-5 h-5" />
                                    <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                                        :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                                        Proveedores
                                    </span>
                                </button>
                            @endif

                            @if($can('mantenedor de facturas'))
                                <button type="button" data-nav-url="{{ route('facturas.index') }}"
                                    class="sidebar-nav-btn w-full flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-300 text-left {{ request()->routeIs('facturas.index') ? 'bg-secondary-500 text-white shadow-lg' : 'text-primary-800 hover:bg-white/60 hover:text-primary-900' }}">
                                    <x-icons.document class="flex-shrink-0 w-5 h-5" />
                                    <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                                        :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                                        Facturas
                                    </span>
                                </button>
                            @endif

                            @if($can('mantenedor de unidades'))
                                <button type="button" data-nav-url="{{ route('unidades') }}"
                                    class="sidebar-nav-btn w-full flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-300 text-left {{ request()->routeIs('unidades') ? 'bg-secondary-500 text-white shadow-lg' : 'text-primary-800 hover:bg-white/60 hover:text-primary-900' }}">
                                    <x-icons.cube class="flex-shrink-0 w-5 h-5" />
                                    <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                                        :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                                        Unidades
                                    </span>
                                </button>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Organización -->
                @if(
                        $can('mantenedor de usuarios') ||
                        $can('mantenedor de departamentos')
                    )
                    <div>
                        <div class="px-3 mb-2">
                            <span
                                class="overflow-hidden text-xs font-semibold tracking-wider uppercase transition-all duration-300 ease-in-out text-primary-600"
                                :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                                Organización
                            </span>
                        </div>
                        <div class="space-y-2">
                            <div
                                x-data="{ open: {{ request()->routeIs('users') || request()->routeIs('departamentos') ? 'true' : 'false' }} }">
                                <button type="button" @click="open = !open"
                                    class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-300 text-left {{ request()->routeIs('users') || request()->routeIs('departamentos') ? 'bg-secondary-100 text-primary-800 border border-secondary-300' : 'text-primary-800 hover:bg-white/60 hover:text-primary-900' }}">
                                    <div class="flex items-center">
                                        <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                                            </path>
                                        </svg>
                                        <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                                            :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                                            Organización
                                        </span>
                                    </div>
                                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div x-show="open" x-transition class="mt-2 ml-6 space-y-1">
                                    @if($can('mantenedor de departamentos'))
                                        <button type="button" data-nav-url="{{ route('departamentos') }}"
                                            class="sidebar-nav-btn w-full flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300 text-left {{ request()->routeIs('departamentos') ? 'bg-secondary-500 text-white' : 'text-primary-700 hover:bg-white/60 hover:text-primary-900' }}">
                                            <x-icons.building class="flex-shrink-0 w-4 h-4" />
                                            <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                                                :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                                                Departamentos
                                            </span>
                                        </button>
                                    @endif
                                    @if($can('mantenedor de usuarios'))
                                        <button type="button" data-nav-url="{{ route('users') }}"
                                            class="sidebar-nav-btn w-full flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300 text-left {{ request()->routeIs('users') ? 'bg-secondary-500 text-white' : 'text-primary-700 hover:bg-white/60 hover:text-primary-900' }}">
                                            <x-icons.users class="flex-shrink-0 w-4 h-4" />
                                            <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                                                :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                                                Usuarios
                                            </span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Administración -->
                @if($can('admin solicitudes'))
                    <div>
                        <div class="px-3 mb-2">
                            <span
                                class="overflow-hidden text-xs font-semibold tracking-wider uppercase transition-all duration-300 ease-in-out text-primary-600"
                                :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                                Administración
                            </span>
                        </div>
                        <div class="space-y-2">
                            @if($can('admin solicitudes'))
                                <button type="button" data-nav-url="{{ route('admin-solicitudes') }}"
                                    class="sidebar-nav-btn w-full flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-300 text-left {{ request()->routeIs('admin-solicitudes') ? 'bg-secondary-500 text-white shadow-lg' : 'text-primary-800 hover:bg-white/60 hover:text-primary-900' }}">
                                    <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                                        :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                                        Admin Solicitudes
                                    </span>
                                </button>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </nav>

        <div class="p-4 mt-auto border-t md:hidden border-primary-300/50">
            <div class="flex items-center px-3 py-2 mb-3">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center w-8 h-8 rounded-full bg-secondary-500">
                        <span class="text-sm font-semibold text-white">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </span>
                    </div>
                </div>
                <div class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                    :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                    <div class="text-sm font-medium text-primary-800"
                        x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"
                        x-on:profile-updated.window="name = $event.detail.name"></div>
                </div>
            </div>
            <div class="space-y-1">
                <button type="button" data-nav-url="{{ route('profile') }}"
                    class="flex items-center w-full px-3 py-2 text-sm font-medium text-left transition-all duration-200 rounded-lg sidebar-nav-btn text-primary-700 hover:bg-secondary-100 hover:text-primary-900">
                    <svg class="flex-shrink-0 w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span class="overflow-hidden transition-all duration-300 ease-in-out"
                        :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                        Perfil
                    </span>
                </button>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center w-full px-3 py-2 text-sm font-medium text-left transition-all duration-200 rounded-lg text-primary-700 hover:bg-secondary-100 hover:text-primary-900">
                        <svg class="flex-shrink-0 w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
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

<script>
    (function () {
        function handleNavigation(e) {
            const btn = e.target.closest('.sidebar-nav-btn');
            if (!btn) return false;

            const url = btn.getAttribute('data-nav-url');
            if (!url || url === '') return false;

            e.stopImmediatePropagation();
            e.preventDefault();
            e.stopPropagation();

            window.location.href = url;
            return true;
        }

        document.addEventListener('click', handleNavigation, true);

        document.addEventListener('mousedown', function (e) {
            const btn = e.target.closest('.sidebar-nav-btn');
            if (btn && btn.getAttribute('data-nav-url')) {
                e.stopImmediatePropagation();
                e.stopPropagation();
            }
        }, true);

        document.addEventListener('mouseup', function (e) {
            const btn = e.target.closest('.sidebar-nav-btn');
            if (btn && btn.getAttribute('data-nav-url')) {
                e.stopImmediatePropagation();
                e.stopPropagation();
            }
        }, true);
    })();
</script>