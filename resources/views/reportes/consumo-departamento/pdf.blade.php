<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Consumo por Departamento - GestionCIC</title>
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
            border-bottom: 3px solid #306073;
        }
        .header h1 {
            font-size: 24px;
            color: #306073;
            margin-bottom: 5px;
        }
        .header .subtitle {
            font-size: 14px;
            color: #525252;
        }
        .info-section {
            margin-bottom: 25px;
            padding: 15px;
            background-color: #f0f4f6;
            border-radius: 5px;
            border-left: 4px solid #306073;
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
            color: #306073;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #306073;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table thead {
            background-color: #306073;
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
            background-color: #fafafa;
        }
        .text-right {
            text-align: right;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }
        .stat-card {
            padding: 15px;
            background-color: #f0f4f6;
            border-left: 4px solid #306073;
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
    </style>
</head>
<body>
    <div class="header">
        <h1>Gestión de Insumos - GestionCIC</h1>
        <div class="subtitle">Reporte de Consumo por Departamento - Período {{ ucfirst($tipoPeriodo) }}</div>
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
                <div class="stat-label">Total Consumido</div>
                <div class="stat-value">{{ number_format($estadisticas['total_consumido'] ?? 0) }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Departamentos Activos</div>
                <div class="stat-value">{{ $estadisticas['departamentos_activos'] ?? 0 }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Solicitudes</div>
                <div class="stat-value">{{ $estadisticas['total_solicitudes'] ?? 0 }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Eficiencia de Entrega</div>
                <div class="stat-value">{{ $estadisticas['eficiencia_entrega'] ?? 0 }}%</div>
            </div>
        </div>
    </div>

    <!-- Consumo por Departamento -->
    <div class="section">
        <h2 class="section-title">Consumo por Departamento</h2>
        @if(count($consumoPorDepartamento) > 0)
            <table>
                <thead>
                    <tr>
                        <th>Departamento</th>
                        <th class="text-right">Total Entregado</th>
                        <th class="text-right">Total Solicitado</th>
                        <th class="text-right">Total Solicitudes</th>
                        <th class="text-right">Insumos Diferentes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($consumoPorDepartamento as $depto)
                        <tr>
                            <td>{{ $depto->nombre_depto }}</td>
                            <td class="text-right">{{ number_format($depto->total_entregado) }}</td>
                            <td class="text-right">{{ number_format($depto->total_solicitado) }}</td>
                            <td class="text-right">{{ $depto->total_solicitudes }}</td>
                            <td class="text-right">{{ $depto->insumos_diferentes }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">No hay datos disponibles para este período</div>
        @endif
    </div>

    <div class="footer">
        <p>Reporte generado el {{ $fecha }} por el sistema GestionCIC</p>
        <p>Este es un documento generado automáticamente</p>
    </div>
</body>
</html>

