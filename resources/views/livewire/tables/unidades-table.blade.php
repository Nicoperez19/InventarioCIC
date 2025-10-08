<div>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($unidades as $unidad)
                <tr>
                    <td class="px-4 py-2">{{ $unidad->id_unidad }}</td>
                    <td class="px-4 py-2">{{ $unidad->nombre_unidad }}</td>
                    <td class="px-4 py-2">
                        <a href="{{ route('unidades.edit', $unidad->id_unidad) }}" class="text-blue-500 mr-3">Editar</a>
                        <form action="{{ route('unidades.destroy', $unidad->id_unidad) }}" method="POST" class="inline" onsubmit="return confirm('¿Seguro que deseas eliminar esta unidad?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="px-4 py-4 text-center text-gray-500" colspan="3">No hay unidades.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


