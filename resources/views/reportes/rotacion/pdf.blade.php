<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Rotación de Inventario - GestionCIC</title>
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
        <h1>Gestión de Insumos - GestionCIC</h1>
        <div class="subtitle">Reporte de Rotación de Inventario - Período {{ ucfirst($tipoPeriodo) }}</div>
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
                <div class="stat-label">Total de Insumos</div>
                <div class="stat-value">{{ $estadisticas['total_insumos'] ?? 0 }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Alta Rotación</div>
                <div class="stat-value">{{ $estadisticas['alta_rotacion'] ?? 0 }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Baja Rotación</div>
                <div class="stat-value">{{ $estadisticas['baja_rotacion'] ?? 0 }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Sin Rotación</div>
                <div class="stat-value">{{ $estadisticas['insumos_sin_rotacion'] ?? 0 }}</div>
            </div>
        </div>
    </div>

    <!-- Insumos con Alta Rotación -->
    <div class="section">
        <h2 class="section-title">Insumos con Alta Rotación</h2>
        @if(count($altaRotacion) > 0)
            <table>
                <thead>
                    <tr>
                        <th>Insumo</th>
                        <th>Tipo</th>
                        <th class="text-right">Stock</th>
                        <th class="text-right">Consumo</th>
                        <th class="text-right">Rotación</th>
                        <th class="text-right">Días Rotación</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($altaRotacion as $item)
                        <tr>
                            <td>{{ $item['nombre_insumo'] }}</td>
                            <td>{{ $item['tipo_insumo'] }}</td>
                            <td class="text-right">{{ $item['stock_actual'] }}</td>
                            <td class="text-right">{{ number_format($item['consumo_total']) }}</td>
                            <td class="text-right">{{ $item['rotacion'] }}</td>
                            <td class="text-right">{{ $item['dias_rotacion'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">No hay insumos con alta rotación en este período</div>
        @endif
    </div>

    <!-- Insumos con Baja Rotación -->
    <div class="section">
        <h2 class="section-title">Insumos con Baja Rotación</h2>
        @if(count($bajaRotacion) > 0)
            <table>
                <thead>
                    <tr>
                        <th>Insumo</th>
                        <th>Tipo</th>
                        <th class="text-right">Stock</th>
                        <th class="text-right">Consumo</th>
                        <th class="text-right">Rotación</th>
                        <th class="text-right">Días Rotación</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bajaRotacion as $item)
                        <tr>
                            <td>{{ $item['nombre_insumo'] }}</td>
                            <td>{{ $item['tipo_insumo'] }}</td>
                            <td class="text-right">{{ $item['stock_actual'] }}</td>
                            <td class="text-right">{{ number_format($item['consumo_total']) }}</td>
                            <td class="text-right">{{ $item['rotacion'] }}</td>
                            <td class="text-right">{{ $item['dias_rotacion'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">No hay insumos con baja rotación en este período</div>
        @endif
    </div>

    <!-- Insumos Sin Rotación -->
    <div class="section">
        <h2 class="section-title">Insumos Sin Rotación</h2>
        @if(count($sinRotacion) > 0)
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
                    @foreach($sinRotacion as $insumo)
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
            <div class="no-data">Todos los insumos tuvieron rotación en este período</div>
        @endif
    </div>

    <div class="footer">
        <p>Reporte generado el {{ $fecha }} por el sistema GestionCIC</p>
        <p>Este es un documento generado automáticamente</p>
    </div>
</body>
</html>

