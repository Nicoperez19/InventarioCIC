<div>
    <!-- Barra de búsqueda y filtros -->
    <div class="mb-6 bg-white rounded-lg shadow-sm border border-neutral-200 overflow-hidden">
        <!-- Header del panel de filtros -->
        <div class="bg-primary-50 border-b border-neutral-200 px-4 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    <h3 class="text-sm font-semibold text-primary-800">Filtros de Búsqueda</h3>
                </div>
                @if($search || $departamentoFilter)
                    <button 
                        wire:click="$set('search', ''); $set('departamentoFilter', '')"
                        class="text-xs font-medium text-primary-600 hover:text-primary-800 transition-colors duration-150 flex items-center space-x-1"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span>Limpiar filtros</span>
                    </button>
                @endif
            </div>
        </div>

        <!-- Contenido de los filtros -->
        <div class="p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Búsqueda principal -->
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">
                        <div class="flex items-center space-x-1">
                            <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span>Buscar</span>
                        </div>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            wire:model.live="search" 
                            placeholder="Buscar por nombre, correo o RUN..." 
                            class="block w-full pl-10 pr-3 py-2.5 border border-neutral-300 rounded-lg bg-white text-neutral-900 placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-150"
                        >
                    </div>
                </div>

                <!-- Filtro por Departamento -->
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">
                        <div class="flex items-center space-x-1">
                            <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span>Departamento</span>
                        </div>
                    </label>
                    <select 
                        wire:model.live="departamentoFilter" 
                        class="w-full px-3 py-2.5 border border-neutral-300 rounded-lg bg-white text-neutral-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-150 hover:border-primary-300 pr-10"
                    >
                        <option value="">Todos los departamentos</option>
                        @foreach($departamentos as $departamento)
                            <option value="{{ $departamento->id_depto }}">{{ $departamento->nombre_depto }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Indicador de filtros activos -->
            @if($search || $departamentoFilter)
                <div class="flex items-center justify-end mt-4 pt-4 border-t border-neutral-200">
                    <div class="flex items-center space-x-2 text-sm text-neutral-600">
                        <svg class="w-4 h-4 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Filtros activos</span>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Tabla -->
    <div class="w-full bg-white shadow-sm rounded-lg border border-neutral-200 overflow-hidden">
        <div class="w-full overflow-x-auto">
        <table class="w-full table-fixed divide-y divide-neutral-200" style="min-width: 800px;">
        <thead class="bg-primary-100">
            <tr>
                    <th class="w-1/12 px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <div class="flex items-center space-x-1">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            <span class="hidden sm:inline">RUN</span>
                        </div>
                    </th>
                    <th class="w-3/12 px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <div class="flex items-center space-x-1 pl-6">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="hidden sm:inline">Nombre</span>
                        </div>
                    </th>
                    <th class="w-3/12 px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <div class="flex items-center space-x-1 pl-6">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span class="hidden sm:inline">Email</span>
                        </div>
                    </th>
                    <th class="w-2/12 px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <div class="flex items-center space-x-1 pl-6">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span class="hidden sm:inline">Departamento</span>
                        </div>
                    </th>
                    <th class="w-3/12 px-3 sm:px-6 py-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <div class="flex items-center justify-end space-x-1 pr-6">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                            </svg>
                            <span class="hidden sm:inline">Acciones</span>
                        </div>
                    </th>
            </tr>
        </thead>
            <tbody class="bg-white divide-y divide-neutral-200">
            @forelse($users as $user)
                    <tr wire:key="user-{{ $user->run }}" class="hover:bg-secondary-50 transition-colors duration-150">
                        <td class="w-1/12 px-3 sm:px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-neutral-500">
                                {{ $user->run }}
                            </div>
                        </td>
                        <td class="w-3/12 px-3 sm:px-6 py-4">
                            <div class="text-sm font-medium text-neutral-900 pl-6">{{ $user->nombre }}</div>
                        </td>
                        <td class="w-3/12 px-3 sm:px-6 py-4">
                            <div class="text-sm text-neutral-600 pl-6">{{ $user->correo }}</div>
                        </td>
                        <td class="w-2/12 px-3 sm:px-6 py-4">
                            <div class="text-sm text-neutral-600 pl-6">{{ $user->departamento->nombre_depto ?? 'Sin departamento' }}</div>
                        </td>
                        <td class="w-3/12 px-3 sm:px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center justify-end space-x-1 sm:space-x-2 lg:space-x-3">
                                <!-- Botón Editar -->
                                <button type="button" 
                                        onclick="openEditModal('{{ $user->run }}')"
                                        class="inline-flex items-center px-2 sm:px-3 py-1 sm:py-2 border border-transparent text-xs font-medium rounded-md text-primary-600 bg-primary-50 hover:bg-primary-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-400 transition-all duration-150">
                                    <svg class="w-3 h-3 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    <span class="hidden sm:inline">Editar</span>
                                </button>
                                
                                <!-- Botón Eliminar -->
                                <form action="{{ route('users.destroy', $user->run) }}" 
                                      method="POST" 
                                      class="inline" 
                                      onsubmit="return confirm('¿Estás seguro de que deseas eliminar el usuario \'{{ $user->nombre }}\'? Esta acción no se puede deshacer.');">
                            @csrf
                            @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center px-2 sm:px-3 py-1 sm:py-2 border border-transparent text-xs font-medium rounded-md text-danger-600 bg-danger-50 hover:bg-danger-600 hover:text-white active:bg-danger-700 active:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-danger-500 transition-colors duration-150">
                                        <svg class="w-3 h-3 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        <span class="hidden sm:inline">Eliminar</span>
                                    </button>
                        </form>
                            </div>
                    </td>
                </tr>
            @empty
                <tr>
                        <td class="px-3 sm:px-6 py-12 text-center" colspan="5">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-neutral-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                                @if($search || $departamentoFilter)
                                    <h3 class="text-lg font-medium text-neutral-900 mb-2">No se encontraron usuarios</h3>
                                    <p class="text-neutral-500">Intenta ajustar los filtros de búsqueda para ver más resultados.</p>
                                @else
                                    <h3 class="text-lg font-medium text-neutral-900 mb-2">No hay usuarios</h3>
                                    <p class="text-neutral-500">Comienza creando tu primer usuario para organizar tu sistema.</p>
                                @endif
                            </div>
                        </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    
    <!-- Paginación -->
    <div class="px-4 py-3 bg-gray-50 border-t border-neutral-200">
        {{ $users->links() }}
    </div>
</div>
