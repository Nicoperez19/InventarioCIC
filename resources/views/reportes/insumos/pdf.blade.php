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
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #306073;
            padding-bottom: 15px;
        }

        .header h1 {
            font-size: 20px;
            color: #306073;
            margin-bottom: 5px;
        }

        .header .subtitle {
            font-size: 14px;
            color: #525252;
        }

        .info-section {
            margin-bottom: 20px;
            background-color: #f0f4f6;
            padding: 15px;
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
            color: #555;
        }

        .info-value {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
        }

        thead {
            background-color: #306073;
            color: white;
        }

        th {
            padding: 10px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #264d5c;
        }

        td {
            padding: 8px 10px;
            border: 1px solid #e5e5e5;
        }

        tbody tr:nth-child(even) {
            background-color: #fafafa;
        }

        tbody tr:hover {
            background-color: #f4f5f0;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .summary {
            margin-top: 20px;
            padding: 15px;
            background-color: #f0f4f6;
            border-radius: 5px;
            border-left: 4px solid #306073;
        }

        .summary-title {
            font-weight: bold;
            font-size: 14px;
            color: #306073;
            margin-bottom: 10px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .section-title {
            font-size: 16px;
            color: #306073;
            margin-top: 30px;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #306073;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            background-color: #fafafa;
            border-radius: 5px;
            margin-top: 20px;
            color: #525252;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Gestión de Insumos - GestionCIC</h1>
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
    <div class="summary">
        <div class="summary-title">Estadísticas Generales</div>
        <div class="summary-row">
            <span>Total de Solicitudes:</span>
            <span style="font-weight: bold;">{{ $estadisticas['total_solicitudes'] ?? 0 }}</span>
        </div>
        <div class="summary-row">
            <span>Total Insumos Solicitados:</span>
            <span style="font-weight: bold;">{{ number_format($estadisticas['total_insumos_solicitados'] ?? 0) }} unidades</span>
        </div>
        <div class="summary-row">
            <span>Insumos Únicos Solicitados:</span>
            <span style="font-weight: bold;">{{ $estadisticas['insumos_unicos_solicitados'] ?? 0 }}</span>
        </div>
        <div class="summary-row">
            <span>Insumos No Solicitados:</span>
            <span style="font-weight: bold;">{{ $estadisticas['insumos_no_solicitados'] ?? 0 }}</span>
        </div>
        <div class="summary-row">
            <span>Total de Insumos en Sistema:</span>
            <span style="font-weight: bold;">{{ $estadisticas['total_insumos'] ?? 0 }}</span>
        </div>
        <div class="summary-row">
            <span>Porcentaje de Utilización:</span>
            <span style="font-weight: bold;">{{ $estadisticas['porcentaje_utilizacion'] ?? 0 }}%</span>
        </div>
    </div>

    <!-- Insumos Más Solicitados -->
    <h2 class="section-title">Insumos Más Solicitados</h2>
    @if(count($insumosMasSolicitados) > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;" class="text-center">#</th>
                    <th style="width: 40%;">Insumo</th>
                    <th style="width: 25%;">Tipo</th>
                    <th style="width: 15%;" class="text-right">Total Solicitado</th>
                    <th style="width: 15%;" class="text-right">Veces Solicitado</th>
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
        <div class="no-data">
            <p style="font-size: 14px;">No hay datos disponibles para este período</p>
        </div>
    @endif

    <!-- Insumos No Solicitados -->
    <h2 class="section-title">Insumos No Solicitados</h2>
    @if(count($insumosNoSolicitados) > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 40%;">Insumo</th>
                    <th style="width: 30%;">Tipo</th>
                    <th style="width: 15%;" class="text-right">Stock Actual</th>
                    <th style="width: 15%;">Unidad</th>
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
        <div class="no-data">
            <p style="font-size: 14px;">Todos los insumos fueron solicitados en este período</p>
        </div>
    @endif

    <div class="footer">
        <p>Generado el {{ $fecha }} | GestionCIC</p>
        <p>Este es un documento generado automáticamente</p>
    </div>
</body>

</html>
