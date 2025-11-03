<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Solicitudes - GestionCIC</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #333;
            padding: 15px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #1e40af;
            padding-bottom: 10px;
        }

        .header h1 {
            font-size: 18px;
            color: #1e40af;
            margin-bottom: 5px;
        }

        .header .subtitle {
            font-size: 12px;
            color: #666;
        }

        .info-section {
            margin-bottom: 15px;
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            border-left: 4px solid #1e40af;
            font-size: 9px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .info-label {
            font-weight: bold;
            color: #555;
        }

        .info-value {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background-color: white;
            font-size: 9px;
        }

        thead {
            background-color: #1e40af;
            color: white;
        }

        th {
            padding: 6px 4px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #1e3a8a;
            font-size: 9px;
        }

        td {
            padding: 5px 4px;
            border: 1px solid #ddd;
            font-size: 8px;
        }

        tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }

        .badge-pendiente {
            background-color: #fef3c7;
            color: #92400e;
        }

        .badge-aprobada {
            background-color: #d1fae5;
            color: #065f46;
        }

        .badge-rechazada {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .badge-entregada {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 2px solid #ddd;
            text-align: center;
            font-size: 8px;
            color: #666;
        }

        .summary {
            margin-top: 15px;
            padding: 10px;
            background-color: #eff6ff;
            border-radius: 5px;
            border-left: 4px solid #3b82f6;
            font-size: 9px;
        }

        .summary-title {
            font-weight: bold;
            font-size: 11px;
            color: #1e40af;
            margin-bottom: 8px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Gestión de Solicitudes - GestionCIC</h1>
        <div class="subtitle">Reporte de Solicitudes de Insumos</div>
    </div>

    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Fecha de Generación:</span>
            <span class="info-value">{{ $fecha }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Total de Solicitudes:</span>
            <span class="info-value" style="font-weight: bold;">{{ $solicitudes->count() }}</span>
        </div>
        @if($filtros['search'] || $filtros['estadoFiltro'] || $filtros['departamentoFiltro'] || $filtros['fechaDesde'] || $filtros['fechaHasta'])
            <div class="info-row">
                <span class="info-label">Filtros Aplicados:</span>
                <span class="info-value">
                    @php
                        $filtrosAplicados = [];
                        if ($filtros['search']) {
                            $filtrosAplicados[] = "Búsqueda: " . $filtros['search'];
                        }
                        if ($filtros['estadoFiltro']) {
                            $filtrosAplicados[] = "Estado: " . ucfirst($filtros['estadoFiltro']);
                        }
                        if ($filtros['departamentoFiltro']) {
                            $filtrosAplicados[] = "Departamento: " . $filtros['departamentoFiltro']->nombre_depto;
                        }
                        if ($filtros['fechaDesde']) {
                            $filtrosAplicados[] = "Desde: " . $filtros['fechaDesde'];
                        }
                        if ($filtros['fechaHasta']) {
                            $filtrosAplicados[] = "Hasta: " . $filtros['fechaHasta'];
                        }
                    @endphp
                    {{ implode(' | ', $filtrosAplicados) }}
                </span>
            </div>
        @endif
    </div>

    @if($solicitudes->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 12%;">N° Solicitud</th>
                    <th style="width: 15%;">Usuario</th>
                    <th style="width: 15%;">Departamento</th>
                    <th style="width: 10%;" class="text-center">Estado</th>
                    <th style="width: 12%;">Fecha</th>
                    <th style="width: 10%;" class="text-center">Items</th>
                    <th style="width: 12%;" class="text-center">Total Unidades</th>
                    <th style="width: 14%;">Tipo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($solicitudes as $solicitud)
                    <tr>
                        <td>{{ $solicitud->numero_solicitud }}</td>
                        <td>{{ $solicitud->user->nombre ?? 'N/A' }}</td>
                        <td>{{ $solicitud->departamento->nombre_depto ?? 'N/A' }}</td>
                        <td class="text-center">
                            @php
                                $estadoClass = 'badge-' . $solicitud->estado;
                            @endphp
                            <span class="badge {{ $estadoClass }}">{{ ucfirst($solicitud->estado) }}</span>
                        </td>
                        <td>{{ $solicitud->fecha_solicitud->format('d/m/Y H:i') }}</td>
                        <td class="text-center">{{ $solicitud->items->count() }}</td>
                        <td class="text-center">{{ $solicitud->items->sum('cantidad_solicitada') }}</td>
                        <td>{{ ucfirst($solicitud->tipo_solicitud) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary">
            <div class="summary-title">Resumen de Solicitudes</div>
            <div class="summary-row">
                <span>Total de Solicitudes:</span>
                <span style="font-weight: bold;">{{ $solicitudes->count() }}</span>
            </div>
            <div class="summary-row">
                <span>Pendientes:</span>
                <span style="font-weight: bold;">{{ $solicitudes->where('estado', 'pendiente')->count() }}</span>
            </div>
            <div class="summary-row">
                <span>Aprobadas:</span>
                <span style="font-weight: bold;">{{ $solicitudes->where('estado', 'aprobada')->count() }}</span>
            </div>
            <div class="summary-row">
                <span>Rechazadas:</span>
                <span style="font-weight: bold;">{{ $solicitudes->where('estado', 'rechazada')->count() }}</span>
            </div>
            <div class="summary-row">
                <span>Entregadas:</span>
                <span style="font-weight: bold;">{{ $solicitudes->where('estado', 'entregada')->count() }}</span>
            </div>
            <div class="summary-row">
                <span>Total de Items:</span>
                <span style="font-weight: bold;">{{ $solicitudes->sum(fn($s) => $s->items->count()) }}</span>
            </div>
            <div class="summary-row">
                <span>Total de Unidades Solicitadas:</span>
                <span style="font-weight: bold;">{{ $solicitudes->sum(fn($s) => $s->items->sum('cantidad_solicitada')) }}</span>
            </div>
        </div>
    @else
        <div style="text-align: center; padding: 40px; background-color: #f8f9fa; border-radius: 5px; margin-top: 20px;">
            <p style="font-size: 12px; color: #666;">No hay solicitudes que coincidan con los filtros aplicados.</p>
        </div>
    @endif

    <div class="footer">
        <p>Generado el {{ $fecha }} | GestionCIC - Sistema de Gestión de Insumos</p>
    </div>
</body>

</html>

