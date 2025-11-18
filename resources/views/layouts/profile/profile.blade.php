<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center space-x-3 sm:space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-primary-400 to-primary-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                </div>
                <div class="min-w-0 flex-1">
                    <h2 class="text-lg sm:text-xl font-semibold leading-tight text-gray-800 truncate">
                        {{ __('Mi Perfil') }}
                    </h2>
                    <p class="text-xs sm:text-sm text-gray-600 mt-1 hidden sm:block">Gestiona tu información personal y configuración de cuenta</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Información del Perfil -->
                <div class="p-4 bg-white shadow-sm rounded-lg border border-neutral-200 sm:p-8">
                    <livewire:profile.update-profile-information-form />
                </div>

                <!-- Cambio de Contraseña -->
                <div class="p-4 bg-white shadow-sm rounded-lg border border-neutral-200 sm:p-8">
                    <livewire:profile.update-password-form />
                </div>
            </div>
            
            <!-- Configuración del Favicon -->
            <div class="mt-6">
                <div class="p-4 bg-white shadow-sm rounded-lg border border-neutral-200 sm:p-8">
                    <livewire:profile.update-favicon-form />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
