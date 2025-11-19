@php
use Illuminate\Support\Facades\Auth;

$user = Auth::user();
$tiposInsumo = \App\Models\TipoInsumo::orderBy('nombre_tipo')->get();

// Función simple para verificar permisos
$can = function($permission) use ($user) {
    if (!$user) return false;
    
    // Si tiene permisos directos, solo esos cuentan
    $directPermissions = $user->getDirectPermissions()->pluck('name')->toArray();
    if (!empty($directPermissions)) {
        return in_array($permission, $directPermissions);
    }
    
    // Si no tiene permisos directos, usar los de roles
    return $user->can($permission);
};
@endphp

<aside class="fixed inset-y-0 left-0 z-50 w-64 bg-primary-100 shadow-xl md:flex md:flex-col" data-livewire-ignore
      :class="{ 'w-64': isSidebarOpen, 'w-0': !isSidebarOpen }" 
      x-show="isSidebarOpen"
      x-transition:enter="transition ease-out duration-300"
      x-transition:enter-start="-translate-x-full"
      x-transition:enter-end="translate-x-0"
      x-transition:leave="transition ease-in duration-300"
      x-transition:leave-start="translate-x-0"
      x-transition:leave-end="-translate-x-full">

    <!-- Header -->
    <div class="flex items-center h-16 px-3 bg-primary-500 shadow-lg">
        <div class="flex items-center">
            <div class="bg-white/20 backdrop-blur-sm rounded-xl p-2 mr-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-auto">
            </div>
            <h1 class="text-xl font-semibold text-white" :class="{ 'hidden': !isSidebarOpen }">
                GestionCIC
            </h1>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-3 py-6 overflow-y-auto">
        <div class="space-y-1">
                @if($can('dashboard'))
                <a href="{{ route('dashboard') }}" 
                   data-livewire-ignore
                   class="flex items-center px-3 py-2 text-sm font-medium rounded-lg sidebar-link {{ request()->routeIs('dashboard') ? 'bg-secondary-500 text-white' : 'text-primary-800 hover:bg-white/60' }}">
                <x-icons.dashboard class="w-5 h-5 mr-3" />
                <span :class="{ 'hidden': !isSidebarOpen }">Dashboard</span>
            </a>
            @endif

                @if($can('solicitudes'))
                <a href="{{ route('solicitudes') }}" 
                   data-livewire-ignore
                   class="flex items-center px-3 py-2 text-sm font-medium rounded-lg sidebar-link {{ request()->routeIs('solicitudes') ? 'bg-secondary-500 text-white' : 'text-primary-800 hover:bg-white/60' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <span :class="{ 'hidden': !isSidebarOpen }">Solicitudes</span>
            </a>
            @endif

                @if($can('mantenedor de usuarios'))
                <a href="{{ route('users') }}" 
                   data-livewire-ignore
                   class="flex items-center px-3 py-2 text-sm font-medium rounded-lg sidebar-link {{ request()->routeIs('users') ? 'bg-secondary-500 text-white' : 'text-primary-800 hover:bg-white/60' }}">
                <x-icons.users class="w-5 h-5 mr-3" />
                <span :class="{ 'hidden': !isSidebarOpen }">Usuarios</span>
            </a>
            @endif

                @if($can('mantenedor de departamentos'))
                <a href="{{ route('departamentos') }}" 
                   data-livewire-ignore
                   class="flex items-center px-3 py-2 text-sm font-medium rounded-lg sidebar-link {{ request()->routeIs('departamentos') ? 'bg-secondary-500 text-white' : 'text-primary-800 hover:bg-white/60' }}">
                <x-icons.building class="w-5 h-5 mr-3" />
                <span :class="{ 'hidden': !isSidebarOpen }">Departamentos</span>
            </a>
            @endif

                @if($can('mantenedor de unidades'))
                <a href="{{ route('unidades') }}" 
                   data-livewire-ignore
                   class="flex items-center px-3 py-2 text-sm font-medium rounded-lg sidebar-link {{ request()->routeIs('unidades') ? 'bg-secondary-500 text-white' : 'text-primary-800 hover:bg-white/60' }}">
                <x-icons.cube class="w-5 h-5 mr-3" />
                <span :class="{ 'hidden': !isSidebarOpen }">Unidades</span>
            </a>
            @endif

            @if($can('insumos'))
            <div x-data="{ open: false }" x-init="open = {{ request()->routeIs('insumos.*') || request()->routeIs('tipo-insumos.*') ? 'true' : 'false' }}">
                <button @click="open = !open" 
                        class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium rounded-lg text-primary-800 hover:bg-white/60">
                    <div class="flex items-center">
                        <x-icons.package class="w-5 h-5 mr-3" />
                        <span :class="{ 'hidden': !isSidebarOpen }">Insumos</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                    <div x-show="open" x-transition class="ml-6 mt-1 space-y-1">
                        <a href="{{ route('insumos.index') }}" 
                           data-livewire-ignore
                           class="flex items-center px-3 py-2 text-sm rounded-lg sidebar-link {{ request()->routeIs('insumos.index') && !request()->get('tipoInsumoFilter') ? 'bg-secondary-500 text-white' : 'text-primary-700 hover:bg-white/60' }}">
                        <x-icons.package class="w-4 h-4 mr-3" />
                        <span :class="{ 'hidden': !isSidebarOpen }">Todos</span>
                    </a>
                        @foreach($tiposInsumo as $tipo)
                        <a href="{{ route('insumos.index', ['tipoInsumoFilter' => $tipo->id]) }}" 
                           data-livewire-ignore
                           class="flex items-center px-3 py-2 text-sm rounded-lg sidebar-link {{ request()->get('tipoInsumoFilter') == $tipo->id ? 'bg-secondary-500 text-white' : 'text-primary-700 hover:bg-white/60' }}">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        <span :class="{ 'hidden': !isSidebarOpen }">{{ $tipo->nombre_tipo }}</span>
                    </a>
                    @endforeach
                        @if($can('mantenedor de tipos de insumo'))
                        <a href="{{ route('tipo-insumos.index') }}" 
                           data-livewire-ignore
                           class="flex items-center px-3 py-2 text-sm rounded-lg sidebar-link {{ request()->routeIs('tipo-insumos.*') ? 'bg-secondary-500 text-white' : 'text-primary-700 hover:bg-white/60' }}">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span :class="{ 'hidden': !isSidebarOpen }">Gestionar Tipos</span>
                    </a>
                    @endif
                </div>
            </div>
            @endif

                @if($can('carga masiva'))
                <a href="{{ route('carga-masiva.index') }}" 
                   data-livewire-ignore
                   class="flex items-center px-3 py-2 text-sm font-medium rounded-lg sidebar-link {{ request()->routeIs('carga-masiva.index') ? 'bg-secondary-500 text-white' : 'text-primary-800 hover:bg-white/60' }}">
                <x-icons.upload class="w-5 h-5 mr-3" />
                <span :class="{ 'hidden': !isSidebarOpen }">Carga Masiva</span>
            </a>
            @endif

                @if($can('mantenedor de proveedores'))
                <a href="{{ route('proveedores.index') }}" 
                   data-livewire-ignore
                   class="flex items-center px-3 py-2 text-sm font-medium rounded-lg sidebar-link {{ request()->routeIs('proveedores.index') ? 'bg-secondary-500 text-white' : 'text-primary-800 hover:bg-white/60' }}">
                <x-icons.truck class="w-5 h-5 mr-3" />
                <span :class="{ 'hidden': !isSidebarOpen }">Proveedores</span>
            </a>
            @endif

                @if($can('mantenedor de facturas'))
                <a href="{{ route('facturas.index') }}" 
                   data-livewire-ignore
                   class="flex items-center px-3 py-2 text-sm font-medium rounded-lg sidebar-link {{ request()->routeIs('facturas.index') ? 'bg-secondary-500 text-white' : 'text-primary-800 hover:bg-white/60' }}">
                <x-icons.document class="w-5 h-5 mr-3" />
                <span :class="{ 'hidden': !isSidebarOpen }">Facturas</span>
            </a>
            @endif

                @if($can('admin solicitudes'))
                <a href="{{ route('admin-solicitudes') }}" 
                   data-livewire-ignore
                   class="flex items-center px-3 py-2 text-sm font-medium rounded-lg sidebar-link {{ request()->routeIs('admin-solicitudes') ? 'bg-secondary-500 text-white' : 'text-primary-800 hover:bg-white/60' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span :class="{ 'hidden': !isSidebarOpen }">Admin Solicitudes</span>
            </a>
            @endif

            @if($can('reportes'))
            <div x-data="{ open: false }" x-init="open = {{ request()->routeIs('reportes.*') ? 'true' : 'false' }}">
                <button @click="open = !open" 
                        class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium rounded-lg text-primary-800 hover:bg-white/60">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span :class="{ 'hidden': !isSidebarOpen }">Reportes</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                    <div x-show="open" x-transition class="ml-6 mt-1 space-y-1">
                        @if($can('reportes insumos'))
                        <a href="{{ route('reportes.insumos.index') }}" 
                           data-livewire-ignore
                           class="flex items-center px-3 py-2 text-sm rounded-lg sidebar-link {{ request()->routeIs('reportes.insumos.*') ? 'bg-secondary-500 text-white' : 'text-primary-700 hover:bg-white/60' }}">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <span :class="{ 'hidden': !isSidebarOpen }">Insumos</span>
                    </a>
                    @endif
                        @if($can('reportes stock'))
                        <a href="{{ route('reportes.stock.index') }}" 
                           data-livewire-ignore
                           class="flex items-center px-3 py-2 text-sm rounded-lg sidebar-link {{ request()->routeIs('reportes.stock.*') ? 'bg-secondary-500 text-white' : 'text-primary-700 hover:bg-white/60' }}">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span :class="{ 'hidden': !isSidebarOpen }">Stock Crítico</span>
                    </a>
                    @endif
                        @if($can('reportes consumo departamento'))
                        <a href="{{ route('reportes.consumo-departamento.index') }}" 
                           data-livewire-ignore
                           class="flex items-center px-3 py-2 text-sm rounded-lg sidebar-link {{ request()->routeIs('reportes.consumo-departamento.*') ? 'bg-secondary-500 text-white' : 'text-primary-700 hover:bg-white/60' }}">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span :class="{ 'hidden': !isSidebarOpen }">Consumo por Depto.</span>
                    </a>
                    @endif
                        @if($can('reportes rotacion'))
                        <a href="{{ route('reportes.rotacion.index') }}" 
                           data-livewire-ignore
                           class="flex items-center px-3 py-2 text-sm rounded-lg sidebar-link {{ request()->routeIs('reportes.rotacion.*') ? 'bg-secondary-500 text-white' : 'text-primary-700 hover:bg-white/60' }}">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span :class="{ 'hidden': !isSidebarOpen }">Rotación</span>
                    </a>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </nav>

    <!-- Footer móvil -->
    <div class="p-4 border-t border-primary-300/50 md:hidden">
        <a href="{{ route('profile') }}" 
           data-livewire-ignore
           class="flex items-center px-3 py-2 text-sm font-medium rounded-lg sidebar-link text-primary-700 hover:bg-secondary-100">
            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Perfil
        </a>
        <form method="POST" action="{{ route('logout') }}" class="mt-1">
            @csrf
            <button type="submit" class="flex items-center w-full px-3 py-2 text-sm font-medium rounded-lg text-primary-700 hover:bg-secondary-100">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Cerrar Sesión
            </button>
        </form>
    </div>
</aside>

