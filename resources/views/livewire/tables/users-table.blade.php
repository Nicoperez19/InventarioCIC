<div>
    <table id="users_table" class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Creado</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>

            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($users as $user)
                <tr>
                    <td class="px-4 py-2">{{ $user->id }}</td>
                    <td class="px-4 py-2">{{ $user->name }}</td>
                    <td class="px-4 py-2">{{ $user->email }}</td>
                    <td class="px-4 py-2">{{ optional($user->created_at)->format('Y-m-d H:i') }}</td>
                    <td class="px-4 py-2">
                        <a href="{{ route('users.edit', $user->id) }}" class="text-blue-500 mr-3">Editar</a>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Seguro que deseas eliminar este usuario?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="px-4 py-4 text-center text-gray-500" colspan="5">No hay usuarios.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
