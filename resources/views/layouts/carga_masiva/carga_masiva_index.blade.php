<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Carga Masiva de Datos') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                @if (session('status'))
                    <div class="mb-4 text-sm text-green-600">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('carga-masiva.upload') }}" enctype="multipart/form-data"
                    class="space-y-4">
                    @csrf
                    <div>
                        <x-input-label for="archivo" :value="__('Archivo Excel')" />
                        <div class="relative mt-1">
                            <input id="archivo" name="file" type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept=".xlsx,.xls,.csv" required />
                            <div class="flex items-center justify-between w-full px-4 py-3 bg-white border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                <span class="text-sm text-gray-500" id="file-name">Seleccionar archivo...</span>
                                <span class="text-sm font-medium text-teal-600">Elegir archivo</span>
                            </div>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Formatos soportados: .xlsx, .xls, .csv</p>
                        @error('file')
                            <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="flex items-center gap-3">
                        <x-button type="submit">{{ __('Cargar Archivo') }}</x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('archivo').addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : 'Seleccionar archivo...';
            document.getElementById('file-name').textContent = fileName;
        });
    </script>
</x-app-layout>
