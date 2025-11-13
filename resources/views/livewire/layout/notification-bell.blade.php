<div 
    x-data="{ open: false }" 
    class="relative"
    wire:poll.30s="poll">
    <!-- Botón de notificaciones -->
    <button 
        @click="open = !open"
        class="relative inline-flex items-center p-2 text-sm font-medium text-white transition-all duration-200 rounded-lg hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/50"
        wire:click="cargarNotificaciones">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
            </path>
        </svg>
        
        <!-- Badge con contador -->
        @if($countNoLeidas > 0)
            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                {{ $countNoLeidas > 99 ? '99+' : $countNoLeidas }}
            </span>
        @endif
    </button>

    <!-- Dropdown de notificaciones -->
    <div 
        x-show="open"
        @click.away="open = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-95"
        class="absolute right-0 mt-2 w-80 md:w-96 bg-white rounded-lg shadow-xl z-50 border border-gray-200"
        style="display: none;">
        
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-primary-50">
            <h3 class="text-lg font-semibold text-gray-900">Notificaciones</h3>
            @if($countNoLeidas > 0)
                <button 
                    wire:click="marcarTodasComoLeidas"
                    class="text-sm text-primary-600 hover:text-primary-800 font-medium">
                    Marcar todas como leídas
                </button>
            @endif
        </div>

        <!-- Lista de notificaciones -->
        <div class="max-h-96 overflow-y-auto">
            @forelse($notificaciones as $notificacion)
                <a 
                    href="{{ $notificacion['solicitud_id'] ? route('admin-solicitudes') : '#' }}"
                    wire:key="notificacion-{{ $notificacion['id'] }}"
                    class="block p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors cursor-pointer
                        {{ !$notificacion['leida'] ? 'bg-blue-50' : '' }}"
                    @click="open = false"
                    wire:click="marcarComoLeida({{ $notificacion['id'] }})">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            @if($notificacion['tipo'] === 'solicitud')
                                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-100">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                            @else
                                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-100">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-gray-900">
                                {{ $notificacion['titulo'] }}
                            </p>
                            <p class="mt-1 text-sm text-gray-500">
                                {{ $notificacion['mensaje'] }}
                            </p>
                            <p class="mt-1 text-xs text-gray-400">
                                {{ \Carbon\Carbon::parse($notificacion['created_at'])->diffForHumans() }}
                            </p>
                        </div>
                        @if(!$notificacion['leida'])
                            <div class="flex-shrink-0 ml-2">
                                <div class="w-2 h-2 bg-blue-600 rounded-full"></div>
                            </div>
                        @endif
                    </div>
                </a>
            @empty
                <div class="p-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <p class="mt-2 text-sm text-gray-500">No hay notificaciones</p>
                </div>
            @endforelse
        </div>

        <!-- Footer -->
        @if(count($notificaciones) > 0)
            <div class="p-3 border-t border-gray-200 bg-gray-50 text-center">
                <a href="{{ route('admin-solicitudes') }}" class="text-sm text-primary-600 hover:text-primary-800 font-medium">
                    Ver todas las solicitudes
                </a>
            </div>
        @endif
    </div>
</div>
