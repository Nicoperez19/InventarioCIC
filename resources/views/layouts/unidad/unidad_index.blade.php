<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-dark-teal rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h2 class="text-xl font-semibold leading-tight text-gray-800">
                        {{ __('Gestión de unidades') }}
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">Administra y organiza las unidades de medida del sistema</p>
                </div>
            </div>
            <a href="{{ route('unidades.create') }}" 
               class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-dark-teal rounded-lg hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-dark-teal transition-colors duration-150 shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Agregar Unidad
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
            <div class="p-4 bg-white shadow sm:p-8 sm:rounded-lg">
                <livewire:tables.unidades-table />
            </div>
        </div>
    </div>
</x-app-layout>


