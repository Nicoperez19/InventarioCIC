<div class="bg-white rounded-lg shadow p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Código QR</h3>
        <div class="flex space-x-2">
            @if($user->codigo_barra)
                <button wire:click="regenerateQr" 
                        class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-sm"
                        onclick="return confirm('¿Regenerar código QR?')">
                    Regenerar
                </button>
            @else
                <button wire:click="generateQr" 
                        class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm">
                    Generar
                </button>
            @endif
        </div>
    </div>

    @if($user->codigo_barra)
        <div class="space-y-4">
            <!-- Código QR numérico -->
            <div class="bg-gray-50 p-3 rounded">
                <label class="block text-sm font-medium text-gray-700 mb-1">Código:</label>
                <div class="flex items-center space-x-2">
                    <code class="text-lg font-mono bg-white px-2 py-1 rounded border">{{ $user->codigo_barra }}</code>
                    <button onclick="navigator.clipboard.writeText('{{ $user->codigo_barra }}')" 
                            class="text-blue-500 hover:text-blue-700 text-sm">
                        📋 Copiar
                    </button>
                </div>
            </div>

            <!-- Imagen del código QR -->
            @if($showQr && $qrUrl)
                <div class="text-center">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Imagen:</label>
                    <div class="inline-block p-4 bg-white border rounded-lg">
                        <img src="{{ $qrUrl }}" 
                             alt="Código QR {{ $user->codigo_barra }}"
                             class="max-w-full h-auto"
                             style="max-width: 300px; max-height: 300px;">
                    </div>
                </div>
            @endif
        </div>
    @else
        <div class="text-center py-8 text-gray-500">
            <div class="text-4xl mb-2">📱</div>
            <p>Este usuario no tiene código QR asignado.</p>
            <button wire:click="generateQr" 
                    class="mt-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                Generar Código QR
            </button>
        </div>
    @endif

    @if (session()->has('message'))
        <div class="mt-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('message') }}
        </div>
    @endif
</div>


