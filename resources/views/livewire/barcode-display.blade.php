<div class="bg-white rounded-lg shadow p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Código QR</h3>
        <div class="flex space-x-2">
            @if($insumo->codigo_barra)
                <button wire:click="regenerateBarcode" 
                        class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-sm"
                        onclick="return confirm('¿Regenerar código QR?')">
                    Regenerar
                </button>
                <a href="{{ route('barcode.generate', $insumo->id_insumo) }}" 
                   target="_blank"
                   class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm">
                    Ver Imagen
                </a>
            @endif
        </div>
    </div>

    @if($insumo->codigo_barra)
        <div class="space-y-4">
            <!-- Código QR numérico -->
            <div class="bg-gray-50 p-3 rounded">
                <label class="block text-sm font-medium text-gray-700 mb-1">Código:</label>
                <div class="flex items-center space-x-2">
                    <code class="text-lg font-mono bg-white px-2 py-1 rounded border">{{ $insumo->codigo_barra }}</code>
                    <button onclick="navigator.clipboard.writeText('{{ $insumo->codigo_barra }}')" 
                            class="text-blue-500 hover:text-blue-700 text-sm">
                        📋 Copiar
                    </button>
                </div>
                
                <!-- Información del código QR -->
                @php
                    $barcodeService = new \App\Services\BarcodeService();
                    $barcodeInfo = $barcodeService->getBarcodeInfo($insumo->codigo_barra);
                @endphp
                
                @if($barcodeInfo['valid'])
                    <div class="mt-2 text-sm text-gray-600">
                        <div class="grid grid-cols-1 gap-2">
                            <div>
                                <span class="font-medium">Unidad:</span> {{ $barcodeInfo['unit']['name'] }}
                            </div>
                            <div>
                                <span class="font-medium">Tipo:</span> {{ $barcodeInfo['type'] }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Imagen del código QR -->
            @if($showBarcode && $barcodeUrl)
                <div class="text-center">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Imagen:</label>
                    <div class="inline-block p-4 bg-white border rounded-lg">
                        <img src="{{ $barcodeUrl }}" 
                             alt="Código QR {{ $insumo->codigo_barra }}"
                             class="max-w-full h-auto"
                             style="max-width: 300px; max-height: 300px;">
                    </div>
                </div>
            @endif

            <!-- Enlaces de descarga -->
            <div class="flex justify-center space-x-2">
                <a href="{{ route('barcode.generate', $insumo->id_insumo) }}" 
                   target="_blank"
                   class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 text-sm">
                    📥 Descargar PNG
                </a>
                <a href="{{ route('barcode.svg', $insumo->id_insumo) }}" 
                   target="_blank"
                   class="px-4 py-2 bg-purple-500 text-white rounded hover:bg-purple-600 text-sm">
                    📥 Descargar SVG
                </a>
            </div>
        </div>
    @else
        <div class="text-center py-8 text-gray-500">
            <div class="text-4xl mb-2">📱</div>
            <p>Este insumo no tiene código QR asignado.</p>
            <button wire:click="regenerateBarcode" 
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