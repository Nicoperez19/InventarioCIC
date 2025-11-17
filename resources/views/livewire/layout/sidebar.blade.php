<?php

use Livewire\Volt\Component;
use App\Models\TipoInsumo;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public function with()
    {
        // CRÍTICO: Limpiar caché y forzar recarga de permisos desde BD
        $permissions = [];
        
        if (auth()->check()) {
            // Limpiar TODA la caché de permisos
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
            
            // Limpiar caché del usuario
            Cache::forget("spatie.permission.cache.user." . auth()->user()->run);
            
            // Obtener usuario fresco desde BD (sin caché)
            $freshUser = \App\Models\User::with(['permissions', 'roles'])
                ->where('run', auth()->user()->run)
                ->first();
            
            // Actualizar el usuario en la sesión con datos frescos
            if ($freshUser) {
                Auth::setUser($freshUser);
                
                // Verificar TODOS los permisos en tiempo de ejecución
                $permissions = [
                    'solicitar-insumos' => $freshUser->can('solicitar-insumos'),
                    'administrar-usuarios' => $freshUser->can('administrar-usuarios'),
                    'administrar-departamentos' => $freshUser->can('administrar-departamentos'),
                    'administrar-unidades' => $freshUser->can('administrar-unidades'),
                    'administrar-tipo-insumos' => $freshUser->can('administrar-tipo-insumos'),
                    'administrar-insumos' => $freshUser->can('administrar-insumos'),
                    'administrar-roles' => $freshUser->can('administrar-roles'),
                    'administrar-proveedores' => $freshUser->can('administrar-proveedores'),
                    'administrar-facturas' => $freshUser->can('administrar-facturas'),
                    'administrar-solicitudes' => $freshUser->can('administrar-solicitudes'),
                ];
            }
        }
        
        return [
            'tiposInsumo' => TipoInsumo::orderBy('nombre_tipo')->get(),
            'permissions' => $permissions,
        ];
    }
    
    public function mount()
    {
        // Limpiar caché al montar el componente
        if (auth()->check()) {
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        }
    }
}; ?>

<div>
    <aside
        class="fixed inset-y-0 left-0 z-50 transition-all duration-300 ease-in-out shadow-xl bg-primary-100 backdrop-blur-sm md:flex md:flex-col"
        :class="{ 'w-64': isSidebarOpen, 'w-0': !isSidebarOpen }" x-show="isSidebarOpen"
        x-transition:enter="transition-all duration-300 ease-in-out" x-transition:enter-start="w-0 opacity-0"
        x-transition:enter-end="w-64 opacity-100" x-transition:leave="transition-all duration-300 ease-in-out"
        x-transition:leave-start="w-64 opacity-100" x-transition:leave-end="w-0 opacity-0">

        <div class="relative flex items-center h-16 overflow-hidden shadow-lg bg-primary-500 px-3">
            <!-- Efecto de brillo sutil -->
            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/8 to-transparent"></div>
            <div class="relative z-10 flex items-center">
                <!-- Logo -->
                <div class="bg-white/20 backdrop-blur-sm rounded-xl p-2 mr-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo GestionCIC" class="h-10 w-auto">
                </div>
                
                <!-- Nombre del sistema -->
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

                @if($permissions['solicitar-insumos'] ?? false)
                <a href="{{ route('solicitudes') }}"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('solicitudes') ? 'bg-secondary-500 text-white shadow-lg transform scale-105' : 'text-primary-800 hover:bg-white/60 hover:text-primary-900 hover:shadow-sm hover:scale-105' }}">
                    <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                        :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                        Solicitudes
                    </span>
                </a>
                @endif

                @if($permissions['administrar-usuarios'] ?? false)
                <a href="{{ route('users') }}"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('users') ? 'bg-secondary-500 text-white shadow-lg transform scale-105' : 'text-primary-800 hover:bg-white/60 hover:text-primary-900 hover:shadow-sm hover:scale-105' }}">
                    <x-icons.users class="flex-shrink-0 w-5 h-5" />
                    <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                        :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                        Usuarios
                    </span>
                </a>
                @endif

                @if($permissions['administrar-departamentos'] ?? false)
                <a href="{{ route('departamentos') }}"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('departamentos') ? 'bg-secondary-500 text-white shadow-lg transform scale-105' : 'text-primary-800 hover:bg-white/60 hover:text-primary-900 hover:shadow-sm hover:scale-105' }}">
                    <x-icons.building class="flex-shrink-0 w-5 h-5" />
                    <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                        :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                        Departamentos
                    </span>
                </a>
                @endif

                @if($permissions['administrar-unidades'] ?? false)
                <a href="{{ route('unidades') }}"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('unidades') ? 'bg-secondary-500 text-white shadow-lg transform scale-105' : 'text-primary-800 hover:bg-white/60 hover:text-primary-900 hover:shadow-sm hover:scale-105' }}">
                    <x-icons.cube class="flex-shrink-0 w-5 h-5" />
                    <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                        :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                        Unidades
                    </span>
                </a>
                @endif


                @if($permissions['administrar-insumos'] ?? false)
                <!-- Menú desplegable de Insumos -->
                <div x-data="{ insumosOpen: {{ request()->routeIs('insumos.*') || request()->routeIs('tipo-insumos.*') || request()->get('tipoInsumoFilter') ? 'true' : 'false' }} }">
                    <!-- Botón principal de Insumos -->
                    <button type="button" @click="insumosOpen = !insumosOpen"
                        class="group flex items-center justify-between w-full px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('insumos.*') || request()->routeIs('tipo-insumos.*') || request()->get('tipoInsumoFilter') ? 'bg-secondary-100 text-primary-800 border border-secondary-300' : 'text-primary-800 hover:bg-white/60 hover:text-primary-900 hover:shadow-sm hover:scale-105' }}">
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
                        x-transition:leave-end="transform opacity-0 scale-95" class="mt-2 ml-6 space-y-1">
                        @if($permissions['administrar-insumos'] ?? false)
                        <!-- Todos los Insumos -->
                        <a href="{{ route('insumos.index') }}"
                            class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300 {{ request()->routeIs('insumos.index') && !request()->get('tipoInsumoFilter') ? 'bg-secondary-500 text-white shadow-md transform scale-105' : 'text-primary-700 hover:bg-white/60 hover:text-primary-900 hover:shadow-sm hover:scale-105' }}">
                            <x-icons.package class="flex-shrink-0 w-4 h-4" />
                            <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                                :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                                Todos los Insumos
                            </span>
                        </a>
                        @endif

                        @if($permissions['administrar-insumos'] ?? false)
                        <!-- Tipos de Insumo Dinámicos -->
                        @foreach($tiposInsumo as $tipo)
                        <a href="{{ route('insumos.index', ['tipoInsumoFilter' => $tipo->id]) }}"
                            class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300 {{ request()->routeIs('insumos.index') && (request()->get('tipoInsumoFilter') == $tipo->id || request()->get('tipoInsumoFilter') == (string)$tipo->id) ? 'bg-secondary-500 text-white shadow-md transform scale-105' : 'text-primary-700 hover:bg-white/60 hover:text-primary-900 hover:shadow-sm hover:scale-105' }}">
                            <svg class="flex-shrink-0 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                </path>
                            </svg>
                            <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                                :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                                {{ $tipo->nombre_tipo }}
                            </span>
                        </a>
                        @endforeach
                        @endif

                        @if($permissions['administrar-tipo-insumos'] ?? false)
                        <!-- Gestión de Tipos de Insumo -->
                        <a href="{{ route('tipo-insumos.index') }}"
                            class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300 {{ request()->routeIs('tipo-insumos.*') ? 'bg-secondary-500 text-white shadow-md transform scale-105' : 'text-primary-700 hover:bg-white/60 hover:text-primary-900 hover:shadow-sm hover:scale-105' }}">
                            <svg class="flex-shrink-0 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                </path>
                            </svg>
                            <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                                :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                                Gestionar Tipos
                            </span>
                        </a>
                        @endif
                    </div>
                </div>
                @endif


                @if($permissions['administrar-insumos'] ?? false)
                <a href="{{ route('carga-masiva.index') }}"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('carga-masiva.index') ? 'bg-secondary-500 text-white shadow-lg transform scale-105' : 'text-primary-800 hover:bg-white/60 hover:text-primary-900 hover:shadow-sm hover:scale-105' }}">
                    <x-icons.upload class="flex-shrink-0 w-5 h-5" />
                    <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                        :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                        Carga Masiva
                    </span>
                </a>
                @endif

                @if($permissions['administrar-proveedores'] ?? false)
                <a href="{{ route('proveedores.index') }}"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('proveedores.index') ? 'bg-secondary-500 text-white shadow-lg transform scale-105' : 'text-primary-800 hover:bg-white/60 hover:text-primary-900 hover:shadow-sm hover:scale-105' }}">
                    <x-icons.truck class="flex-shrink-0 w-5 h-5" />
                    <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                        :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                        Proveedores
                    </span>
                </a>
                @endif

                @if($permissions['administrar-facturas'] ?? false)
                <a href="{{ route('facturas.index') }}"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('facturas.index') ? 'bg-secondary-500 text-white shadow-lg transform scale-105' : 'text-primary-800 hover:bg-white/60 hover:text-primary-900 hover:shadow-sm hover:scale-105' }}">
                    <x-icons.document class="flex-shrink-0 w-5 h-5" />
                    <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                        :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                        Facturas
                    </span>
                </a>
                @endif

                @if($permissions['administrar-solicitudes'] ?? false)
                <a href="{{ route('admin-solicitudes') }}"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('admin-solicitudes') ? 'bg-secondary-500 text-white shadow-lg transform scale-105' : 'text-primary-800 hover:bg-white/60 hover:text-primary-900 hover:shadow-sm hover:scale-105' }}">
                    <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                        :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                        Admin Solicitudes
                    </span>
                </a>
                @endif

                <!-- Menú desplegable de Reportes -->
                <div x-data="{ reportesOpen: {{ request()->routeIs('reportes.*') ? 'true' : 'false' }} }">
                    <!-- Botón principal de Reportes -->
                    <button type="button" @click="reportesOpen = !reportesOpen"
                        class="group flex items-center justify-between w-full px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('reportes.*') ? 'bg-secondary-100 text-primary-800 border border-secondary-300' : 'text-primary-800 hover:bg-white/60 hover:text-primary-900 hover:shadow-sm hover:scale-105' }}">
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                                :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                                Reportes
                            </span>
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': reportesOpen }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>

                    <!-- Submenú desplegable -->
                    <div x-show="reportesOpen" x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95" class="mt-2 ml-6 space-y-1">
                        
                        <!-- Reporte de Insumos -->
                        <a href="{{ route('reportes.insumos.index') }}"
                            class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300 {{ request()->routeIs('reportes.insumos.*') ? 'bg-secondary-500 text-white shadow-md transform scale-105' : 'text-primary-700 hover:bg-white/60 hover:text-primary-900 hover:shadow-sm hover:scale-105' }}">
                            <svg class="flex-shrink-0 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                                :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                                Insumos
                            </span>
                        </a>

                        <!-- Reporte de Stock Crítico -->
                        <a href="{{ route('reportes.stock.index') }}"
                            class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300 {{ request()->routeIs('reportes.stock.*') ? 'bg-secondary-500 text-white shadow-md transform scale-105' : 'text-primary-700 hover:bg-white/60 hover:text-primary-900 hover:shadow-sm hover:scale-105' }}">
                            <svg class="flex-shrink-0 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                                :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                                Stock Crítico
                            </span>
                        </a>

                        <!-- Reporte de Consumo por Departamento -->
                        <a href="{{ route('reportes.consumo-departamento.index') }}"
                            class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300 {{ request()->routeIs('reportes.consumo-departamento.*') ? 'bg-secondary-500 text-white shadow-md transform scale-105' : 'text-primary-700 hover:bg-white/60 hover:text-primary-900 hover:shadow-sm hover:scale-105' }}">
                            <svg class="flex-shrink-0 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                                :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                                Consumo por Depto.
                            </span>
                        </a>

                        <!-- Reporte de Rotación -->
                        <a href="{{ route('reportes.rotacion.index') }}"
                            class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-300 {{ request()->routeIs('reportes.rotacion.*') ? 'bg-secondary-500 text-white shadow-md transform scale-105' : 'text-primary-700 hover:bg-white/60 hover:text-primary-900 hover:shadow-sm hover:scale-105' }}">
                            <svg class="flex-shrink-0 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            <span class="ml-3 overflow-hidden transition-all duration-300 ease-in-out"
                                :class="{ 'w-auto opacity-100': isSidebarOpen, 'w-0 opacity-0': !isSidebarOpen }">
                                Rotación de Inventario
                            </span>
                        </a>
                    </div>
                </div>

            </div>
        </nav>

        <!-- Sección de Usuario - Solo visible en móvil -->
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
                    <div class="text-sm font-medium text-primary-800" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"
                        x-on:profile-updated.window="name = $event.detail.name"></div>
                </div>
            </div>
            
            <div class="space-y-1">
                <a href="{{ route('profile') }}"
                    class="flex items-center px-3 py-2 text-sm font-medium transition-all duration-200 rounded-lg text-primary-700 hover:bg-secondary-100 hover:text-primary-900">
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
                    <button type="submit" class="flex items-center w-full px-3 py-2 text-sm font-medium transition-all duration-200 rounded-lg text-primary-700 hover:bg-secondary-100 hover:text-primary-900">
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

<script>
    // Escuchar eventos de actualización de permisos y forzar recarga del sidebar
    document.addEventListener('permissions-updated', function() {
        // Si hay un componente Livewire del sidebar, forzar su actualización
        if (window.Livewire) {
            const sidebarComponent = Livewire.find('layout.sidebar');
            if (sidebarComponent) {
                sidebarComponent.$wire.$refresh();
            }
        }
    });
    
    // Escuchar cuando se actualiza el usuario actual
    window.addEventListener('user-permissions-updated', function(event) {
        console.log('Permisos del usuario actual actualizados', event.detail);
        
        // Actualizar el sidebar sin recargar la página
        if (window.Livewire) {
            const sidebarComponent = Livewire.find('layout.sidebar');
            if (sidebarComponent) {
                // Forzar actualización del componente
                // Esto recargará los permisos desde la base de datos
                sidebarComponent.$wire.$refresh();
                console.log('Sidebar actualizado sin recargar la página');
            }
        }
    });
</script>
