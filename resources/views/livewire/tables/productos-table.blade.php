<div>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unidad</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($productos as $producto)
                <tr>
                    <td class="px-4 py-2">{{ $producto->id_producto }}</td>
                    <td class="px-4 py-2">{{ $producto->codigo_producto }}</td>
                    <td class="px-4 py-2">{{ $producto->nombre_producto }}</td>
                    <td class="px-4 py-2">{{ $producto->stock_actual }}</td>
                    <td class="px-4 py-2">{{ $producto->id_unidad }}</td>
                    <td class="px-4 py-2">
                        <a href="{{ route('productos.edit', $producto->id_producto) }}" class="text-blue-500 mr-3">Editar</a>
                        <form action="{{ route('productos.destroy', $producto->id_producto) }}" method="POST" class="inline" onsubmit="return confirm('¿Seguro que deseas eliminar este producto?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="px-4 py-4 text-center text-gray-500" colspan="6">No hay productos.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


