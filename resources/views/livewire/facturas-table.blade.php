<div>
    <div class="mb-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div>
            <input 
                type="text" 
                wire:model.live="search" 
                placeholder="Buscar facturas..." 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
        </div>
        <div>
            <select wire:model.live="proveedorFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Todos los proveedores</option>
                @foreach($proveedores as $proveedor)
                    <option value="{{ $proveedor->id }}">{{ $proveedor->nombre_proveedor }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <input 
                type="date" 
                wire:model.live="fechaDesde" 
                placeholder="Fecha desde" 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
        </div>
        <div>
            <input 
                type="date" 
                wire:model.live="fechaHasta" 
                placeholder="Fecha hasta" 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
        </div>
    </div>

    <div class="mb-4 flex justify-between items-center">
        <div class="flex gap-2">
            <select wire:model.live="perPage" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="10">10 por página</option>
                <option value="25">25 por página</option>
                <option value="50">50 por página</option>
            </select>
        </div>
        <a href="{{ route('facturas.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
            Nueva Factura
        </a>
    </div>

    @if (session()->has('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            @forelse ($facturas as $factura)
                <li class="px-6 py-4 hover:bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 bg-green-100 rounded-full flex items-center justify-center">
                                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ $factura->numero_factura }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ $factura->proveedor->nombre_proveedor }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ $factura->fecha_factura->format('d/m/Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">
                                    ${{ number_format($factura->monto_total, 0, ',', '.') }}
                                </p>
                                @if($factura->tieneArchivo())
                                    <p class="text-xs text-green-600">Con archivo</p>
                                @else
                                    <p class="text-xs text-gray-400">Sin archivo</p>
                                @endif
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('facturas.show', $factura) }}" 
                                   class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                    Ver
                                </a>
                                <a href="{{ route('facturas.edit', $factura) }}" 
                                   class="text-green-600 hover:text-green-900 text-sm font-medium">
                                    Editar
                                </a>
                                @if($factura->tieneArchivo())
                                    <button wire:click="download({{ $factura->id }})" 
                                            class="text-purple-600 hover:text-purple-900 text-sm font-medium">
                                        Descargar
                                    </button>
                                @endif
                                <button wire:click="delete({{ $factura->id }})" 
                                        wire:confirm="¿Estás seguro de que quieres eliminar esta factura?"
                                        class="text-red-600 hover:text-red-900 text-sm font-medium">
                                    Eliminar
                                </button>
                            </div>
                        </div>
                    </div>
                </li>
            @empty
                <li class="px-6 py-4 text-center text-gray-500">
                    No se encontraron facturas
                </li>
            @endforelse
        </ul>
    </div>

    <div class="mt-4">
        {{ $facturas->links() }}
    </div>
</div>