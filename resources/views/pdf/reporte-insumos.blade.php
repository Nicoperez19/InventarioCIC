<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Insumos - {{ ucfirst($tipoPeriodo) }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #2563eb;
        }
        .header h1 {
            font-size: 24px;
            color: #1e40af;
            margin-bottom: 5px;
        }
        .header .subtitle {
            font-size: 14px;
            color: #666;
        }
        .info-section {
            margin-bottom: 25px;
            padding: 15px;
            background-color: #f3f4f6;
            border-radius: 5px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        .info-label {
            font-weight: bold;
            color: #374151;
        }
        .info-value {
            color: #111827;
        }
        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        .section-title {
            font-size: 18px;
            color: #1e40af;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #3b82f6;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table thead {
            background-color: #2563eb;
            color: white;
        }
        table th {
            padding: 10px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
        }
        table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e5e7eb;
        }
        table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        table tbody tr:hover {
            background-color: #f3f4f6;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }
        .stat-card {
            padding: 15px;
            background-color: #f9fafb;
            border-left: 4px solid #2563eb;
            border-radius: 5px;
        }
        .stat-label {
            font-size: 11px;
            color: #6b7280;
            margin-bottom: 5px;
        }
        .stat-value {
            font-size: 20px;
            font-weight: bold;
            color: #111827;
        }
        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
        }
        .no-data {
            text-align: center;
            padding: 20px;
            color: #6b7280;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Gestión de Insumos - InventarioCIC</h1>
        <div class="subtitle">Reporte de Insumos - Período {{ ucfirst($tipoPeriodo) }}</div>
    </div>

    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Período:</span>
            <span class="info-value">{{ $fechaInicio->format('d/m/Y') }} - {{ $fechaFin->format('d/m/Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Fecha de Generación:</span>
            <span class="info-value">{{ $fecha }}</span>
        </div>
    </div>

    <!-- Estadísticas Generales -->
    <div class="section">
        <h2 class="section-title">Estadísticas Generales</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total de Solicitudes</div>
                <div class="stat-value">{{ $estadisticas['total_solicitudes'] ?? 0 }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Insumos Solicitados</div>
                <div class="stat-value">{{ number_format($estadisticas['total_insumos_solicitados'] ?? 0) }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Insumos Únicos Solicitados</div>
                <div class="stat-value">{{ $estadisticas['insumos_unicos_solicitados'] ?? 0 }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Insumos No Solicitados</div>
                <div class="stat-value">{{ $estadisticas['insumos_no_solicitados'] ?? 0 }}</div>
            </div>
        </div>
    </div>

    <!-- Insumos Más Solicitados -->
    <div class="section">
        <h2 class="section-title">Insumos Más Solicitados</h2>
        @if(count($insumosMasSolicitados) > 0)
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Insumo</th>
                        <th>Tipo</th>
                        <th class="text-right">Total Solicitado</th>
                        <th class="text-right">Veces Solicitado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($insumosMasSolicitados as $index => $insumo)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $insumo->nombre_insumo }}</td>
                            <td>{{ $insumo->nombre_tipo ?? 'N/A' }}</td>
                            <td class="text-right">{{ number_format($insumo->total_solicitado) }}</td>
                            <td class="text-right">{{ $insumo->veces_solicitado }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">No hay datos disponibles para este período</div>
        @endif
    </div>

    <!-- Insumos No Solicitados -->
    <div class="section">
        <h2 class="section-title">Insumos No Solicitados</h2>
        @if(count($insumosNoSolicitados) > 0)
            <table>
                <thead>
                    <tr>
                        <th>Insumo</th>
                        <th>Tipo</th>
                        <th class="text-right">Stock Actual</th>
                        <th>Unidad</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($insumosNoSolicitados as $insumo)
                        <tr>
                            <td>{{ $insumo->nombre_insumo }}</td>
                            <td>{{ $insumo->tipoInsumo->nombre_tipo ?? 'N/A' }}</td>
                            <td class="text-right">{{ number_format($insumo->stock_actual) }}</td>
                            <td>{{ $insumo->unidadMedida->nombre_unidad_medida ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">Todos los insumos fueron solicitados en este período</div>
        @endif
    </div>

    <div class="footer">
        <p>Reporte generado el {{ $fecha }} por el sistema InventarioCIC</p>
        <p>Este es un documento generado automáticamente</p>
    </div>
</body>
</html>

