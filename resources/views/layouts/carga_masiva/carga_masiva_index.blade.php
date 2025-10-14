<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Carga Masiva de Datos') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if (session('status'))
                    <div class="mb-4 text-sm text-green-600">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('carga-masiva.upload') }}" enctype="multipart/form-data"
                    class="space-y-4">
                    @csrf
                    <div>
                        <x-input-label for="archivo" :value="__('Archivo Excel')" />
                        <div class="mt-1 relative">
                            <input id="archivo" name="archivo" type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept=".xlsx,.xls,.csv" required />
                            <div class="flex items-center justify-between w-full px-4 py-3 border border-gray-300 rounded-lg bg-white hover:bg-gray-50 cursor-pointer">
                                <span class="text-sm text-gray-500" id="file-name">Seleccionar archivo...</span>
                                <span class="text-sm text-teal-600 font-medium">Elegir archivo</span>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Formatos soportados: .xlsx, .xls, .csv</p>
                    </div>


                    <div class="flex items-center gap-3">
                        <x-primary-button>{{ __('Cargar Archivo') }}</x-primary-button>
                        <a href="#" class="text-sm text-gray-600 underline">{{ __('Descargar Plantilla') }}</a>
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
