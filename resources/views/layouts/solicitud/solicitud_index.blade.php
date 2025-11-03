<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center space-x-3 sm:space-x-4">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg sm:w-10 sm:h-10 bg-gradient-to-br from-blue-400 to-blue-500">
                        <svg class="w-4 h-4 text-white sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <h2 class="text-lg font-semibold leading-tight text-gray-800 truncate sm:text-xl">
                        {{ __('Solicitud de Insumos') }}
                    </h2>
                    <p class="hidden mt-1 text-xs text-gray-600 sm:text-sm sm:block">
                        @if(auth()->user()->hasRole('jefe-departamento'))
                            Solicita artículos de oficina disponibles
                        @elseif(auth()->user()->hasRole('auxiliar'))
                            Solicita artículos de aseo disponibles
                        @else
                            Solicita insumos disponibles según tu rol
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
                      <!-- Tabla de solicitudes -->
            <div class="p-4 bg-white shadow sm:p-8 sm:rounded-lg">
                <livewire:tables.solicitud-insumos-table />
            </div>
        </div>
    </div>
</x-app-layout>

