<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center space-x-3 sm:space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-primary-400 to-primary-500 rounded-lg flex items-center justify-center">
                        <x-icons.dashboard class="w-4 h-4 sm:w-5 sm:h-5 text-white" />
                    </div>
                </div>
                <div class="min-w-0 flex-1">
                    <h2 class="text-lg sm:text-xl font-semibold leading-tight text-gray-800 truncate">
                        {{ __('Dashboard') }}
                    </h2>
                    <p class="text-xs sm:text-sm text-gray-600 mt-1 hidden sm:block">Gestión de inventario y solicitudes</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
            
            <!-- ========== SECCIÓN: INVENTARIO ========== -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    Inventario
                </h3>
                
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                    <!-- Total Insumos -->
                    <div class="overflow-hidden bg-white rounded-lg shadow hover:shadow-md transition-shadow">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 w-0 ml-4">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Total Insumos</dt>
                                        <dd class="text-2xl font-bold text-gray-900">{{ number_format($stats['inventario']['total_insumos']) }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stock Crítico -->
                    <div class="overflow-hidden bg-white rounded-lg shadow hover:shadow-md transition-shadow border-l-4 border-red-500">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 w-0 ml-4">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Stock Crítico</dt>
                                        <dd class="text-2xl font-bold text-red-600">{{ number_format($stats['inventario']['stock_critico']) }}</dd>
                                        <dd class="text-xs text-gray-500 mt-1">{{ $stats['inventario']['stock_agotado'] }} agotados</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stock Bajo -->
                    <div class="overflow-hidden bg-white rounded-lg shadow hover:shadow-md transition-shadow border-l-4 border-yellow-500">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 w-0 ml-4">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Stock Bajo</dt>
                                        <dd class="text-2xl font-bold text-yellow-600">{{ number_format($stats['inventario']['stock_bajo']) }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stock Normal -->
                    <div class="overflow-hidden bg-white rounded-lg shadow hover:shadow-md transition-shadow border-l-4 border-green-500">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 w-0 ml-4">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Stock Normal</dt>
                                        <dd class="text-2xl font-bold text-green-600">{{ number_format($stats['inventario']['stock_normal']) }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Insumos con Stock Crítico -->
                <div class="mt-6 bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h4 class="text-base font-semibold text-gray-900">Insumos con Stock Crítico</h4>
                            <a href="{{ route('reportes.stock.index') }}" class="text-sm text-primary-600 hover:text-primary-800 font-medium">
                                Ver reporte completo →
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        @if($stats['recientes']['insumos_criticos']->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($stats['recientes']['insumos_criticos']->take(9) as $insumo)
                                    <div class="p-4 bg-red-50 rounded-lg border border-red-200">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $insumo->nombre_insumo }}</p>
                                                <div class="mt-2 flex items-center space-x-4">
                                                    <div>
                                                        <p class="text-xs text-gray-500">Stock Actual</p>
                                                        <p class="text-lg font-bold {{ $insumo->stock_actual <= 0 ? 'text-red-600' : 'text-orange-600' }}">
                                                            {{ $insumo->stock_actual }}
                                                        </p>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs text-gray-500">Stock Mínimo</p>
                                                        <p class="text-sm font-medium text-gray-700">{{ $insumo->stock_minimo }}</p>
                                                    </div>
                                                </div>
                                                @if($insumo->unidadMedida)
                                                    <p class="text-xs text-gray-500 mt-1">{{ $insumo->unidadMedida->nombre_unidad }}</p>
                                                @endif
                                            </div>
                                            <div class="ml-2">
                                                @if($insumo->stock_actual <= 0)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        Agotado
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                        Bajo
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">Todos los insumos tienen stock adecuado</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- ========== SECCIÓN: SOLICITUDES ========== -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Solicitudes
                </h3>
                
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                    <!-- Total Solicitudes -->
                    <div class="overflow-hidden bg-white rounded-lg shadow hover:shadow-md transition-shadow">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 w-0 ml-4">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Total Solicitudes</dt>
                                        <dd class="text-2xl font-bold text-gray-900">{{ number_format($stats['solicitudes']['total']) }}</dd>
                                        <dd class="text-xs text-gray-500 mt-1">{{ $stats['solicitudes']['mes_actual'] }} este mes</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pendientes -->
                    <div class="overflow-hidden bg-white rounded-lg shadow hover:shadow-md transition-shadow border-l-4 border-yellow-500">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 w-0 ml-4">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Pendientes</dt>
                                        <dd class="text-2xl font-bold text-yellow-600">{{ number_format($stats['solicitudes']['pendientes']) }}</dd>
                                        <dd class="text-xs text-gray-500 mt-1">Requieren atención</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Aprobadas -->
                    <div class="overflow-hidden bg-white rounded-lg shadow hover:shadow-md transition-shadow border-l-4 border-green-500">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 w-0 ml-4">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Aprobadas</dt>
                                        <dd class="text-2xl font-bold text-green-600">{{ number_format($stats['solicitudes']['aprobadas']) }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Entregadas -->
                    <div class="overflow-hidden bg-white rounded-lg shadow hover:shadow-md transition-shadow border-l-4 border-blue-500">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 w-0 ml-4">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Entregadas</dt>
                                        <dd class="text-2xl font-bold text-blue-600">{{ number_format($stats['solicitudes']['entregadas']) }}</dd>
                                        <dd class="text-xs text-gray-500 mt-1">{{ $stats['solicitudes']['entregadas_mes'] }} este mes</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Métricas de Cantidades -->
                <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div class="bg-white rounded-lg shadow p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Cantidad Solicitada</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['solicitudes']['total_cantidad_solicitada']) }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ number_format($stats['solicitudes']['cantidad_solicitada_mes']) }} este mes</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Cantidad Aprobada</p>
                                <p class="text-2xl font-bold text-green-600 mt-1">{{ number_format($stats['solicitudes']['total_cantidad_aprobada']) }}</p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Cantidad Entregada</p>
                                <p class="text-2xl font-bold text-blue-600 mt-1">{{ number_format($stats['solicitudes']['total_cantidad_entregada']) }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ number_format($stats['solicitudes']['cantidad_entregada_mes']) }} este mes</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========== SECCIÓN: ADMINISTRACIÓN DE SOLICITUDES ========== -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    Administración de Solicitudes
                </h3>

                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <!-- Solicitudes Pendientes de Aprobación -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200 bg-yellow-50">
                            <div class="flex items-center justify-between">
                                <h4 class="text-base font-semibold text-gray-900">Pendientes de Aprobación</h4>
                                <a href="{{ route('admin-solicitudes') }}?estado=pendiente" class="text-sm text-primary-600 hover:text-primary-800 font-medium">
                                    Ver todas →
                                </a>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Tiempo promedio: {{ $stats['administracion']['tiempo_promedio_aprobacion'] }} días</p>
                        </div>
                        <div class="p-6">
                            @if($stats['administracion']['pendientes_aprobacion']->count() > 0)
                                <div class="space-y-3">
                                    @foreach($stats['administracion']['pendientes_aprobacion']->take(5) as $solicitud)
                                        <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-200 hover:bg-yellow-100 transition-colors">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center space-x-2 mb-2">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            {{ $solicitud->numero_solicitud }}
                                                        </span>
                                                        @if($solicitud->tiempo_espera > 3)
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                                {{ $solicitud->tiempo_espera }} días
                                                            </span>
                                                        @else
                                                            <span class="text-xs text-gray-500">{{ $solicitud->tiempo_espera }} días</span>
                                                        @endif
                                                    </div>
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ $solicitud->departamento->nombre_depto ?? 'Sin departamento' }}
                                                    </p>
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        {{ $solicitud->user->nombre ?? 'Usuario' }} • {{ $solicitud->total_items }} items • {{ $solicitud->total_cantidad }} unidades
                                                    </p>
                                                    @if($solicitud->fecha_solicitud)
                                                        <p class="text-xs text-gray-400 mt-1">{{ $solicitud->fecha_solicitud->format('d/m/Y H:i') }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-12 w-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500">No hay solicitudes pendientes de aprobación</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Solicitudes Aprobadas Pendientes de Entrega -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200 bg-green-50">
                            <div class="flex items-center justify-between">
                                <h4 class="text-base font-semibold text-gray-900">Aprobadas Pendientes de Entrega</h4>
                                <a href="{{ route('admin-solicitudes') }}?estado=aprobada" class="text-sm text-primary-600 hover:text-primary-800 font-medium">
                                    Ver todas →
                                </a>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Tiempo promedio: {{ $stats['administracion']['tiempo_promedio_entrega'] }} días</p>
                        </div>
                        <div class="p-6">
                            @if($stats['administracion']['aprobadas_pendientes_entrega']->count() > 0)
                                <div class="space-y-3">
                                    @foreach($stats['administracion']['aprobadas_pendientes_entrega']->take(5) as $solicitud)
                                        <div class="p-4 bg-green-50 rounded-lg border border-green-200 hover:bg-green-100 transition-colors">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center space-x-2 mb-2">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            {{ $solicitud->numero_solicitud }}
                                                        </span>
                                                        @if($solicitud->tiempo_espera > 2)
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                                {{ $solicitud->tiempo_espera }} días
                                                            </span>
                                                        @else
                                                            <span class="text-xs text-gray-500">{{ $solicitud->tiempo_espera }} días</span>
                                                        @endif
                                                    </div>
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ $solicitud->departamento->nombre_depto ?? 'Sin departamento' }}
                                                    </p>
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        {{ $solicitud->user->nombre ?? 'Usuario' }} • {{ $solicitud->total_items }} items • {{ $solicitud->total_cantidad }} unidades
                                                    </p>
                                                    @if($solicitud->fecha_aprobacion)
                                                        <p class="text-xs text-gray-400 mt-1">Aprobada: {{ $solicitud->fecha_aprobacion->format('d/m/Y H:i') }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-12 w-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500">Todas las solicitudes aprobadas han sido entregadas</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========== SECCIÓN: INFORMACIÓN ADICIONAL ========== -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Solicitudes Recientes -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h4 class="text-base font-semibold text-gray-900">Solicitudes Recientes</h4>
                            <a href="{{ route('solicitudes') }}" class="text-sm text-primary-600 hover:text-primary-800 font-medium">
                                Ver todas →
                            </a>
                        </div>
                    </div>
                    <div class="overflow-hidden">
                        <div class="divide-y divide-gray-200">
                            @forelse($stats['recientes']['solicitudes']->take(8) as $solicitud)
                                <div class="p-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center space-x-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    @if($solicitud->estado == 'pendiente') bg-yellow-100 text-yellow-800
                                                    @elseif($solicitud->estado == 'aprobada') bg-green-100 text-green-800
                                                    @elseif($solicitud->estado == 'entregada') bg-blue-100 text-blue-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ ucfirst($solicitud->estado) }}
                                                </span>
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $solicitud->numero_solicitud }}
                                                </p>
                                            </div>
                                            <p class="mt-1 text-xs text-gray-500">
                                                {{ $solicitud->departamento->nombre_depto ?? 'Sin departamento' }} • {{ $solicitud->fecha_solicitud ? $solicitud->fecha_solicitud->format('d/m/Y H:i') : 'Sin fecha' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-8 text-center">
                                    <p class="text-sm text-gray-500">No hay solicitudes recientes</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Top Departamentos -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h4 class="text-base font-semibold text-gray-900">Departamentos con Más Solicitudes</h4>
                    </div>
                    <div class="p-6">
                        @if($stats['top']['departamentos']->count() > 0)
                            <div class="space-y-4">
                                @foreach($stats['top']['departamentos'] as $departamento)
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900">{{ $departamento->nombre_depto }}</p>
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            <div class="w-32 bg-gray-200 rounded-full h-2">
                                                <div class="bg-primary-600 h-2 rounded-full" style="width: {{ min(100, ($departamento->solicitudes_count / max(1, $stats['solicitudes']['total'])) * 100) }}%"></div>
                                            </div>
                                            <span class="text-sm font-semibold text-gray-900 w-12 text-right">
                                                {{ $departamento->solicitudes_count }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-sm text-gray-500">No hay datos disponibles</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
