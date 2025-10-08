<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Gestión de unidades') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="flex items-center justify-between mb-6">
            <x-button href="{{ route('unidades.create') }}" variant="add" class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Agregar Unidad
            </x-button>
        </div>
        <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
            <div class="p-4 bg-white shadow sm:p-8 sm:rounded-lg">
                <div class="max-w-xl">
                    <livewire:tables.unidades-table />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


