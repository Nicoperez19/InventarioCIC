<div>
    <div class="mb-4 flex flex-col sm:flex-row gap-4">
        <div class="flex-1">
            <input 
                type="text" 
                wire:model.live="search" 
                placeholder="Buscar proveedores..." 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
        </div>
        <div class="flex gap-2">
            <select wire:model.live="perPage" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="10">10 por página</option>
                <option value="25">25 por página</option>
                <option value="50">50 por página</option>
            </select>
        </div>
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
            @forelse ($proveedores as $proveedor)
                <li class="px-6 py-4 hover:bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <span class="text-blue-600 font-medium text-sm">
                                            {{ substr($proveedor->nombre_proveedor, 0, 2) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ $proveedor->nombre_proveedor }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        RUT: {{ $proveedor->rut_formateado }}
                                    </p>
                                    @if($proveedor->telefono)
                                        <p class="text-sm text-gray-500">
                                            Tel: {{ $proveedor->telefono }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $proveedor->facturas_count }} facturas
                                </p>
                                <p class="text-sm text-gray-500">
                                    Total: ${{ number_format($proveedor->facturas_sum_monto_total ?? 0, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('proveedores.edit', $proveedor) }}" 
                                   class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                    Editar
                                </a>
                                <button wire:click="delete({{ $proveedor->id }})" 
                                        wire:confirm="¿Estás seguro de que quieres eliminar este proveedor?"
                                        class="text-red-600 hover:text-red-900 text-sm font-medium">
                                    Eliminar
                                </button>
                            </div>
                        </div>
                    </div>
                </li>
            @empty
                <li class="px-6 py-4 text-center text-gray-500">
                    No se encontraron proveedores
                </li>
            @endforelse
        </ul>
    </div>

    <div class="mt-4">
        {{ $proveedores->links() }}
    </div>
</div>