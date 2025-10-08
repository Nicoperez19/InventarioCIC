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
            @forelse($departamentos as $departamento)
                <tr>
                    <td class="px-4 py-2">{{ $departamento->id_depto }}</td>
                    <td class="px-4 py-2">{{ $departamento->nombre_depto }}</td>
                    <td class="px-4 py-2">
                        <a href="{{ route('departamentos.edit', $departamento->id_depto) }}" class="text-blue-500 mr-3">Editar</a>
                        <form action="{{ route('departamentos.destroy', $departamento->id_depto) }}" method="POST" class="inline" onsubmit="return confirm('¿Seguro que deseas eliminar este departamento?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="px-4 py-4 text-center text-gray-500" colspan="3">No hay departamentos.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


